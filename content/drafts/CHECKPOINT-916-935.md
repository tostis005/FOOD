# Checkpoint — articles 916–935

Branch: `new-content-916-935`

This checkpoint preserves the drafting/research state for the first block from `TOPICS-916-1115.md`. Do **not** merge this branch to `main` yet. The final deliverable remains 40 article JSON files: 20 ES + 20 EN.

## Editorial standard already fixed

- Minimum body target: 631 words per language; normal target ~700–1,000 when the intent supports it.
- Minimum reading target: 4 minutes at the repository audit speed (210 wpm).
- Prefer 4 substantial H2s for this block; avoid fragmented/catalogue structure.
- Answer the primary search intent early.
- ES and EN must be independently localized, not literal translations.
- Valid JSON and schema consistent with current `content/articles` files.
- `article_number`, `language`, `locale`, `status=publish`, slug and id must be correct.
- Matching, non-empty `translation_group` across ES/EN.
- Do not repeat the excerpt verbatim in `content_html`.
- Avoid forbidden editorial/process language from `content/tools/audit-article-editorial.py`, including `deliberadamente` / `deliberately`.
- No long duplicated boilerplate across articles.
- Manual cold read remains required even after automated audit passes.

## Session state

The 916–935 block was researched and drafted conceptually in both languages during the previous session. The session-level body-length check was reported in the range ~632–789 words. However, the body drafts were **not materialized as Git files before handoff**. Treat the article architecture/research below as the persisted source of truth and reconstruct the final JSONs from it; do not redo topic discovery or redefine the angles.

## Core sources already selected

Use authoritative/primary sources wherever possible and verify the exact current wording before final publication:

- Japan Meat Grading Association (JMGA): Japanese carcass yield/quality grades, A1–A5 system, BMS scale and marbling grading.
- Japan External Trade Organization (JETRO) / MAFF: Japanese Wagyu definitions, export context, traceability/authenticity information.
- Kobe Beef Marketing & Distribution Promotion Association: what legally/officially qualifies as Kobe beef and the Tajima/Hyogo criteria.
- USDA AMS: U.S. beef quality grading, especially USDA Prime, and any relevant certified beef program documentation.
- Australian Wagyu Association (AWA): Fullblood, Purebred and Crossbred terminology/registry definitions; verify exact percentages against current AWA wording before finalizing article 929.
- For U.S. “American Wagyu”, avoid implying that one universal federal percentage definition exists unless a primary source explicitly supports that claim; explain producer/program genetics and labeling carefully.

## Canonical topics and locked editorial angles

### 916 — Qué es la carne Wagyu y por qué es diferente de la ternera normal
Primary angle: Wagyu is a cattle/genetic category, not a synonym for any highly marbled steak. Explain Japanese context, intramuscular fat, texture, production/traceability, and why ordinary beef can also be highly marbled without being Wagyu.
Suggested H2s: what Wagyu means; marbling/fat distribution; why eating experience differs; what to verify when buying.

### 917 — Wagyu vs. Kobe: cuál es la diferencia y por qué no son lo mismo
Primary angle: all Kobe beef is Wagyu, but not all Wagyu is Kobe. Kobe is a much narrower protected/association-defined origin and eligibility category tied to Tajima cattle and Hyogo criteria. Avoid using “Kobe-style” as if equivalent.
Suggested H2s: relationship between terms; Kobe eligibility/origin; why restaurant labels confuse people; how to verify.

### 918 — Qué significa A5 en el Wagyu y por qué es la clasificación más conocida
Primary angle: A5 is a Japanese carcass grade combining yield grade A with meat-quality grade 5; it is not a breed and not a universal global Wagyu grade. Explain that quality grade itself includes several attributes, not marbling alone.
Suggested H2s: decoding A and 5; what quality grade measures; why A5 became famous; what A5 does and does not guarantee about personal preference.

### 919 — Qué significa el BMS del Wagyu y cómo se mide el marmoleo
Primary angle: BMS is the Beef Marbling Standard used within Japanese grading, with a numbered scale. Explain visual assessment, relationship to quality grade, and why BMS is not directly interchangeable with USDA marbling terms or Australian scores without context.
Suggested H2s: what BMS measures; scale/visual assessment; relationship with A5; why cross-country comparisons are tricky.

### 920 — Por qué el Wagyu es tan caro: qué estás pagando realmente
Primary angle: price comes from genetics, feeding/time, limited supply, grading, traceability, import/distribution, yield and brand/origin—not simply “because it has more fat”. Separate true Japanese premium product from domestic crossbred Wagyu pricing.
Suggested H2s: production economics; grading/scarcity; import/traceability costs; when a high price is justified and what to verify.

### 921 — Por qué el Wagyu tiene tanto marmoleo y de dónde sale esa grasa
Primary angle: intramuscular adipose development is driven by genetics plus feeding/management and age; it is not injected fat. Distinguish intramuscular fat from external fat and explain why visual marbling varies by animal and grade.
Suggested H2s: where marbling physically sits; genetics; feeding/time; why not every Wagyu looks like A5.

### 922 — A qué sabe el Wagyu y por qué su textura es tan diferente
Primary angle: explain richness, tenderness, fat melt and small-serving sensory intensity without cliché. Different Wagyu grades/origins taste different. Avoid promising that every Wagyu tastes “buttery”.
Suggested H2s: flavor; texture/fat melt; why small bites feel rich; how origin/grade/cut alter the experience.

### 923 — ¿Merece la pena el Wagyu? Cuándo se nota realmente la diferencia
Primary angle: value is use-case dependent. A5 is most distinctive when served simply and in small portions; it may be poor value in burgers, heavy sauces or contexts that hide texture. Compare “experience purchase” vs everyday steak value.
Suggested H2s: when difference is obvious; when premium is wasted; who may prefer conventional steak; buying strategy for first try.

### 924 — Wagyu japonés vs. Wagyu americano: cuál es realmente la diferencia
Primary angle: do not frame American Wagyu as fake. Compare genetics, crossbreeding, grading systems, feeding, portion style, flavor, texture and price. Explain that “American Wagyu” often refers to U.S.-raised cattle with Wagyu genetics, frequently crossbred, and the exact genetics depend on the producer/program.
Suggested H2s: genetics/origin; grading; eating style; which suits which buyer.

### 925 — Wagyu japonés vs. Wagyu australiano: qué cambia entre ambos
Primary angle: both can be legitimate Wagyu. Compare registry/genetics, production environment, grading language, marbling ranges and export market. Do not equate Australian Wagyu with Japanese A5.
Suggested H2s: genetics/registry; grading differences; flavor/texture spectrum; value/availability.

### 926 — Wagyu vs. Angus: diferencias de sabor, marmoleo, grasa y precio
Primary angle: breed/category comparison without claiming Angus is “normal” or inferior. Wagyu tends toward greater marbling potential; Angus can deliver stronger conventional beef character and better value. Genetics and grade matter more than a breed name alone.
Suggested H2s: breed/category; marbling/fat; taste/texture; price/use case.

### 927 — Wagyu vs. USDA Prime: cuál es la diferencia y por qué no se clasifican igual
Primary angle: compare unlike systems. Wagyu is genetics/breed terminology; USDA Prime is a U.S. carcass quality grade available across breeds. A Wagyu animal in the U.S. can also receive USDA grade if eligible. Avoid direct “A5 = Prime++” equivalence.
Suggested H2s: what Wagyu describes; what Prime describes; marbling system differences; buying comparison.

### 928 — Qué es el American Wagyu y cuánto Wagyu tiene realmente
Primary angle: American Wagyu is a market category, not one fixed percentage unless a specific registry/program defines it. Explain Fullblood/Purebred/Crossbred as separate precise concepts and tell readers to ask for genetics/registry claims instead of assuming from the label.
Suggested H2s: what the label usually means; why percentages vary; how to read producer claims; what matters beyond genetics.

### 929 — Fullblood, Purebred y Crossbred Wagyu: qué significan al comprar carne
Primary angle: definitions must come from current registry/association rules. Explain these are genetic pedigree categories, not meat-quality grades. A lower-percentage cross can still be excellent beef; Fullblood does not automatically mean A5.
Suggested H2s: Fullblood; Purebred; Crossbred; why genetics and eating quality are different questions.

### 930 — ¿El Wagyu tiene que ser japonés? Por qué también existe Wagyu de otros países
Primary angle: Wagyu originated in Japan, but Wagyu genetics exist in other countries. “Japanese Wagyu” is origin-specific; Australian/American Wagyu can be legitimate depending on genetics and labeling. Kobe remains a separate narrow category.
Suggested H2s: Japanese origin; global genetics; naming/labeling; how to avoid confusing origin with authenticity.

### 931 — Qué significa Wagyu y cómo se pronuncia correctamente
Primary angle: explain Japanese word components (wa + gyu, Japanese + cattle/beef context) carefully, pronunciation approximation for Spanish and English readers, and why the word alone does not mean “A5” or “Kobe”. Verify transliteration details before final publication.
Suggested H2s: meaning; pronunciation; what the term covers; common misuse.

### 932 — Cómo saber si un Wagyu japonés es auténtico antes de comprarlo
Primary angle: practical buyer verification: country/origin claim, producer/exporter documentation, individual identification/traceability where available, grade certificate, official marks/programs, and skepticism toward vague “Kobe/Wagyu-style” marketing.
Suggested H2s: documents/traceability; grade/origin details; retailer questions; red flags.

### 933 — Certificados y trazabilidad del Wagyu japonés: cómo comprobar lo que estás comprando
Primary angle: deeper than 932. Explain carcass/individual identification, certificate fields, grade information and how official Japanese traceability/export systems work. Keep 932 as “buyer checklist”; make 933 the documentary/traceability explainer to avoid cannibalization.
Suggested H2s: what a certificate contains; individual ID/traceability; grade/origin marks; how to verify documentation.

### 934 — Cuánto cuesta el Wagyu y por qué el precio cambia tanto
Primary angle: do not hard-code volatile retail prices as evergreen facts. Explain price drivers and, if examples are used, date/market them explicitly. Distinguish Japanese A5, named regional brands, U.S./Australian Wagyu, cut, grade, portion and import channel.
Suggested H2s: why there is no single price; biggest price drivers; retail vs restaurant; how to compare value per serving rather than per kilogram alone.

### 935 — Por qué las raciones de Wagyu suelen ser mucho más pequeñas que un steak normal
Primary angle: high marbling means much greater sensory richness and energy density; Japanese service styles often emphasize smaller portions/bites. Explain that this is not merely restaurant stinginess. Keep exact “how much to buy” quantities for article 936 to prevent cannibalization.
Suggested H2s: richness/satiety; serving tradition; why a conventional steak-size A5 portion can be overwhelming; how to think about menu portions without giving article 936’s quantity answer.

## Cannibalization boundaries for this cluster

- 916 = definition/overview; do not turn it into the full comparison hub.
- 917 = Wagyu vs Kobe only.
- 918 = A5 system; 919 = BMS specifically.
- 920 = why expensive; 934 = how prices vary / shopping-price framework.
- 921 = biology of marbling; 922 = sensory taste/texture.
- 923 = value judgment / is it worth it.
- 924/925/926/927 = distinct comparisons; do not merge their intents.
- 928 = American Wagyu label; 929 = genetic registry categories.
- 932 = practical authenticity checklist; 933 = certificate/traceability deep dive.
- 935 = why servings are small; leave actual grams/person and purchase planning to 936.

## Next actions in the new chat

1. Read `AGENTS.md`, `content/articles/EDITORIAL-STANDARD.md`, `content/topics/TOPICS-916-1115.md`, this checkpoint and `content/tools/audit-article-editorial.py`.
2. Confirm branch `new-content-916-935` and do **not** modify `main`.
3. Reconstruct/materialize the 40 JSONs for 916–935 from the locked angles above, preserving native ES/EN writing.
4. Run the repository editorial audit and a duplicate-paragraph check over the full 40-file block.
5. Cold-read the full Wagyu cluster for factual consistency and intent cannibalization.
6. Commit the 40 JSONs atomically on `new-content-916-935` (or keep a single final content commit after any branch-only checkpoints).
7. Do not merge to `main` until the user explicitly asks for publication/integration.
