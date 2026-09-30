---
name: backlink-radar
description: >-
  Free-tier backlink intelligence for any website. Pull a domain's backlink
  profile and referring domains from genuinely free data sources (Common Crawl
  open web graph, Google Search Console exports, free checker tools), run
  competitor link-gap analysis, spot broken-link-building and unlinked-mention
  opportunities, and plan link-worthy assets. Use when the user asks about
  backlinks, referring domains, link building, link gap, off-page SEO,
  domain authority, or "who links to X".
license: MIT
---

# Backlink Radar

OpenSEO-style backlink workflows that run on **free data first**. Paid APIs
(DataForSEO, Ahrefs) are optional upgrades, never a requirement.

## Core principle

Backlink data is expensive because crawl infrastructure is expensive. Three
legitimate free sources exist — this skill knows when to use each:

| Source | Covers | Cost | Best for |
|---|---|---|---|
| Common Crawl web graph / index | Any site, raw link data | $0 | Bulk referring-domain discovery |
| Google Search Console | Sites you own only | $0 | Your own true link profile (official) |
| Ahrefs / Moz / Semrush free checkers | Any site, top ~100 links | $0 (web UI) | Quick spot checks, anchor samples |

See `references/data-sources.md` for endpoints, limits, and upgrade paths.

## Workflow 1 — Backlink profile for any domain (free)

1. Run `scripts/commoncrawl_backlinks.py <domain>` — it prints ready-to-run
   **free extraction recipes** (Common Crawl domain webgraph; BigQuery free
   tier over HTTP Archive link data). `--coverage` live-checks the site's
   presence in the latest crawl. Follow the recipe to get referring domains.
2. Deduplicate to **referring domains**, not raw URL counts — report links
   and RD separately; RD is the metric that correlates with rankings.
3. Classify each referring domain: editorial / directory / UGC / footer /
   suspicious. Flag exact-match-anchor clusters.
4. Deliver a table: referring domain, first-seen crawl, sample source URL,
   target URL, anchor (when available), link type guess (dofollow/nofollow
   cannot be confirmed from crawl data alone — say so).

## Workflow 2 — Your own site, ground truth (GSC)

1. Ask for the user's GSC "Links" export (Top linking sites / Top linked
   pages CSVs). If they don't have it, walk them through exporting it.
2. Cross-reference against Workflow 1 output: links GSC shows that crawl
   missed (fresh links), and crawl links GSC doesn't count (likely ignored
   or nofollowed by Google).
3. Identify top linked pages — these are the site's proven link magnets;
   recommend making more content in that pattern.

## Workflow 3 — Competitor link gap

1. Run Workflow 1 for the user and 2–3 competitors.
2. Intersect referring domains: sites linking to ≥2 competitors but NOT the
   user are the highest-probability outreach targets.
3. Rank targets by relevance to the user's niche, then draft a one-line
   outreach angle per target (resource page, guest post, broken link,
   unlinked mention). Never mass-generate outreach spam.

## Workflow 4 — Link-earning asset plan

When the user's real goal is "get dofollow links pointing at my site":

1. Audit which of their pages already earn links (Workflow 2).
2. Recommend asset types with proven link-earning rates in their niche:
   free tools/calculators, original data studies, open-source utilities,
   embeddable charts, definitive guides.
3. For each asset, specify the distribution surface where editorial links
   actually come from (GitHub, Product Hunt, niche newsletters, Reddit,
   Hacker News) — see `references/link-earning-playbook.md`.

## Hard rules

- Never promise dofollow links. Whether a link passes equity is the linking
  site's choice; report what is observable and mark rel/sponsored/ugc
  attributes when known.
- Never suggest buying links, PBNs, comment spam, or injecting attribution
  links into content published on other people's sites without disclosure —
  these violate Google spam policy and put the USER's domain at risk.
- State data freshness: Common Crawl snapshots lag weeks to months; say
  which crawl was used.
- Free sources give samples, not a complete index. Say "observed links",
  not "all links".
