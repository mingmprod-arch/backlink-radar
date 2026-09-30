# Link-earning playbook — how free tools actually get dofollow backlinks

The OpenSEO case (github.com/every-app/open-seo) is the model: a free,
genuinely useful tool earned editorial coverage — blogs, newsletters,
listicles — each linking to the project site with editorial dofollow links.
No link scheme, no begging. The tool IS the link magnet.

## The flywheel

1. **Build something free and specific.** "Semrush costs $139/mo, this is
   $0" is a story journalists and bloggers repeat for you.
2. **Put it where developers live.** GitHub repo with a strong README.
   GitHub's own links are nofollow — they don't pass equity — but stars and
   forks are the social proof that triggers step 3.
3. **Launch surfaces**: Product Hunt, Hacker News (Show HN), niche
   subreddits, Threads/X dev communities, newsletters (TLDR etc.).
4. **Editorial coverage = the dofollow links.** Every blog post reviewing
   or listing your tool links to your site because it's useful to their
   readers. These are the links Google values: earned, editorial, relevant.
5. **Your own domain must host the project page** (docs, landing page,
   blog). Coverage links split between your GitHub and your domain; make
   sure the domain is the canonical project home so a good share lands there.

## What works vs what gets you penalized

| Tactic | Verdict |
|---|---|
| Free tool → people write about it → editorial links | ✅ The whole strategy |
| Directory submissions (relevant, curated ones) | ✅ Fine, modest value |
| Guest posts with a relevant author-bio link | ✅ Fine at real scale |
| Embeddable badge/widget with auto dofollow "Powered by" link | ⚠️ Google explicitly calls widget links a link scheme — use rel="nofollow"/sponsored or accept penalty risk |
| Auto-inserting your link into AI-generated content users publish | ❌ Scaled link scheme; risks YOUR domain |
| Buying links / PBNs | ❌ Manual action territory |

## Attribution done right

- MIT license + a polite "built with backlink-radar?" credit in README of
  forks — opt-in, no penalty risk.
- Optional `--credit` flag in generated reports that users can turn on.
- Ask (once, in docs) that coverage links to the project page.

## Measuring what you earn

- GSC Links report on your own domain — the ground truth, free.
- `scripts/commoncrawl_backlinks.py yourdomain.com` monthly to watch
  referring-domain growth in the open crawl.
- Watch referral traffic in analytics: every listicle that sends visitors
  is also a link that counts.
