#!/usr/bin/env python3
import json
import re
import sys
from pathlib import Path

root = Path(sys.argv[1] if len(sys.argv) > 1 else 'content/articles')
files = sorted(root.glob('es/*.json')) + sorted(root.glob('en/*.json'))
errors = []

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
print('NARRATIVE_METHOD=human-review-not-rigid-template')
