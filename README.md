# backlink-radar

[![License: MIT](https://img.shields.io/badge/license-MIT-black.svg)](LICENSE)
[![MCP server](https://img.shields.io/badge/MCP-hub.recmoment.net%2Fmcp-blue.svg)](https://hub.recmoment.net/mcp)
[![Backlink Hub](https://img.shields.io/badge/backlink--hub-join%20free-3fb950.svg)](https://recmoment.net/hub/)
[![Website](https://img.shields.io/badge/by-Rec%20Moment-5e697f.svg)](https://recmoment.net)

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
- 🩺 **Free on-page SEO audit** — title/meta/canonical/headings/schema/OG,
  every fail ships with a one-line fix
- 🤖 **GEO readiness check** — is your site readable and citable by AI
  answer engines? robots.txt AI-bot stance, llms.txt, SSR content,
  FAQ schema
- 📊 **Domain-authority lookup** — Open PageRank + Common Crawl size
  signals, with honest pointers to Ahrefs' free DR checker

## Install

Copy the `backlink-radar/` folder into your agent's skills directory:

```bash
# Claude Code
cp -r backlink-radar ~/.claude/skills/

# Kimi Work / other agents: place under the agent's skills folder
```

Or paste `SKILL.md` into any LLM's system/project prompt.

## Connect your agent to the Backlink Hub (MCP)

The Hub's hosted MCP server plugs straight into your agent — one config
block, no local install:

```json
{
  "mcpServers": {
    "backlink-hub": {
      "type": "http",
      "url": "https://hub.recmoment.net/mcp",
      "headers": { "Authorization": "Bearer rmh_YOUR_KEY_HERE" }
    }
  }
}
```

Get a **free API key** at [recmoment.net/hub-portal](https://recmoment.net/hub-portal/)
(email → key, 30 seconds), then drop the config into your client:

| Client | Where the config goes |
|---|---|
| **Cursor** | `.cursor/mcp.json` in your project (or global Settings → MCP) |
| **Claude Code** | `claude mcp add --transport http backlink-hub https://hub.recmoment.net/mcp --header "Authorization: Bearer rmh_YOUR_KEY_HERE"` |
| **Claude Desktop** | Settings → Connectors → Add custom connector → paste the URL + key |
| **Windsurf / others** | Any MCP client that speaks streamable HTTP — same `mcp.json` shape as the [mcp.json](mcp.json) in this repo |

Tools you get: `hub_search_sites`, `hub_submit_site`, `hub_request_match`,
`hub_analyze_content`, `hub_report_outcome`, `hub_publisher_wishlist`,
plus points/referral endpoints over REST. Free plan includes 20 directory
queries/month; points or membership unlock matching.

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
| `scripts/site_audit.py <url>` | 19-check on-page SEO + GEO (AI-engine readiness) audit, zero API keys |
| `scripts/dr_check.py <domain>` | Domain-authority signals: Open PageRank (free key) + Common Crawl presence |

## Data honesty

Free sources give **observed** links, not a complete index; Common Crawl
lags weeks–months; dofollow/nofollow can't be confirmed from crawl data
alone. The skill states these limits in its outputs. Upgrade path when you
outgrow free: DataForSEO backlinks API, pay-per-call, no subscription.

## Want the targets pre-vetted? Join the Backlink Hub

This skill is free (MIT) and stays free. It is maintained by
[Rec Moment](https://recmoment.net), which also runs the **Backlink
Hub** — a paid member directory for the step this skill deliberately
leaves to you: *actually getting the link*.

- 📇 Curated directory of sites that accept guest posts, resource-page
  listings and niche edits — contact routes included, refreshed monthly
- 📡 Link-gap monitoring: get alerted when a competitor gains a referring
  domain you don't have
- ✅ Every listed site screened: no PBNs, no link farms, `rel="sponsored"`
  required whenever money changes hands
- 🔌 **MCP server + REST API**: plug `https://hub.recmoment.net/mcp` into
  your own agent with a member API key and query the directory
  (`hub_search_sites`, `hub_submit_site`, `hub_request_match`) without
  leaving your AI workflow

The Hub sells data, matching and monitoring — **never guaranteed links**.
Details and membership: https://recmoment.net/hub/

## License

MIT — fork it, ship it, credit appreciated but not required.
