#!/usr/bin/env python3
from __future__ import annotations

import json
from pathlib import Path

ROOT = Path('content/articles')

INTENTS = {
    996: {
        'en': 'Determine when tuna can be eaten raw, which freezing and handling controls reduce parasite and food-safety risk, and how sushi tuna differs from ordinary raw tuna.',
        'es': 'Determinar cuándo puede comerse atún crudo, qué controles de congelación y manipulación reducen el riesgo de parásitos y seguridad alimentaria, y qué diferencia al atún para sushi.',
    },
    997: {
        'en': 'Understand what “sushi grade” means, whether the term is regulated, and which freezing, sourcing, and handling controls actually determine raw-fish safety.',
        'es': 'Entender qué significa “sushi grade”, si el término está regulado y qué controles de congelación, origen y manipulación determinan realmente la seguridad del pescado crudo.',
    },
    998: {
        'en': 'Assess whether common edible mushrooms can be eaten raw, which varieties or digestibility issues matter, and when cooking is the better choice.',
        'es': 'Valorar si los champiñones y setas comestibles habituales pueden comerse crudos, qué variedades o problemas de digestibilidad importan y cuándo conviene cocinarlos.',
    },
    999: {
        'en': 'Determine whether broccoli can be eaten raw and how washing, digestibility, texture, and nutrient retention differ from cooked broccoli.',
        'es': 'Determinar si el brócoli puede comerse crudo y cómo cambian el lavado, la digestibilidad, la textura y la conservación de nutrientes frente al brócoli cocinado.',
    },
    1000: {
        'en': 'Determine whether cauliflower can be eaten raw and how washing, digestibility, texture, and preparation affect how practical it is to eat.',
        'es': 'Determinar si la coliflor puede comerse cruda y cómo influyen el lavado, la digestibilidad, la textura y la preparación en su consumo.',
    },
    1001: {
        'en': 'Determine whether zucchini can be eaten raw, when unusual bitterness is a warning sign, and how washing and preparation affect safe consumption.',
        'es': 'Determinar si el calabacín puede comerse crudo, cuándo un amargor inusual es una señal de alerta y cómo influyen el lavado y la preparación.',
    },
    1002: {
        'en': 'Determine whether asparagus can be eaten raw and how texture, digestibility, washing, and preparation differ from cooked asparagus.',
        'es': 'Determinar si los espárragos pueden comerse crudos y cómo cambian la textura, la digestibilidad, el lavado y la preparación frente a cocinarlos.',
    },
    1003: {
        'en': 'Determine whether green beans should be eaten raw, what lectin-related or digestive concerns matter, and how cooking changes safety and texture.',
        'es': 'Determinar si las judías verdes deben comerse crudas, qué preocupaciones relacionadas con lectinas o digestión importan y cómo cambia la cocción su seguridad y textura.',
    },
    1004: {
        'en': 'Determine whether sweet potato can be eaten raw and how starch, digestibility, texture, and preparation affect whether doing so makes sense.',
        'es': 'Determinar si el boniato puede comerse crudo y cómo el almidón, la digestibilidad, la textura y la preparación influyen en que tenga sentido hacerlo.',
    },
    1005: {
        'en': 'Assess the risks of eating raw potato, including glycoalkaloids in green or sprouted potatoes, resistant starch, digestibility, and safer preparation.',
        'es': 'Valorar los riesgos de comer patata cruda, incluidos los glicoalcaloides de patatas verdes o con brotes, el almidón resistente, la digestibilidad y una preparación más segura.',
    },
    1006: {
        'en': 'Determine whether mango skin is edible and how texture, washing, residues, and possible skin irritation affect whether it is worth eating.',
        'es': 'Determinar si la piel del mango es comestible y cómo influyen la textura, el lavado, los residuos y la posible irritación en que merezca la pena comerla.',
    },
    1007: {
        'en': 'Determine whether orange peel is edible and how bitterness, washing, wax or residues, zesting, and preparation affect practical use.',
        'es': 'Determinar si la piel de naranja es comestible y cómo influyen el amargor, el lavado, las ceras o residuos, el rallado y la preparación.',
    },
    1008: {
        'en': 'Determine whether lemon peel is edible and how washing, bitterness, wax or residues, zesting, and preparation affect safe practical use.',
        'es': 'Determinar si la piel de limón es comestible y cómo influyen el lavado, el amargor, las ceras o residuos, el rallado y la preparación.',
    },
    1009: {
        'en': 'Determine whether shrimp shells are edible and how texture, preparation, choking risk, digestibility, and shellfish allergy context affect consumption.',
        'es': 'Determinar si la cáscara de camarones o langostinos es comestible y cómo influyen la textura, la preparación, el riesgo de atragantamiento, la digestibilidad y la alergia al marisco.',
    },
    1010: {
        'en': 'Assess the real toxicity risk from apple seeds, how chewing and dose change cyanogenic-compound exposure, and what accidental ingestion usually means.',
        'es': 'Valorar el riesgo tóxico real de las semillas de manzana, cómo la masticación y la dosis cambian la exposición a compuestos cianogénicos y qué implica una ingestión accidental.',
    },
    1011: {
        'en': 'Assess what happens if a cherry pit is swallowed, how whole versus crushed pits change choking and cyanogenic-compound risk, and when concern is warranted.',
        'es': 'Valorar qué ocurre al tragar un hueso de cereza, cómo cambian el riesgo de atragantamiento y compuestos cianogénicos si está entero o triturado y cuándo preocuparse.',
    },
    1012: {
        'en': 'Determine whether watermelon seeds are edible and how eating them whole, chewing them, roasting them, and digestibility affect their practical use.',
        'es': 'Determinar si las semillas de sandía son comestibles y cómo influyen comerlas enteras, masticarlas, tostarlas y su digestibilidad.',
    },
    1013: {
        'en': 'Determine whether papaya seeds are edible, what is known about safety and flavor, and why concentrated or large amounts deserve more caution.',
        'es': 'Determinar si las semillas de papaya son comestibles, qué se sabe sobre su seguridad y sabor y por qué las cantidades grandes o concentradas requieren más cautela.',
    },
    1014: {
        'en': 'Determine whether broccoli stalks are edible and how trimming, peeling, cooking, texture, and nutrition affect the best ways to use them.',
        'es': 'Determinar si el tallo del brócoli es comestible y cómo el recorte, pelado, cocción, textura y valor nutricional influyen en la mejor forma de aprovecharlo.',
    },
    1015: {
        'en': 'Determine which squash skins are edible, how variety and skin thickness matter, and when cooking, peeling, or leaving the skin on makes sense.',
        'es': 'Determinar qué pieles de calabaza son comestibles, cómo importan la variedad y el grosor y cuándo tiene sentido cocinarla, pelarla o dejarla puesta.',
    },
}

def serialize(data, raw):
    compact = raw.count('\n') <= 1
    if compact:
        return json.dumps(data, ensure_ascii=False, separators=(',', ':')) + '\n'
    return json.dumps(data, ensure_ascii=False, indent=2) + '\n'

def main():
    index = {}
    for lang in ('es', 'en'):
        for path in sorted((ROOT / lang).glob('*.json')):
            raw = path.read_text(encoding='utf-8')
            data = json.loads(raw)
            number = data.get('article_number')
            if isinstance(number, int) and number in INTENTS:
                index[(number, lang)] = (path, raw, data)

    expected = {(number, lang) for number in INTENTS for lang in ('es', 'en')}
    missing = sorted(expected - set(index))
    if missing:
        raise SystemExit(f'Missing target article versions: {missing}')

    changed = []
    for number in sorted(INTENTS):
        for lang in ('es', 'en'):
            path, raw, data = index[(number, lang)]
            seo = data.setdefault('seo', {})
            target = INTENTS[number][lang]
            if seo.get('search_intent') == target:
                continue
            seo['search_intent'] = target
            path.write_text(serialize(data, raw), encoding='utf-8')
            changed.append(path.as_posix())

    print('SEARCH_INTENT_REFINEMENT')
    print(f'ARTICLES={len(INTENTS)}')
    print(f'VERSIONS_CHANGED={len(changed)}')
    for path in changed:
        print(f'CHANGED={path}')

if __name__ == '__main__':
    main()
