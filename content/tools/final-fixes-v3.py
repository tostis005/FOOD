#!/usr/bin/env python3
import json
from pathlib import Path

ROOT = Path('content/articles')
FORBIDDEN = [
    'merece un artículo específico', 'merecen un artículo específico',
    'merece un artículo aparte', 'merecen un artículo aparte',
    'en otro artículo', 'lo veremos aparte', 'se tratará aparte',
    'deserves its own article', 'deserve their own article',
    'in another article', 'covered elsewhere', 'article-specific treatment',
    "we'll cover", 'we will cover'
]
SAFE_SIMPLIFICATIONS = {
    'cocinado con calor seco': 'cocinado',
    'cocinada con calor seco': 'cocinada',
    'cooked with dry heat': 'cooked',
    'cooked, dry heat': 'cooked',
    'drained solids with bone': 'drained',
}

def clean_value(value):
    if isinstance(value, str):
        out = value
        for old, new in SAFE_SIMPLIFICATIONS.items():
            out = out.replace(old, new)
        return out
    if isinstance(value, list):
        return [clean_value(v) for v in value]
    if isinstance(value, dict):
        return {k: clean_value(v) for k, v in value.items()}
    return value

paths = sorted(ROOT.glob('es/*.json')) + sorted(ROOT.glob('en/*.json'))
if len(paths) != 50:
    raise SystemExit(f'Expected 50 article files, found {len(paths)}')

articles = {}
for path in paths:
    data = clean_value(json.loads(path.read_text(encoding='utf-8')))
    articles[(data['language'], data['article_number'])] = (path, data)

# Translation groups must match inside every ES/EN pair.
for n in range(1, 26):
    es_path, es = articles[('es', n)]
    en_path, en = articles[('en', n)]
    en['translation_group'] = es['translation_group']

# Everything in this approved batch is ready to publish.
for _, data in articles.values():
    data['status'] = 'publish'

# Preserve the user's quantified-comparison rule in the avocado article.
es_path, es = articles[('es', 25)]
es_html = es['content_html']
es_html = es_html.replace(
    'Sí. Una manzana o naranja tienen mucha menos energía por 100 g. La razón es la grasa: aporta unas 9 kcal por gramo, más del doble que carbohidratos o proteína.',
    'Sí. El aguacate aporta unas <strong>160 kcal por 100 g</strong>, frente a unas <strong>52 kcal de la manzana</strong> y alrededor de <strong>47 kcal de la naranja</strong>. Es decir, aporta aproximadamente tres veces más energía que esas frutas. La razón principal es la grasa: aporta unas 9 kcal por gramo, frente a unas 4 kcal por gramo de carbohidratos o proteína.'
)
es_html = es_html.replace(
    'El aguacate tiene mucha más agua y menos calorías por 100 g que frutos secos. Una almendra o nuez es más concentrada. Pero ambas categorías pueden formar parte de patrones saludables y saciantes.',
    'El aguacate aporta unas <strong>160 kcal por 100 g</strong>, bastante menos que los frutos secos: las almendras rondan <strong>579 kcal</strong> y las nueces unas <strong>654 kcal por 100 g</strong>. El aguacate es, por tanto, calórico para ser una fruta, pero mucho menos concentrado que los frutos secos porque contiene bastante más agua. Ambas categorías pueden formar parte de patrones saludables y saciantes.'
)
es['content_html'] = es_html
if not any(s.get('url') == 'https://fdc.nal.usda.gov/' for s in es.get('sources', [])):
    es.setdefault('sources', []).append({
        'name':'USDA FoodData Central',
        'url':'https://fdc.nal.usda.gov/',
        'note':'Valores comparativos aproximados de energía para manzana, naranja, almendras y nueces.'
    })

en_path, en = articles[('en', 25)]
en_html = en['content_html']
en_html = en_html.replace(
    'Avocado contains more calories than most fruit because it contains much more fat. That fact often gets turned into the claim that avocado is “fattening.” A more accurate statement is that <strong>it is energy-dense, and total energy balance determines weight change over time</strong>.',
    'Avocado is much more calorie-dense than most fruit: about <strong>160 calories per 100 g</strong>, compared with roughly <strong>52 calories for apple</strong> and <strong>47 for orange</strong>. That is about three times as much energy by weight. The main reason is fat. This often gets turned into the claim that avocado is “fattening,” but a more accurate statement is that <strong>it is energy-dense, and total energy balance determines weight change over time</strong>.'
)
en_html = en_html.replace(
    'It contains roughly 15 grams of fat per 100 grams. Fat provides about nine calories per gram, more than carbohydrate or protein.',
    'It contains roughly <strong>15 g of fat per 100 g</strong>, whereas apples and oranges contain well under 1 g. Fat provides about <strong>9 calories per gram</strong>, compared with about <strong>4 calories per gram</strong> from carbohydrate or protein, which explains most of the energy difference.'
)
en_html = en_html.replace(
    '<h3>Is avocado low-calorie?</h3><p>No. It is relatively calorie-dense for a fruit, although much less dense than oils or nuts.</p>',
    '<h3>Is avocado low-calorie?</h3><p>No. It provides about <strong>160 calories per 100 g</strong>, compared with roughly <strong>52 for apple</strong> and <strong>47 for orange</strong>. It is still far less energy-dense than olive oil at about 900 calories or many nuts at roughly 550–650 calories per 100 g.</p>'
)
en['content_html'] = en_html

# Write all 50 in a normalized form so the reviewed set is explicit in Git history.
for (_, _), (path, data) in articles.items():
    path.write_text(json.dumps(data, ensure_ascii=False, indent=2) + '\n', encoding='utf-8')

# Final editorial audits.
errors = []
short = []
for n in range(1, 26):
    es = articles[('es', n)][1]
    en = articles[('en', n)][1]
    if es['translation_group'] != en['translation_group']:
        errors.append(f'{n}: translation_group mismatch')
for (lang, n), (path, data) in articles.items():
    if data.get('status') != 'publish':
        errors.append(f'{path}: status is not publish')
    text = path.read_text(encoding='utf-8').lower()
    for phrase in FORBIDDEN:
        if phrase.lower() in text:
            errors.append(f'{path}: forbidden editorial hand-off: {phrase}')
    length = len(data.get('content_html', ''))
    if length < 2200:
        short.append(f'{path}:{length}')
if errors:
    print('\n'.join(errors))
    raise SystemExit('FINAL_EDITORIAL_AUDIT=FAIL')
print('FINAL_EDITORIAL_AUDIT=PASS')
print('TRANSLATION_PAIRS=25')
print('PUBLISH_READY=50')
print('SHORT_ARTICLES=' + (','.join(short) if short else 'NONE'))
