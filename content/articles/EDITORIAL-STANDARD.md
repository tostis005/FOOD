# Pometum editorial standard

These rules apply to every article in every language.

## The voice

Pometum should sound like a knowledgeable food editor explaining something useful to another person, not like a database, a textbook summary or a generated checklist.

The tone is clear, warm, curious and concrete. It can notice what a reader sees in a real kitchen — water pooling in a pan, a potato turning green, a container that has been in the fridge for three days — but it must never invent personal anecdotes, fake experience or emotional filler.

Human does not mean chatty. Every paragraph still needs a job.

## Start from the reader's real question

Whenever the topic allows it, open with the situation, apparent contradiction or practical doubt that made the reader search in the first place. Do not default to dictionary-style openings such as “X is a food that…” or “X contains…”.

Examples of useful entry points:

- The meat is in a hot pan, yet instead of browning it is sitting in a puddle.
- A potato has two small sprouts: is the whole potato now unusable?
- Parmesan wins a protein ranking per 100 g, but nobody eats it in the same quantity as chicken.

Give the core answer early, then earn the rest of the article by explaining why it is true.

## Reader-first prose

Articles must read like finished food journalism written by a knowledgeable editor, not like notes from a database or explanations of the writing process.

Never expose editorial process in the article body. Avoid phrases such as “we chose”, “we rounded”, “deliberately”, “this ranking uses”, “the table is not intended to”, “another article”, “deserves its own article”, and equivalent Spanish wording.

Explain the food, not the decision to write about it in a certain way.

## Build a thread, not a stack of sections

An article should feel as if one idea leads naturally to the next. Headings are navigation, not a substitute for prose.

Avoid a mechanical rhythm in which every H2 is followed by two short paragraphs that independently define one fact. Vary paragraph length when the idea requires it, use natural transitions, and let related ideas develop together.

A conclusion should leave the reader with the decision or principle that matters, not merely repeat every heading in miniature.

## Tables should answer quickly; prose should interpret

Tables are useful for scanning. Do not spend the following paragraphs reading the table back row by row.

Use the prose to explain what changes the ranking, which differences are meaningful in practice, what serving size does to the result, and what the table cannot tell the reader.

When two foods differ by only a trivial amount, say so. Do not manufacture a winner from a difference that is unlikely to matter in normal eating.

## Quantify measurable comparisons

When saying that one food has more or less protein, fiber, fat, calories, sodium, iron, storage time or another measurable property, give both values whenever a reliable like-for-like comparison exists.

Example: avocado provides about 160 kcal/100 g, compared with about 52 kcal for apple and 47 kcal for orange.

Do not force numbers into genuinely qualitative statements where they would create false precision.

## Compare like with like

Use ordinary preparation labels a reader understands: raw, cooked, grilled/pan-cooked when relevant, canned, drained, dry. Do not expose database terminology such as “cooked by dry heat”.

Do not mix dry legumes with cooked legumes in a ranking without explicitly separating them. Do not mix canned fish into a cooked-fish ranking when oil, salt or draining changes the comparison substantially.

## Translate concentration into real portions

A per-100-g number is often useful for comparison but may be misleading as a description of a normal meal. When serving size changes the practical conclusion, show the serving-level number as well.

This is especially important for cheese, seeds, nuts, dry foods, oils and other concentrated foods. Make clear whether a food is likely to be the main protein/fiber source in a meal or something that mostly adds to the total.

## Explain why numbers change

When water absorption, water loss, drying, curing, added fat or edible bone changes a value, explain the mechanism in plain language.

Do not imply that cooking has “created” protein when water loss simply increases protein per 100 g, or that cooking has destroyed most of a legume's protein when absorbed water lowers the concentration.

## Food-safety writing

Be human in the explanation and firm in the recommendation. A relatable opening must never soften a safety rule.

Distinguish clearly between spoilage signs, freshness clues and microbiological safety. Smell and appearance can tell a reader when food is obviously bad; they often cannot certify that apparently normal food is safe.

Use the guidance appropriate to the target market and explain material differences between authorities when they genuinely differ.

## Every article stands alone

A reader who reaches the end should understand the question promised by the title. Related topics may be linked later, but the article must not defer essential explanation to future content.

## Quick answer / excerpt

The JSON `excerpt` is the canonical quick answer shown by the article template in the highlighted answer box.

Do not repeat the excerpt verbatim at the top of `content_html`, and do not create a separate “Quick answer” / “Respuesta rápida” section inside `content_html`. The opening paragraph can naturally develop the same idea, but it should add context rather than duplicate the box.

## Sources

Use authoritative sources when they materially support safety, nutrient reference values or contested facts. Spanish content should normally favor Spain/EU authorities such as AESAN and EFSA; English content should normally favor U.S. authorities such as USDA, FDA and CDC when the market context is U.S.

The JSON `sources` array is the single canonical source list. **Do not include a `<h2>Sources</h2>` or `<h2>Fuentes</h2>` section inside `content_html`.** The importer renders the structured `sources` array at the end of the article. This prevents duplicate source sections and keeps source metadata reusable.

## Localization

Spanish and English versions share a translation group but are independently written. Food-safety rules, units, examples and authorities should match the target market.

Do not translate idioms or sentence structures mechanically. The English article should sound written in English; the Spanish article should sound written in Spanish.

## Structure

Tables, FAQs and headings are tools, not a mandatory template. Use as many sections as the subject needs and no more.

Before considering an article finished, check that it has a readable narrative thread, no repeated quick answer, no inline Sources/Fuentes section, no table narration, and no comparison such as “more”, “less”, “higher” or “lower” that would be clearer with actual numbers.
