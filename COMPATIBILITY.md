# Compatibility — skill ↔ hub server ↔ portal

This repo (`backlink-radar`) is the **public face**: the skill, the website
source, and docs. The engine lives in a separate **private** repo
(`seo-hub`): vetting, scoring, anti-abuse and matching logic stay closed on
purpose — open-sourcing them would hand over the moat.

The two repos are not merged, but they move together. This file is the
contract that keeps them in sync.

## Version map

| Skill (this repo) | Server API (`seo-hub`) | Portal page (WP #128) | Notes |
|---|---|---|---|
| 1.0.x | `v1` (`/v1/hub/*`) | any | Initial release |
| 1.1.0 | `v1` + `portal` deep-link field in every `hub_*` tool response | ≥ 2026-10-07 build (hash deep-link + role tabs) | SKILL ↔ Portal two-way loop |

Rules:

- **Server is backwards-compatible within `v1`.** New response fields (like
  `portal`) are additive; older skills must keep working. The skill treats a
  missing `portal` field as "older server" and falls back to the plain
  portal URL.
- **Breaking API change = synchronized release.** If an endpoint path,
  request shape, or existing response field changes, bump the skill minor
  version, update this table, and release both sides together (server
  deploys first, skill ships after smoke passes).
- **Portal deep links** use `#portal-<section>` hashes. Adding/renaming a
  portal section is a server-visible change: update
  `TOOL_PORTAL_SECTION` in `seo-hub/server/src/hub.js` in the same release.

## Release checklist (either side)

1. `cd seo-hub/server && node scripts/smoke.mjs` — all green.
2. Deploy server (Render auto-deploys from `main`); confirm the deploy is
   live before shipping the skill.
3. Update this table and `server.json` version in `backlink-radar`.
4. Commit + push `backlink-radar`; the skill is live immediately.

## Endpoint ownership

- MCP: `https://hub.recmoment.net/mcp` (Streamable HTTP, JSON-RPC)
- REST: `https://hub.recmoment.net/v1/hub/*`
- Public proof: `/hub/public/stats`, `/hub/public/leaderboard`,
  `/hub/public/audit`, `/hub/public/benchmarks`
- Portal: `https://recmoment.net/hub-portal/` (WordPress page #128, source
  in `website/hub-portal.php`)
