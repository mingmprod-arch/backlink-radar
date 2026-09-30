# Free backlink data sources — honest capability sheet

## 1. Common Crawl (free, any site)

- **Index API**: `https://index.commoncrawl.org/collinfo.json` lists crawls;
  query `…-index?url=pattern&output=json`. Free, rate-limited, no key.
- **Web graph datasets**: `https://commoncrawl.org/web-graphs` — free
  host-level and domain-level link lists per crawl on S3. This is the closest
  thing to a free Ahrefs index: download the domain graph, look up who
  points at any domain. Data lags by weeks–months.
- **Limits**: no anchor-text reliability at domain level, no dofollow/nofollow
  flag, no traffic estimates. Say "observed in crawl CC-MAIN-YYYY-WW".

## 2. Google Search Console (free, your sites only)

- Official, what Google actually counts. Links → Top linking sites / Top
  linked pages → Export CSV. Feed to `scripts/gsc_links_report.py`.
- Cannot see competitors. No anchor distribution export since 2019-ish
  changes; anchors visible only per-link in UI samples.

## 3. Free web checkers (free sample, any site)

- Ahrefs free backlink checker (top 100 links, DR), Moz Link Explorer free
  tier (10 queries/mo with account), Semrush free account (10 requests/day).
- Use for: spot checks, anchor samples, authority metrics. Not scriptable
  without an API key; keep it manual.

## 4. Bing Webmaster Tools (free, your sites)

- Backlinks report for verified sites; historically allowed "any site"
  comparison — availability varies, verify before promising.

## 5. Paid upgrade path (when free runs out)

- **DataForSEO backlinks API**: pay per call (~$1–5 per domain report).
  This is exactly what OpenSEO uses. BYOK, no subscription.
- Ahrefs / Semrush APIs: full depth, subscription-priced.

## Metric guidance

- Report **referring domains** as the headline number; raw link counts are
  easily inflated by sitewide/footer links.
- Free sources cannot confirm dofollow vs nofollow — only the page's HTML
  (`rel` attribute) can, and it changes. When it matters, fetch the source
  page and check.
