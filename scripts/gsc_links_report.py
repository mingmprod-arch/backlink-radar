#!/usr/bin/env python3
"""Analyse a Google Search Console "Links" export (CSV) for your own site.

GSC is the only free *ground truth* for your own backlink profile: it shows
what Google actually counts. Export from:
  Search Console -> Links -> Top linking sites / Top linked pages -> Export.

Usage:
    python3 gsc_links_report.py top_linking_sites.csv [top_linked_pages.csv]
"""

import csv
import sys
from collections import Counter


def load(path):
    with open(path, newline="", encoding="utf-8-sig") as f:
        rows = list(csv.reader(f))
    header, data = rows[0], rows[1:]
    return [h.strip().lower() for h in header], data


def main():
    if len(sys.argv) < 2:
        print(__doc__)
        sys.exit(1)

    header, data = load(sys.argv[1])
    # GSC top-linking-sites CSV: "Site,Links" (or "Target pages")
    try:
        site_i = header.index("site")
        links_i = header.index("links")
    except ValueError:
        site_i, links_i = 0, 1

    total = 0
    top = []
    for row in data:
        if len(row) <= max(site_i, links_i):
            continue
        try:
            n = int(row[links_i].replace(",", ""))
        except ValueError:
            continue
        total += n
        top.append((row[site_i], n))

    print(f"Total observed links: {total:,}")
    print(f"Referring domains in export: {len(top)}\n")
    print("Top 20 linking sites:")
    for site, n in top[:20]:
        share = n / total * 100 if total else 0
        print(f"  {n:>7,}  ({share:4.1f}%)  {site}")

    # Concentration warning: if top 5 domains carry most links, profile is fragile
    if total:
        top5 = sum(n for _, n in top[:5])
        if top5 / total > 0.5:
            print(
                f"\n⚠ Top 5 domains carry {top5 / total * 100:.0f}% of links — "
                "profile is concentrated; diversify referring domains."
            )

    if len(sys.argv) > 2:
        h2, d2 = load(sys.argv[2])
        page_i, link_i = 0, 1
        for cand in ("target page", "page", "url"):
            if cand in h2:
                page_i = h2.index(cand)
                break
        print("\nTop linked pages (your proven link magnets):")
        parsed = []
        for row in d2:
            if len(row) <= link_i:
                continue
            try:
                parsed.append((row[page_i], int(row[link_i].replace(",", ""))))
            except ValueError:
                continue
        parsed.sort(key=lambda x: -x[1])
        for url, n in parsed[:15]:
            print(f"  {n:>7,}  {url}")
        print(
            "\n→ Create more content in the pattern of your top linked pages; "
            "they are empirically what your niche links to."
        )


if __name__ == "__main__":
    main()
