#!/usr/bin/env python3
import json
import re
import sys
from pathlib import Path

root = Path(sys.argv[1] if len(sys.argv) > 1 else 'content/articles')
files = sorted(root.glob('es/*.json')) + sorted(root.glob('en/*.json'))
errors = []

expected = {(lang, n) for lang in ('es','en') for n in range(1,51)}
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
        'deserves its own article', 'another article', 'in this article',
        'we chose', 'we have chosen', 'we used', 'we have used', 'deliberately',
        'to avoid manipulating', 'ordering criteria', 'sources and methodology',
        'record used', 'specific records', 'the table is not intended', 'cooked by dry heat'
    ]
    for phrase in forbidden:
        if phrase in plain:
            errors.append(f'{path}: forbidden editorial/process phrase: {phrase!r}')

missing = sorted(expected - seen)
extra = sorted(seen - expected)
if missing:
    errors.append(f'missing article pairs: {missing}')
if extra:
    errors.append(f'unexpected article pairs: {extra}')
if len(files) != 100:
    errors.append(f'expected 100 article JSON files, found {len(files)}')

if errors:
    print('EDITORIAL_AUDIT=FAIL')
    for err in errors:
        print(err)
    raise SystemExit(1)
print('EDITORIAL_AUDIT=PASS')
print('ARTICLES=100')
print('SPANISH=50')
print('ENGLISH=50')
