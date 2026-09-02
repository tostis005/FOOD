#!/usr/bin/env python3
import json
import re
import sys
from pathlib import Path

root = Path(sys.argv[1] if len(sys.argv) > 1 else 'content/articles')
files = sorted(root.glob('es/*.json')) + sorted(root.glob('en/*.json'))
errors = []
review_notes = []

expected = {(lang, n) for lang in ('es','en') for n in range(1,26)}
seen = set()

for path in files:
    try:
        data = json.loads(path.read_text(encoding='utf-8'))
    except Exception as exc:
        errors.append(f'{path}: invalid JSON: {exc}')
        continue
    lang = data.get('language')
    num = data.get('article_number')
    seen.add((lang, num))
    if data.get('status') != 'publish':
        errors.append(f'{path}: status is {data.get("status")!r}, expected publish')

    content = data.get('content_html','')
    plain = re.sub(r'<[^>]+>', ' ', content)
    plain = re.sub(r'\s+', ' ', plain).lower()

    forbidden = [
        'merece un artículo', 'merece otro artículo', 'otro artículo', 'en este artículo',
        'hemos elegido', 'hemos usado', 'hemos utilizado', 'deliberadamente',
        'se excluye deliberadamente', 'excluimos deliberadamente', 'para no manipular',
        'criterio de ordenación', 'fuentes y criterio', 'registro utilizado', 'registros concretos',
        'la tabla no pretende', 'no pretendemos', 'cocinado con calor seco',
        'tabla principal', 'ranking principal', 'tabla anterior', 'ranking anterior',
        'la tabla de arriba', 'el ranking de arriba', 'como vimos', 'como hemos visto',
        'en otra guía', 'en otra entrada', 'en otro post', 'en otra página',
        'deserves its own article', 'another article', 'in this article',
        'we chose', 'we have chosen', 'we used', 'we have used', 'deliberately',
        'to avoid manipulating', 'ordering criteria', 'sources and methodology',
        'record used', 'specific records', 'the table is not intended', 'cooked by dry heat',
        'main table', 'main ranking', 'previous table', 'previous ranking',
        'the table above', 'the ranking above', 'as we saw', 'as we have seen',
        'in another guide', 'in another article', 'in another post', 'on another page'
    ]
    for phrase in forbidden:
        if phrase in plain:
            errors.append(f'{path}: forbidden editorial/process phrase: {phrase!r}')

    cross_article_patterns = [
        r'como (?:ocurre|pasa|sucede) (?:con|en) las carnes',
        r'como (?:ocurre|pasa|sucede) (?:con|en) los pescados',
        r'igual que (?:en|con) las carnes',
        r'al igual que (?:en|con) las carnes',
        r'igual que (?:en|con) los pescados',
        r'al igual que (?:en|con) los pescados',
        r'as (?:with|in) (?:the )?meat ranking',
        r'as (?:with|in) (?:the )?fish ranking',
        r'as (?:with|in) (?:the )?meat article',
        r'as (?:with|in) (?:the )?fish article',
    ]
    for pattern in cross_article_patterns:
        if re.search(pattern, plain):
            errors.append(f'{path}: vague cross-article comparison: {pattern!r}')

    excerpt = re.sub(r'\s+', ' ', str(data.get('excerpt',''))).strip()
    if excerpt and excerpt.lower() in plain:
        errors.append(f'{path}: excerpt/quick answer is repeated verbatim in content_html')

    # This is intentionally a review signal, not a failing template rule. A long
    # run of tiny H2 sections can indicate the catalogue effect, but some topics
    # genuinely need short decision sections. Human cold-reading remains required.
    sections = re.split(r'(?=<h2>)', content)
    short_run = 0
    longest_short_run = 0
    for section in sections:
        if not section.startswith('<h2>'):
            continue
        body = re.sub(r'^<h2>.*?</h2>', '', section, count=1, flags=re.S)
        paragraphs = len(re.findall(r'<p(?:\s[^>]*)?>', body))
        has_structured = bool(re.search(r'<(?:table|ul|ol)(?:\s[^>]*)?>', body))
        if paragraphs <= 2 and not has_structured:
            short_run += 1
            longest_short_run = max(longest_short_run, short_run)
        else:
            short_run = 0
    if longest_short_run >= 4:
        review_notes.append(
            f'{path}: narrative review suggested: {longest_short_run} consecutive short H2 sections may read like a catalogue'
        )

missing = sorted(expected - seen)
extra = sorted(seen - expected)
if missing:
    errors.append(f'missing article pairs: {missing}')
if extra:
    errors.append(f'unexpected article pairs: {extra}')
if len(files) != 50:
    errors.append(f'expected 50 article JSON files, found {len(files)}')

if errors:
    print('EDITORIAL_AUDIT=FAIL')
    for err in errors:
        print(err)
    raise SystemExit(1)

print('EDITORIAL_AUDIT=PASS')
print('ARTICLES=50')
print('SPANISH=25')
print('ENGLISH=25')
print('MANUAL_COLD_READ=REQUIRED')
if review_notes:
    print(f'NARRATIVE_REVIEW_NOTES={len(review_notes)}')
    for note in review_notes:
        print(note)
else:
    print('NARRATIVE_REVIEW_NOTES=0')
