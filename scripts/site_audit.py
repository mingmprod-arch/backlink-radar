#!/usr/bin/env python3
"""Free on-page SEO + GEO audit for any URL.

Checks the stuff that actually moves the needle, using nothing but the page
itself and its robots/sitemap files — no API keys, no paid tools:

  On-page SEO : title, meta description, canonical, OG/Twitter cards,
                heading structure, image alts, viewport, schema.org JSON-LD
  GEO (AI)    : robots.txt stance toward AI crawlers (GPTBot, ClaudeBot,
                PerplexityBot, Google-Extended), llms.txt, sitemap,
                content extractability (SSR vs JS-only), answer-style
                structure (descriptive H2s, FAQ schema)

Usage:
    python3 site_audit.py https://example.com
    python3 site_audit.py https://example.com --json
"""
import argparse, json, re, sys, urllib.request, urllib.parse, gzip
import xml.etree.ElementTree as ET

UA = {"User-Agent": "Mozilla/5.0 (compatible; SiteAudit/1.0; +https://github.com/mingmprod-arch/backlink-radar)"}
AI_BOTS = ["GPTBot", "ClaudeBot", "PerplexityBot", "Google-Extended", "CCBot", "Bytespider", "Amazonbot"]

def fetch(url, timeout=15, cap=800_000):
    req = urllib.request.Request(url, headers=UA)
    with urllib.request.urlopen(req, timeout=timeout) as r:
        data = r.read(cap)
        if url.endswith(".gz"):
            data = gzip.decompress(data)
        return r.status, r.geturl(), data.decode("utf-8", "ignore")

def meta(html, name=None, prop=None):
    if name:
        m = re.search(r'<meta[^>]+name=["\']%s["\'][^>]+content=["\']([^"\']*)' % re.escape(name), html, re.I)
    else:
        m = re.search(r'<meta[^>]+property=["\']%s["\'][^>]+content=["\']([^"\']*)' % re.escape(prop), html, re.I)
    return m.group(1).strip() if m else None

def check(results, group, item, ok, detail, fix=None):
    results.append({"group": group, "item": item, "ok": bool(ok), "detail": detail, "fix": fix})

def audit(url):
    results = []
    status, final_url, html = fetch(url)
    parsed = urllib.parse.urlparse(final_url)
    root = f"{parsed.scheme}://{parsed.netloc}"

    # ── On-page SEO ──
    title = (re.search(r"<title[^>]*>(.*?)</title>", html, re.I | re.S) or [None, ""])[1].strip()
    check(results, "seo", "title", 10 <= len(title) <= 65,
          f"{len(title)} chars: {title[:70]!r}" if title else "missing",
          "Keep 50–60 chars, primary keyword near the front" if not (10 <= len(title) <= 65) else None)

    desc = meta(html, "description") or ""
    check(results, "seo", "meta description", 50 <= len(desc) <= 170,
          f"{len(desc)} chars" if desc else "missing",
          "Write 120–155 chars with the query's intent answered" if not (50 <= len(desc) <= 170) else None)

    canon = re.search(r'<link[^>]+rel=["\']canonical["\'][^>]+href=["\']([^"\']+)', html, re.I)
    check(results, "seo", "canonical", canon is not None,
          canon.group(1) if canon else "missing",
          "Add <link rel=canonical> to avoid duplicate-URL dilution" if not canon else None)

    h1s = re.findall(r"<h1[^>]*>(.*?)</h1>", html, re.I | re.S)
    check(results, "seo", "single H1", len(h1s) == 1,
          f"{len(h1s)} H1 tag(s)",
          "Use exactly one H1 that states the page's topic" if len(h1s) != 1 else None)

    h2s = re.findall(r"<h2[^>]*>(.*?)</h2>", html, re.I | re.S)
    check(results, "seo", "H2 structure", len(h2s) >= 2,
          f"{len(h2s)} H2 sections",
          "Break content into scannable H2 sections" if len(h2s) < 2 else None)

    imgs = re.findall(r"<img\b[^>]*>", html, re.I)
    no_alt = [i for i in imgs if not re.search(r'alt=["\'][^"\']+', i, re.I)]
    check(results, "seo", "image alt text", len(no_alt) == 0,
          f"{len(imgs)} images, {len(no_alt)} missing alt",
          "Add descriptive alt text (accessibility + image SEO)" if no_alt else None)

    check(results, "seo", "viewport meta", 'name="viewport"' in html or "name='viewport'" in html,
          "present" if "viewport" in html else "missing",
          "Add <meta name=viewport content='width=device-width, initial-scale=1'>" if "viewport" not in html else None)

    jsonld = re.findall(r'<script[^>]+type=["\']application/ld\+json["\'][^>]*>(.*?)</script>', html, re.I | re.S)
    ld_types = []
    for block in jsonld:
        try:
            data = json.loads(block)
            items = data if isinstance(data, list) else [data]
            ld_types += [i.get("@type") for i in items if isinstance(i, dict) and i.get("@type")]
        except json.JSONDecodeError:
            pass
    check(results, "seo", "schema.org JSON-LD", len(jsonld) > 0,
          f"types: {', '.join(map(str, ld_types))}" if ld_types else ("present but unparseable" if jsonld else "missing"),
          "Add Organization/WebSite/Article/FAQPage schema" if not jsonld else None)

    check(results, "seo", "Open Graph", bool(meta(html, prop="og:title")),
          "og:title present" if meta(html, prop="og:title") else "missing",
          "Add og:title/og:description/og:image for social shares" if not meta(html, prop="og:title") else None)

    # ── GEO: can AI engines read & cite you? ──
    try:
        _, _, robots = fetch(root + "/robots.txt")
    except Exception:
        robots = ""
    blocked, allowed = [], []
    for bot in AI_BOTS:
        m = re.search(rf"(?im)^user-agent:\s*{re.escape(bot)}\s*$([\s\S]*?)(?=^user-agent:|\Z)", robots)
        if m and re.search(r"(?im)^disallow:\s*/\s*$", m.group(1)):
            blocked.append(bot)
        else:
            allowed.append(bot)
    check(results, "geo", "AI crawlers allowed", len(blocked) == 0,
          f"blocked: {', '.join(blocked)}" if blocked else f"all {len(AI_BOTS)} major AI bots may crawl",
          "Blocking GPTBot/PerplexityBot removes you from AI answers — allow them unless deliberate" if blocked else None)

    try:
        s, _, _ = fetch(root + "/llms.txt", timeout=8)
        has_llms = s == 200
    except Exception:
        has_llms = False
    check(results, "geo", "llms.txt", has_llms,
          "present" if has_llms else "missing",
          "Ship /llms.txt summarizing key pages — emerging standard for AI agents" if not has_llms else None)

    try:
        s, _, sm = fetch(root + "/sitemap.xml", timeout=8)
        n_urls = sm.count("<loc>") if s == 200 else 0
        has_sm = s == 200 and n_urls > 0
    except Exception:
        has_sm, n_urls = False, 0
    check(results, "geo", "sitemap.xml", has_sm,
          f"{n_urls} URLs" if has_sm else "missing",
          "Submit a sitemap — both Google and AI crawlers use it for discovery" if not has_sm else None)

    text = re.sub(r"<script\b[\s\S]*?</script>|<style\b[\s\S]*?</style>", " ", html, flags=re.I)
    text = re.sub(r"<[^>]+>", " ", text)
    words = len(text.split())
    check(results, "geo", "content extractable (SSR)", words >= 300,
          f"~{words} visible words",
          "Page looks JS-rendered — AI crawlers rarely execute JS; pre-render key content" if words < 300 else None)

    faq = any(t in ("FAQPage", "HowTo") for t in ld_types)
    q_h2 = sum(1 for h in h2s if "?" in h)
    check(results, "geo", "answer-style structure", faq or q_h2 >= 1,
          f"FAQ schema: {faq}, question H2s: {q_h2}",
          "Add FAQPage schema / question-form H2s — AI answers quote these directly" if not (faq or q_h2) else None)

    # answer-first block: is the opening paragraph short and direct (AIO chunk retrieval)
    paras = re.findall(r"<p[^>]*>(.*?)</p>", html, re.I | re.S)
    first_p_words = len(re.sub(r"<[^>]+>", "", paras[0]).split()) if paras else 0
    has_takeaway = bool(re.search(r"(?i)(key takeaway|tl;dr|in short|summary|重點|摘要)", html))
    check(results, "geo", "answer-first block", (0 < first_p_words <= 60) or has_takeaway,
          f"first paragraph ~{first_p_words} words; explicit takeaway block: {has_takeaway}",
          "Open with a 40–60 word direct answer, or add a 'Key takeaway' box per section" if not ((0 < first_p_words <= 60) or has_takeaway) else None)

    # freshness: AI citations skew toward content updated in the last 2-3 months
    has_date_schema = bool(re.search(r'"date(Published|Modified)"', html))
    has_visible_date = bool(re.search(r"(?i)(updated|last modified|更新)[^<]{0,40}20[12]\d", html)) or bool(re.search(r"20[12]\d[-/年][01]?\d", html))
    check(results, "geo", "freshness signal", has_date_schema or has_visible_date,
          f"datePublished/dateModified schema: {has_date_schema}, visible date: {has_visible_date}",
          "Show a visible 'Last updated' date + dateModified schema; refresh top pages every 3–6 months" if not (has_date_schema or has_visible_date) else None)

    # E-E-A-T: author byline / Person schema
    has_author = bool(meta(html, "author")) or bool(re.search(r'rel=["\']author', html, re.I)) or \
        any(t == "Person" for t in ld_types) or bool(re.search(r'"author"', html))
    check(results, "geo", "author byline (E-E-A-T)", has_author,
          "author signal found" if has_author else "no author byline / Person schema",
          "Name a real author with credentials — AI engines weight credible, attributable sources" if not has_author else None)

    # outbound citations: studies show citing authoritative sources + stats lifts AI citation rates 30-40%
    links_out = re.findall(r'<a\b[^>]+href=["\'](https?://[^"\']+)', html, re.I)
    ext = {urllib.parse.urlparse(l).netloc.replace("www.", "") for l in links_out
           if urllib.parse.urlparse(l).netloc.replace("www.", "") != parsed.netloc.replace("www.", "")}
    check(results, "geo", "outbound citations", len(ext) >= 2,
          f"{len(ext)} external domains cited",
          "Cite primary sources with years (studies, official docs) — cited content earns more AI citations" if len(ext) < 2 else None)

    # scannable structure: lists / tables (easier for AI to extract)
    n_lists = len(re.findall(r"<[uo]l\b", html, re.I))
    n_tables = len(re.findall(r"<table\b", html, re.I))
    check(results, "geo", "scannable lists/tables", (n_lists + n_tables) >= 2,
          f"{n_lists} lists, {n_tables} tables",
          "Use bullet lists and comparison tables — AI models extract these more reliably than dense prose" if (n_lists + n_tables) < 2 else None)

    return {"url": final_url, "status": status, "results": results}

def main():
    ap = argparse.ArgumentParser()
    ap.add_argument("url")
    ap.add_argument("--json", action="store_true")
    args = ap.parse_args()
    url = args.url if args.url.startswith("http") else "https://" + args.url
    try:
        out = audit(url)
    except Exception as e:
        print(f"fetch failed: {e}", file=sys.stderr)
        sys.exit(1)
    if args.json:
        print(json.dumps(out, indent=2, ensure_ascii=False))
        return
    passed = sum(1 for r in out["results"] if r["ok"])
    total = len(out["results"])
    print(f"\nAudit: {out['url']}  (HTTP {out['status']})  —  {passed}/{total} checks passed\n")
    for group in ("seo", "geo"):
        print("== On-page SEO ==" if group == "seo" else "\n== GEO (AI-engine readiness) ==")
        for r in [x for x in out["results"] if x["group"] == group]:
            mark = "✓" if r["ok"] else "✗"
            print(f"  {mark} {r['item']:<26} {r['detail']}")
            if r["fix"]:
                print(f"      → fix: {r['fix']}")
    print()

if __name__ == "__main__":
    main()
