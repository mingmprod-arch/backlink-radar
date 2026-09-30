#!/usr/bin/env python3
"""Free backlink discovery via Common Crawl.

Two modes:

  --coverage <domain>   (live, fast) Query the latest Common Crawl CDX
                        index for the target's own indexed URLs. Confirms
                        the crawl sees the site and gives page samples.
                        The CDX API is prefix-keyed, so it CANNOT answer
                        "who links to X" directly — that needs link data.

  (default) <domain>    Print the exact free recipes for extracting real
                        referring domains for the target from open datasets:

                        A) Common Crawl webgraph (free S3 bulk download)
                        B) BigQuery free tier (1 TB/mo) over the HTTP
                           Archive public dataset, which stores per-page
                           outgoing links for millions of sites

Why not just query the index for pages linking to the target? The CDX
index is keyed by URL prefix (SURT), so substring "contains this domain"
queries require a full scan and time out. The webgraph/HTTP-Archive paths
below are the honest free ways; both are used by real SEO tooling.

Usage:
    python3 commoncrawl_backlinks.py example.com
    python3 commoncrawl_backlinks.py example.com --coverage
"""

import argparse
import json
import sys
import urllib.parse
import urllib.request

CC_COLLINFO = "https://index.commoncrawl.org/collinfo.json"

WEBGRAPH_RECIPE = """\
=== A) Common Crawl webgraph (100% free, offline-capable) ===

1. Get the latest crawl id:
     curl -s https://index.commoncrawl.org/collinfo.json | head
2. Download the domain-level webgraph for that crawl from:
     https://data.commoncrawl.org/projects/hyperlinkgraph/cc-main-<YYYY-MM>/domain/
   Files: vertices.txt.gz (domain names), edges.txt.gz (src -> dst links).
3. Extract referring domains (one-liner, streams the edges):
     python3 - <<'PY'
   import gzip
   target = "{domain}"
   # 1) find the target's node id(s)
   ids = set()
   with gzip.open("vertices.txt.gz", "rt") as f:
       for line in f:
           parts = line.rstrip("\\n").split("\\t")
           if len(parts) == 2 and (parts[1] == target or parts[1].endswith("." + target)):
               ids.add(parts[0])
   # 2) collect sources pointing at it (rev domain graph: edges are dst -> src? check header)
   refs = set()
   with gzip.open("edges.txt.gz", "rt") as f, gzip.open("vertices.txt.gz", "rt") as v:
       names = {{}}
       for line in v:
           p = line.rstrip("\\n").split("\\t")
           if len(p) == 2:
               names[p[0]] = p[1]
       for line in f:
           p = line.split()
           if len(p) == 2 and p[1] in ids and p[0] in names:
               refs.add(names[p[0]])
   for r in sorted(refs):
       print(r)
   print(f"# {{len(refs)}} referring domains", file=__import__("sys").stderr)
   PY

Docs: https://commoncrawl.org/web-graphs
"""

BIGQUERY_RECIPE = """\
=== B) BigQuery free tier (1 TB query/month free) over HTTP Archive ===

HTTP Archive crawls millions of pages monthly and stores outgoing links.
Query who links to {domain} (standard SQL, run in the BigQuery console):

  SELECT DISTINCT NET.REG_DOMAIN(page) AS referring_domain
  FROM `httparchive.pages.latest_desktop` p,
       UNNEST(JSON_EXTRACT_ARRAY(p.payload, '$._links')) AS link
  WHERE JSON_VALUE(link, '$.href') LIKE '%//{domain}%'
     OR JSON_VALUE(link, '$.href') LIKE '%//www.{domain}%'

  -- page = URL of the linking page; filter out self-links as needed.

Free tier covers ~1 TB scanned/month; constrain with
_TABLE_SUFFIX / date partitions to stay inside it.
Setup: console.cloud.google.com -> enable BigQuery -> no card required
for the free sandbox.
"""

ATHENA_NOTE = """\
=== C) If you already use AWS: Athena over Common Crawl's parquet index
also works and stays within a few cents per query. See
https://commoncrawl.org/blog/index-to-warc-files-and-urls-in-columnar-format
"""


def http_json(url: str):
    req = urllib.request.Request(url, headers={"User-Agent": "backlink-radar/1.0"})
    with urllib.request.urlopen(req, timeout=60) as r:
        return json.load(r)


def http_ndjson(url: str):
    """CDX output=json is newline-delimited JSON, one record per line."""
    req = urllib.request.Request(url, headers={"User-Agent": "backlink-radar/1.0"})
    with urllib.request.urlopen(req, timeout=90) as r:
        for line in r:
            line = line.strip()
            if line:
                yield json.loads(line)


def coverage(domain: str, limit: int = 50):
    colls = http_json(CC_COLLINFO)
    rows = []
    used = None
    for coll in colls[:4]:  # newest first; fall back when the server 504s
        cdx = coll["cdx-api"]
        q = (
            f"{cdx}?url={urllib.parse.quote(domain + '/', safe='')}"
            f"&output=json&filter=status:200&limit={limit}"
        )
        try:
            rows = list(http_ndjson(q))
            used = cdx
            break
        except Exception as e:
            print(f"[warn] {coll['id']} unavailable ({e}); trying older crawl",
                  file=sys.stderr)
    if used is None:
        print("# all recent crawl indexes timed out; try again later", file=sys.stderr)
        return
    print(f"# crawl index: {used}", file=sys.stderr)
    print(f"# {len(rows)} of the site's own URLs observed (sample):", file=sys.stderr)
    for row in rows[:20]:
        print(" ", row.get("url", row))


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument("domain")
    ap.add_argument("--coverage", action="store_true",
                    help="live-check the domain's own presence in the latest crawl")
    args = ap.parse_args()

    if args.coverage:
        coverage(args.domain)
        return

    print(WEBGRAPH_RECIPE.format(domain=args.domain))
    print(BIGQUERY_RECIPE.format(domain=args.domain))
    print(ATHENA_NOTE)


if __name__ == "__main__":
    main()
