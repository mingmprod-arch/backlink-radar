# backlink-radar

Free-tier backlink intelligence, packaged as an AI-agent skill. Inspired by
[OpenSEO](https://github.com/every-app/open-seo)'s approach — open source,
BYOK optional, no forced subscription — but focused on one job: **finding
out who links to any website, using data sources that cost $0**.

## What it does

- 🔍 **Backlink profile for any domain** from the Common Crawl open index
  and web-graph datasets (free, no API key)
- 🏠 **Ground-truth analysis of your own links** via Google Search Console
  exports
- ⚔️ **Competitor link-gap analysis** — who links to them but not you
- 🧲 **Link-earning asset planning** — what to build so editorial dofollow
  links come to you

## Install

Copy the `backlink-radar/` folder into your agent's skills directory:

```bash
# Claude Code
cp -r backlink-radar ~/.claude/skills/

# Kimi Work / other agents: place under the agent's skills folder
```

Or paste `SKILL.md` into any LLM's system/project prompt.

## Use

Ask your agent things like:

- "Who links to stripe.com? Give me referring domains."
- "Here's my GSC links export — which pages earn my links?"
- "Do a link-gap analysis: me vs competitor-a.com vs competitor-b.com"
- "What should I build to earn backlinks in the dev-tools niche?"

## Scripts

| Script | Purpose |
|---|---|
| `scripts/commoncrawl_backlinks.py <domain>` | Free extraction recipes (webgraph / BigQuery) for referring domains; `--coverage` = live crawl-presence check |
| `scripts/gsc_links_report.py <gsc-export.csv>` | Concentration + link-magnet analysis of your GSC links export |

## Data honesty

Free sources give **observed** links, not a complete index; Common Crawl
lags weeks–months; dofollow/nofollow can't be confirmed from crawl data
alone. The skill states these limits in its outputs. Upgrade path when you
outgrow free: DataForSEO backlinks API, pay-per-call, no subscription.

## License

MIT — fork it, ship it, credit appreciated but not required.
