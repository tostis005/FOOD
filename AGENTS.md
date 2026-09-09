# Quinnoa repository instructions

These instructions apply to any work that creates, rewrites, reviews, audits or imports Quinnoa editorial articles.

## Mandatory editorial source

Before writing or modifying any article, **read and apply `content/articles/EDITORIAL-STANDARD.md` in full**.

That file is the canonical editorial standard for Quinnoa. Do not rely on memory, a previous chat, an older article or a generic writing template instead of reading it. If another instruction conflicts with it, follow the most recent explicit user instruction first and otherwise treat `content/articles/EDITORIAL-STANDARD.md` as authoritative for article writing.

## Mandatory topic inventory

Before creating any new numbered article, **read `content/topics/README.md` and the relevant `TOPICS-*.md` file from `main`**.

Canonical GitHub folder:
`https://github.com/tostis005/FOOD/tree/main/content/topics`

The files in this folder together contain the approved article-topic sequence and are the source of truth for article numbering and topic order. The inventory is deliberately split into numbered ranges so new batches can be added without mixing topic planning with the Spanish and English article content.

Mandatory rules:

- create articles strictly in the numeric order defined in the topic inventory;
- never infer, invent, replace, reorder, merge or skip a topic;
- Spanish and English versions must use the same `article_number` and `translation_group`, while each language is written natively for its market;
- before drafting article N, locate article N explicitly in the relevant file under `content/topics/` and use that exact approved search intent;
- if an existing article JSON conflicts with the topic inventory, the topic inventory wins and the JSON must be corrected;
- when several chats or branches are producing different numeric ranges in parallel, each must stay inside its assigned range and consult the same topic inventory to avoid overlap or drift;
- before proposing or approving a new topic, search **all `TOPICS-*.md` files in `content/topics/`**, not just the newest batch, to identify duplicates, near-duplicates and likely search-intent cannibalization;
- adding or reorganizing topic-list files must not move or reorganize the existing Spanish or English article body files.

For example, article 319 is **“Jamón serrano vs. jamón ibérico: diferencias nutricionales y de elaboración”**. It must not be replaced by a newly invented topic.

## Objective publishable-quality gate

Every new or substantially rewritten article must pass this gate before it is considered ready. It applies independently to the Spanish and English JSON versions.

### 1. Minimum useful depth

- The default minimum is **631 words of reader-facing article body text per language**, excluding HTML tags and metadata. Quinnoa displays reading time using approximately 210 words per minute, so this is the minimum that normally produces a visible **4 min read**.
- This is a floor, not a target. A normal article should use as much space as the search intent genuinely needs; roughly 700–1,000 words will often be more natural for topics that require explanation, comparison or practical guidance.
- Do **not** pad an article with repetition, generic background, unnecessary lists or invented detail merely to reach the threshold.
- An article below 631 words is **not automatically publishable**. A shorter article is allowed only when the search intent is genuinely narrow and further expansion would reduce usefulness. Such a case requires an explicit human/editorial exception; the automated audit should still flag it for review rather than silently pass it.

### 2. Section depth and anti-catalogue rule

Headings must organize a coherent explanation, not manufacture length.

- Avoid a stack of H2 sections containing only one short paragraph each.
- When an article contains **5 or more H2 sections**, it must average at least **80 reader-facing words per H2** across the body. Falling below this threshold is an objective catalogue/thin-section warning and means the article is not ready without editorial review.
- Prefer fewer, better-developed sections whenever several headings can be combined naturally.
- A table, list or FAQ does not compensate for an underdeveloped main explanation.
- The cold-read test remains mandatory: if removing the headings would make the article feel like disconnected notes, it fails even if the numeric thresholds are met.

### 3. Search-intent completeness

A passing article must also:

- answer the main query early and directly;
- develop every material claim introduced in the title, excerpt/quick answer or opening;
- include the practical distinctions, caveats, units, preparation effects or safety context that materially affect the answer;
- avoid filler sections added only for SEO or word count;
- avoid vague references to other articles, internal production language or editorial/process commentary;
- use tables only when they improve comparison or comprehension, and interpret their meaning in prose.

### 4. Bilingual integrity

An article number is considered complete only when **both ES and EN versions pass**.

- Both languages must have the correct `article_number`, `language`, `translation_group` and publish status.
- English must be written as natural editorial English for its market, not as a literal translation of Spanish.
- A defect in either language makes the article pair fail the quality gate until corrected.

### 5. Automated audit and human review

`content/tools/audit-article-editorial.py` is the machine-checkable quality floor and must be run before new article work is marked ready. Passing the script does not replace the required cold read or factual/source review.

For audit reporting, distinguish clearly between:

- **PASS**: meets the automated quality floor in both languages and passes the human editorial review;
- **REVIEW/FAIL**: misses the 631-word floor, triggers the thin-H2/catalogue rule, has structural/bilingual/process-language errors, or fails the cold read;
- **SHORT EXCEPTION**: below 631 words but explicitly approved after human review because the intent is genuinely narrow and expansion would be filler.

Never lower these thresholds merely to make an existing batch pass. Improve the article instead, or document a genuine short-form exception.

## Method, not template

Do not copy a fixed document structure from an existing article. Apply the editorial method to the specific search intent and subject. Headings, tables, examples, comparisons and section count should exist only when they help the reader understand the topic.

Every article must:

- stand completely on its own for a reader arriving from search;
- answer the real question early and develop it in a natural reading order;
- avoid references that assume another Quinnoa article has been read;
- explain every material factor introduced in the quick answer/excerpt;
- quantify measurable comparisons with like-for-like figures when reliable;
- distinguish per-100-g concentration from realistic serving size when that changes the conclusion;
- explain effects of cooking, water gain/loss, drying, curing, draining or processing when they change the numbers;
- interpret tables instead of narrating them row by row;
- be written natively for its language and target market rather than translated mechanically;
- use `article / artículo` as the normal public-content term unless the format is genuinely a guide.

## Required final review

Automated audits are regression checks, not a substitute for editorial judgment. Before considering an article finished, perform a **cold read from beginning to end** as if the reader has never seen Quinnoa before.

Check that:

1. the article still makes sense if the reader has no outside context;
2. each idea leads naturally to the next instead of feeling like a stack of independent H2 sections;
3. removing the headings mentally would still leave a coherent argument;
4. every promise made by the title and quick answer is actually developed;
5. tables or rankings are introduced exactly where they appear and their important meaning is explained afterward;
6. related articles, if linked, are optional further reading and never required to understand the current article.

After editing article JSON, run the repository editorial/comparison validations and do not treat the work as complete if they fail.

## Bilingual content

Spanish and English articles may share a topic or translation group, but each version must be written as a native article for its own market context. Adapt authorities, units, examples, terminology and food-safety guidance where appropriate.
