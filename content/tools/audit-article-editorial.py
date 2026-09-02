#!/usr/bin/env python3
import json
import re
import sys
from collections import defaultdict
from pathlib import Path

root = Path(sys.argv[1] if len(sys.argv) > 1 else 'content/articles')
files = sorted(root.glob('es/*.json')) + sorted(root.glob('en/*.json'))
errors = []
seen = set()
numbers_by_lang = defaultdict(set)
groups_by_number = defaultdict(dict)

for path in files:
    try:
        data = json.loads(path.read_text(encoding='utf-8'))
    except Exception as exc:
        errors.append(f'{path}: invalid JSON: {exc}')
        continue

    lang = data.get('language')
    num = data.get('article_number')
    if lang not in ('es', 'en'):
        errors.append(f'{path}: invalid language {lang!r}; expected es or en')
        continue
    if not isinstance(num, int) or num < 1:
        errors.append(f'{path}: invalid article_number {num!r}; expected positive integer')
        continue

    key = (lang, num)
    if key in seen:
        errors.append(f'{path}: duplicate article pair {key}')
    seen.add(key)
    numbers_by_lang[lang].add(num)
    groups_by_number[num][lang] = data.get('translation_group')

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

es_numbers = numbers_by_lang['es']
en_numbers = numbers_by_lang['en']
all_numbers = es_numbers | en_numbers

if not all_numbers:
    errors.append('no article JSON files found')
else:
    max_number = max(all_numbers)
    expected_numbers = set(range(1, max_number + 1))
    for lang, numbers in (('es', es_numbers), ('en', en_numbers)):
        missing = sorted(expected_numbers - numbers)
        extra = sorted(numbers - expected_numbers)
        if missing:
            errors.append(f'{lang}: missing article numbers: {missing}')
        if extra:
            errors.append(f'{lang}: unexpected article numbers: {extra}')

    if es_numbers != en_numbers:
        errors.append(
            f'bilingual article numbers do not match; '
            f'ES-only={sorted(es_numbers - en_numbers)}, EN-only={sorted(en_numbers - es_numbers)}'
        )

    for num in sorted(es_numbers & en_numbers):
        es_group = groups_by_number[num].get('es')
        en_group = groups_by_number[num].get('en')
        if not es_group or not en_group:
            errors.append(f'article {num}: missing translation_group in one or both languages')
        elif es_group != en_group:
            errors.append(
                f'article {num}: translation_group mismatch: es={es_group!r}, en={en_group!r}'
            )

if errors:
    print('EDITORIAL_AUDIT=FAIL')
    for err in errors:
        print(err)
    raise SystemExit(1)

print('EDITORIAL_AUDIT=PASS')
print(f'ARTICLES={len(files)}')
print(f'SPANISH={len(es_numbers)}')
print(f'ENGLISH={len(en_numbers)}')
print(f'LATEST_ARTICLE_NUMBER={max(all_numbers)}')
print('MANUAL_COLD_READ=REQUIRED')
print('NARRATIVE_METHOD=human-review-not-rigid-template')
