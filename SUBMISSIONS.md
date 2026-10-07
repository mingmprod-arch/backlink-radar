# Directory submissions — copy-paste playbook

One-time setup per directory. Each listing is a dofollow link + a new
install channel. Do them in this order (highest traffic first).

## 1. Official MCP Registry (registry.modelcontextprotocol.io)

The `server.json` in this repo root is already registry-ready.

```bash
npm i -g @modelcontextprotocol/mcp-publisher   # or: brew install mcp-publisher
mcp-publisher login github                      # one-time, browser OAuth
mcp-publisher publish                           # run from this repo root
```

Verify: `curl "https://registry.modelcontextprotocol.io/v0/servers?search=backlink-hub"`

## 2. Smithery (smithery.ai)

`smithery.yaml` is in this repo root (remote type, no build).

1. https://smithery.ai/new → connect GitHub → pick `mingmprod-arch/backlink-radar`
2. It reads `smithery.yaml` + `server.json` automatically
3. Confirm the config schema asks for `hubApiKey`, then Publish

## 3. mcpservers.org / Glama / pulse-mcp

Web forms, 2 minutes each:

- https://mcpservers.org/submit — name "Backlink Hub", category "Marketing / SEO",
  remote URL `https://hub.recmoment.net/mcp`, link the GitHub repo
- https://glama.ai/mcp/servers — "Add server", point at the repo
- https://www.pulsemcp.com/servers — submit form

## 4. Awesome lists (GitHub PR)

Target: `punkpeye/awesome-mcp-servers` (biggest). Fork → add one line under
"Marketing" (create the section if missing) → PR.

**Line to add:**

```markdown
- [backlink-hub](https://github.com/mingmprod-arch/backlink-radar) 🎖️ 📇 ☁️ - Vetted backlink publisher directory with auto-matching, pitch drafting, and verified placement outcomes. Free API key; publisher-approved placements only, no link selling.
```

**PR title:** `Add backlink-hub (backlink publisher directory + auto-matching)`

**PR body:**

```markdown
## What
Adds [Backlink Hub](https://github.com/mingmprod-arch/backlink-radar): a remote
MCP server giving agents a vetted directory of sites that accept guest posts /
niche edits, with auto-matching (`hub_auto_match`), pitch drafting grounded in
each publisher's real topic terms, and server-side verification of published
placements (fetches the live page, checks the link and its `rel`).

## Why it belongs
- Actively maintained, hosted streamable-http endpoint (`https://hub.recmoment.net/mcp`)
- Published on the official MCP registry (`net.recmoment/backlink-hub`)
- Free API key tier; compliance-first (rel=sponsored for paid placements, no link schemes)

## Checklist
- [x] Server is reachable and responds to `initialize` + `tools/list`
- [x] Entry follows the existing format and legend
```

## 5. Skill directories (for SKILL.md)

- `anthropics/skills` PR is invite-heavy — instead list on community indexes:
  `ComposioHQ/awesome-claude-skills`, `travisvn/awesome-claude-skills` — same PR
  pattern as above, one line pointing at `SKILL.md` in this repo.
