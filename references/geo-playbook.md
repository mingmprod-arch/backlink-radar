# GEO Playbook — what actually gets you cited by AI answer engines

Distilled from Google's own AI-features guidance, the Princeton GEO study,
and 2025–2026 practitioner research (HubSpot, Search Engine Journal,
Directive, Exploding Topics). Everything here is already encoded as checks
in `scripts/site_audit.py` — this file is the "why".

## The three highest-leverage levers (evidence-backed)

1. **Brand mentions across the web.** The top-3 correlates of appearing in
   Google AI Overviews are: brand web mentions, brand anchors, brand search
   volume. This is why backlinks and digital PR are GEO levers, not just
   SEO levers — and why this skill treats link earning as the foundation.
2. **Quotable, self-contained passages.** AI systems do chunk-level
   retrieval: a 40–60 word passage that answers a question on its own gets
   extracted. Lead sections with the answer, then elaborate. Add "Key
   takeaway" boxes. One idea per paragraph. Avoid opening paragraphs with
   "It / This / The software" — ambiguous subjects cause mis-citations.
3. **Citations and original data.** The Princeton study found content with
   statistics, quotations and authoritative citations gains 30–40% more AI
   visibility. Cite primary sources with publication years; publish at
   least one original dataset/benchmark per quarter.

## Content structure

- Question-form H2s ("How do I…?") and proper heading hierarchy
- FAQ sections with FAQPage schema — among the most-cited elements
- Bullet lists and comparison tables over dense prose
- Conversational, low-fluff language; define entities with
  subject-predicate-object statements
- Schema: Article, FAQPage, HowTo, Organization, Person (author)

## Freshness

AI citations skew hard toward content updated in the last 2–3 months.
Show a visible "Last updated" date, emit dateModified schema, and refresh
top pages every 3–6 months with new stats/examples.

## E-E-A-T

Named authors with credentials and bios, reviewer credits for YMYL
topics, about/contact pages, original research. AI engines weight
attributable, credible sources.

## Platform coverage

- **Google AIO**: grounded in Search index — normal SEO health applies
- **ChatGPT search**: powered by Bing's index — submit to Bing Webmaster
  Tools or you're invisible there
- **Perplexity/Claude**: transparent citations; robots.txt must not block
  their crawlers (PerplexityBot, ClaudeBot); ship /llms.txt

## What does NOT work

- Keyword-stuffed thin content (December 2025 core update penalized it)
- Mass AI-generated content without experience/insight
- Blocking AI crawlers "just in case" while wanting AI visibility
- Chasing prompt results daily — prompts drift faster than SERPs; track
  citation share weekly, not rankings daily

## Weekly operating rhythm (from practitioner playbooks)

- Query 10–20 priority questions across ChatGPT / Perplexity / Gemini;
  note whether you're cited
- Refresh one aging page (new stat, new FAQ, updated date)
- Check GSC for crawl/index issues on updated pages
