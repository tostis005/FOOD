#!/usr/bin/env python3
import html
import json
import math
import re
import sys
from collections import Counter, defaultdict
from pathlib import Path

MIN_WORDS = 631
WORDS_PER_MINUTE = 210
H2_TRIGGER = 5
MIN_WORDS_PER_H2 = 80.0

args = [arg for arg in sys.argv[1:] if not arg.startswith('--')]
strict_quality = '--strict-quality' in sys.argv[1:]
root = Path(args[0] if args else 'content/articles')
files = sorted(root.glob('es/*.json')) + sorted(root.glob('en/*.json'))

errors = []
seen = set()
numbers_by_lang = defaultdict(set)
groups_by_number = defaultdict(dict)
version_results = {}
reason_counts = Counter()
reading_time_counts = Counter()

FORBIDDEN = [
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

CROSS_ARTICLE_PATTERNS = [
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


def visible_text(content_html):
    text = re.sub(r'(?is)<(?:script|style)\b[^>]*>.*?</(?:script|style)>', ' ', content_html)
    text = re.sub(r'<[^>]+>', ' ', text)
    text = html.unescape(text)
    return re.sub(r'\s+', ' ', text).strip()


def word_count(text):
    # Unicode-aware words, including common apostrophe/hyphen compounds.
    return len(re.findall(r"\b[^\W_]+(?:['’\-][^\W_]+)*\b", text, flags=re.UNICODE))


def add_reason(result, code, detail):
    result['reasons'].append({'code': code, 'detail': detail})
    reason_counts[code] += 1


for path in files:
    path_lang = path.parent.name
    try:
        data = json.loads(path.read_text(encoding='utf-8'))
    except Exception as exc:
        errors.append(f'{path}: invalid JSON: {exc}')
        reason_counts['invalid_json'] += 1
        continue

    num = data.get('article_number')
    if not isinstance(num, int) or num < 1:
        errors.append(f'{path}: invalid article_number {num!r}; expected positive integer')
        reason_counts['invalid_article_number'] += 1
        continue

    result = {
        'path': str(path),
        'language': path_lang,
        'article_number': num,
        'words': 0,
        'reading_minutes': 0,
        'h2': 0,
        'words_per_h2': None,
        'reasons': [],
    }
    version_results[(path_lang, num)] = result

    lang = data.get('language')
    if lang not in ('es', 'en'):
        msg = f'{path}: invalid language {lang!r}; expected es or en'
        errors.append(msg)
        add_reason(result, 'invalid_language', msg)
    elif lang != path_lang:
        msg = f'{path}: language field is {lang!r}, expected {path_lang!r} from directory'
        errors.append(msg)
        add_reason(result, 'language_mismatch', msg)

    key = (path_lang, num)
    if key in seen:
        msg = f'{path}: duplicate article pair {key}'
        errors.append(msg)
        add_reason(result, 'duplicate_number', msg)
    seen.add(key)
    numbers_by_lang[path_lang].add(num)
    groups_by_number[num][path_lang] = data.get('translation_group')

    if data.get('status') != 'publish':
        msg = f'{path}: status is {data.get("status")!r}, expected publish'
        errors.append(msg)
        add_reason(result, 'status_not_publish', msg)

    content = str(data.get('content_html', ''))
    text = visible_text(content)
    plain = text.lower()
    words = word_count(text)
    h2_count = len(re.findall(r'<h2\b', content, flags=re.IGNORECASE))
    minutes = max(1, math.ceil(words / WORDS_PER_MINUTE)) if words else 0
    words_per_h2 = (words / h2_count) if h2_count else None

    result['words'] = words
    result['reading_minutes'] = minutes
    result['h2'] = h2_count
    result['words_per_h2'] = round(words_per_h2, 1) if words_per_h2 is not None else None
    reading_time_counts[minutes] += 1

    if words < MIN_WORDS:
        add_reason(
            result,
            'below_4_min_floor',
            f'{path}: {words} words; minimum is {MIN_WORDS} for the default 4-minute floor'
        )

    if h2_count >= H2_TRIGGER and words_per_h2 is not None and words_per_h2 < MIN_WORDS_PER_H2:
        add_reason(
            result,
            'thin_h2_catalogue',
            f'{path}: {h2_count} H2 across {words} words = {words_per_h2:.1f} words/H2; minimum is {MIN_WORDS_PER_H2:.0f}'
        )

    for phrase in FORBIDDEN:
        if phrase in plain:
            msg = f'{path}: forbidden editorial/process phrase: {phrase!r}'
            errors.append(msg)
            add_reason(result, 'forbidden_process_language', msg)

    for pattern in CROSS_ARTICLE_PATTERNS:
        if re.search(pattern, plain):
            msg = f'{path}: vague cross-article comparison: {pattern!r}'
            errors.append(msg)
            add_reason(result, 'vague_cross_article_reference', msg)

    excerpt = re.sub(r'\s+', ' ', str(data.get('excerpt', ''))).strip()
    if excerpt and excerpt.lower() in plain:
        msg = f'{path}: excerpt/quick answer is repeated verbatim in content_html'
        errors.append(msg)
        add_reason(result, 'excerpt_repeated_verbatim', msg)

es_numbers = numbers_by_lang['es']
en_numbers = numbers_by_lang['en']
all_numbers = es_numbers | en_numbers
pair_reasons = defaultdict(list)

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
            reason_counts[f'missing_{lang}_numbers'] += len(missing)
            for num in missing:
                pair_reasons[num].append(f'missing_{lang}')
        if extra:
            errors.append(f'{lang}: unexpected article numbers: {extra}')
            reason_counts[f'unexpected_{lang}_numbers'] += len(extra)

    if es_numbers != en_numbers:
        errors.append(
            f'bilingual article numbers do not match; '
            f'ES-only={sorted(es_numbers - en_numbers)}, EN-only={sorted(en_numbers - es_numbers)}'
        )

    for num in sorted(es_numbers & en_numbers):
        es_group = groups_by_number[num].get('es')
        en_group = groups_by_number[num].get('en')
        if not es_group or not en_group:
            msg = f'article {num}: missing translation_group in one or both languages'
            errors.append(msg)
            pair_reasons[num].append('missing_translation_group')
            reason_counts['missing_translation_group'] += 1
        elif es_group != en_group:
            msg = f'article {num}: translation_group mismatch: es={es_group!r}, en={en_group!r}'
            errors.append(msg)
            pair_reasons[num].append('translation_group_mismatch')
            reason_counts['translation_group_mismatch'] += 1

pair_pass = 0
pair_fail = 0
failed_pair_ids = []
for num in sorted(all_numbers):
    reasons = list(pair_reasons[num])
    for lang in ('es', 'en'):
        result = version_results.get((lang, num))
        if result is None:
            reasons.append(f'missing_{lang}')
        elif result['reasons']:
            reasons.extend(f'{lang}:{r["code"]}' for r in result['reasons'])
    if reasons:
        pair_fail += 1
        failed_pair_ids.append(num)
    else:
        pair_pass += 1

version_pass = sum(1 for r in version_results.values() if not r['reasons'])
version_fail = len(version_results) - version_pass
es_pass = sum(1 for (lang, _), r in version_results.items() if lang == 'es' and not r['reasons'])
en_pass = sum(1 for (lang, _), r in version_results.items() if lang == 'en' and not r['reasons'])
es_fail = len(es_numbers) - es_pass
en_fail = len(en_numbers) - en_pass

print('OBJECTIVE_QUALITY_AUDIT')
print(f'MIN_WORDS={MIN_WORDS}')
print(f'WORDS_PER_MINUTE={WORDS_PER_MINUTE}')
print(f'H2_CATALOGUE_TRIGGER={H2_TRIGGER}')
print(f'MIN_WORDS_PER_H2={MIN_WORDS_PER_H2:.0f}')
print(f'VERSIONS={len(version_results)}')
print(f'VERSION_PASS={version_pass}')
print(f'VERSION_FAIL={version_fail}')
print(f'SPANISH_PASS={es_pass}')
print(f'SPANISH_FAIL={es_fail}')
print(f'ENGLISH_PASS={en_pass}')
print(f'ENGLISH_FAIL={en_fail}')
print(f'ARTICLE_PAIRS={len(all_numbers)}')
print(f'PAIR_PASS={pair_pass}')
print(f'PAIR_FAIL={pair_fail}')
print(f'BELOW_4_MIN_FLOOR={reason_counts["below_4_min_floor"]}')
print(f'THIN_H2_CATALOGUE={reason_counts["thin_h2_catalogue"]}')
for minute in sorted(reading_time_counts):
    print(f'READING_MIN_{minute}={reading_time_counts[minute]}')
for code, count in sorted(reason_counts.items()):
    if code not in ('below_4_min_floor', 'thin_h2_catalogue'):
        print(f'REASON_{code.upper()}={count}')
print('FAILED_PAIR_IDS=' + ','.join(str(n) for n in failed_pair_ids))
print('MANUAL_COLD_READ=REQUIRED')
print('NARRATIVE_METHOD=human-review-not-rigid-template')

quality_failures = pair_fail > 0
if errors or (strict_quality and quality_failures):
    print('EDITORIAL_AUDIT=FAIL')
    for err in errors:
        print(err)
    if strict_quality and quality_failures:
        print('STRICT_QUALITY_GATE=FAIL')
    raise SystemExit(1)

print('EDITORIAL_AUDIT=PASS')
if quality_failures:
    print('STRICT_QUALITY_GATE=REVIEW_REQUIRED')
else:
    print('STRICT_QUALITY_GATE=PASS')
print(f'ARTICLES={len(files)}')
print(f'SPANISH={len(es_numbers)}')
print(f'ENGLISH={len(en_numbers)}')
print(f'LATEST_ARTICLE_NUMBER={max(all_numbers)}')
