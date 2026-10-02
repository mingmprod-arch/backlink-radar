#!/usr/bin/env python3
"""Free domain-authority check.

There is no truly free DR clone — Ahrefs DR and Moz DA are proprietary.
What IS free:

  1. Open PageRank (openpagerank.com) — free API key, returns a 0–10
     PageRank-style score computed from Common Crawl + other open graphs.
     Set OPR_API_KEY to use it.
  2. Common Crawl presence proxy — how many URLs of the domain the latest
     crawl holds, a rough size/freshness signal. Always runs, no key.
  3. Ahrefs' free Website Authority Checker (web UI) for a real DR number
     when you need it: https://ahrefs.com/website-authority-checker

Usage:
    python3 dr_check.py example.com
    OPR_API_KEY=xxxx python3 dr_check.py example.com
"""
import json, os, sys, urllib.request, urllib.parse

UA = {"User-Agent": "backlink-radar/1.0"}

def openpagerank(domain, key):
    q = urllib.parse.urlencode({"domains[0]": domain})
    req = urllib.request.Request(
        f"https://openpagerank.com/api/v1.0/getPageRank?{q}",
        headers={**UA, "API-OPR": key})
    with urllib.request.urlopen(req, timeout=15) as r:
        data = json.load(r)
    res = data.get("response", [])
    if res:
        return res[0].get("page_rank_decimal"), res[0].get("rank")
    return None, None

def cc_presence(domain):
    with urllib.request.urlopen("https://index.commoncrawl.org/collinfo.json", timeout=15) as r:
        cdx = json.load(r)[0]["cdx-api"]
    q = urllib.parse.urlencode({"url": f"{domain}/*", "output": "json",
                                "filter": "status:200", "limit": "1",
                                "showNumPages": "true"})
    req = urllib.request.Request(f"{cdx}?{q}", headers=UA)
    with urllib.request.urlopen(req, timeout=30) as r:
        head = r.read().decode("utf-8", "ignore").splitlines()
    if head and head[0].startswith("{"):
        meta = json.loads(head[0])
        return meta.get("numPages", 0)  # 每頁 15000 條記錄
    return 0

def main():
    domain = sys.argv[1].replace("https://", "").replace("http://", "").split("/")[0]
    key = os.environ.get("OPR_API_KEY")
    print(f"\nAuthority check: {domain}\n")

    if key:
        try:
            score, rank = openpagerank(domain, key)
            print(f"  Open PageRank : {score}/10" + (f"  (global rank ~{int(rank):,})" if rank else ""))
        except Exception as e:
            print(f"  Open PageRank : lookup failed ({e})")
    else:
        print("  Open PageRank : skipped — set OPR_API_KEY (free at openpagerank.com)")

    try:
        pages = cc_presence(domain)
        est = pages * 15000
        print(f"  Common Crawl  : ~{est:,} URLs in latest crawl (size signal, NOT a DR score)")
    except Exception as e:
        print(f"  Common Crawl  : lookup failed ({e})")

    print("\n  For a real DR number, run the domain through Ahrefs' free checker:")
    print("  https://ahrefs.com/website-authority-checker")
    print("\n  Honesty note: no free API reproduces Ahrefs DR. Use DR for quick")
    print("  triage only — topical relevance and traffic matter more for links.\n")

if __name__ == "__main__":
    main()
