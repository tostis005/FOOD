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
