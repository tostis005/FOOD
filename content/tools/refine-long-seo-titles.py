#!/usr/bin/env python3
from __future__ import annotations

import json
from pathlib import Path

ROOT = Path('content/articles')

TITLE_MAP = {
    ('es', 907): 'Mayonesa separada o aguada: cuándo desecharla',
    ('es', 911): 'Verduras congeladas en bloque: ¿se rompió la cadena de frío?',
    ('en', 843): 'Soft Blueberries: How to Tell If They’re Spoiled',
    ('en', 908): 'Soy Sauce Sediment or Crystals: Is It Still Good?',
    ('en', 914): 'Swollen Food Package: Why You Shouldn’t Taste It',
    ('es', 845): 'Pelos blancos en las moras: ¿es moho?',
    ('en', 883): 'Egg Yolk Breaks When Cracked: Is It Still Fresh?',
    ('en', 898): 'Separated Oil in Peanut Butter: Should You Stir It?',
    ('en', 911): 'Frozen Vegetables Clumped Together: What It Means',
    ('es', 910): 'Azúcar moreno duro: por qué ocurre y si sigue bueno',
    ('es', 997): 'Sushi grade: qué significa y si garantiza seguridad',
    ('en', 910): 'Hard Brown Sugar: Why It Happens and If It’s Still Good',
    ('en', 912): 'Large Ice Crystals in Ice Cream: Is It Safe?',
    ('es', 898): 'Aceite separado en crema de cacahuete: ¿hay que mezclarlo?',
    ('es', 909): 'Madre del vinagre: qué es la masa gelatinosa',
    ('es', 756): 'Tomate: ¿fruta o verdura? La respuesta botánica',
    ('en', 840): 'White or Green Strawberries: Ripeness or Spoilage?',
    ('es', 912): 'Cristales de hielo en el helado: ¿se puede comer?',
    ('en', 858): 'Brown Center in Pineapple: Can You Eat the Rest?',
    ('en', 886): 'Condensation in a Bread Bag: When to Worry',
    ('es', 843): 'Arándanos blandos: cómo saber si están pasados',
    ('es', 903): 'Lata abollada: cuándo es segura y cuándo desecharla',
    ('en', 86): 'Are All Calories the Same? How the Body Processes Them',
    ('en', 887): 'Damp or Sticky Bread Without Mold: When to Discard It',
    ('es', 1016): 'Leche cortada en el café: por qué ocurre y si está mala',
    ('es', 883): 'Yema que se rompe al cascar: qué dice de la frescura',
    ('en', 876): 'Watery Whipped Cream: Why It Happens and Is It Safe?',
}

def serialize(data, raw):
    compact = raw.count('\n') <= 1
    return (json.dumps(data, ensure_ascii=False, separators=(',', ':')) if compact
            else json.dumps(data, ensure_ascii=False, indent=2)) + '\n'

def main():
    targets = {}
    for lang in ('es', 'en'):
        for path in sorted((ROOT / lang).glob('*.json')):
            raw = path.read_text(encoding='utf-8')
            data = json.loads(raw)
            key = (lang, data.get('article_number'))
            if key in TITLE_MAP:
                targets[key] = (path, raw, data)

    missing = sorted(set(TITLE_MAP) - set(targets))
    if missing:
        raise SystemExit(f'Missing SEO title targets: {missing}')

    changed = []
    for key, new_title in TITLE_MAP.items():
        if len(new_title) > 65:
            raise SystemExit(f'Curated title still too long ({len(new_title)}): {key} {new_title}')
        path, raw, data = targets[key]
        seo = data.setdefault('seo', {})
        if seo.get('title') == new_title:
            continue
        seo['title'] = new_title
        path.write_text(serialize(data, raw), encoding='utf-8')
        changed.append(path.as_posix())

    print('LONG_SEO_TITLE_REFINEMENT')
    print(f'TARGETS={len(TITLE_MAP)}')
    print(f'FILES_CHANGED={len(changed)}')
    for path in changed:
        print(f'CHANGED={path}')

if __name__ == '__main__':
    main()
