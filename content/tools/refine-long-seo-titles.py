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
    ('es', 86): '¿Todas las calorías se procesan igual en el cuerpo?',
    ('es', 1010): 'Semillas de manzana: ¿son venenosas y cuánto riesgo hay?',
    ('es', 1018): 'Espuma en el agua de la pasta: por qué ocurre y cómo evitarla',
    ('es', 1023): 'Nata montada a mantequilla: por qué ocurre al batir',
    ('es', 1035): 'Verduras congeladas blandas: por qué ocurre al cocinarlas',
    ('es', 1289): 'Carne separada mecánicamente: qué es y cómo identificarla',
    ('es', 1317): 'Kombucha y probióticos: qué significa que esté fermentada',
    ('es', 1355): 'Sirope de agave: fructosa e índice glucémico',
    ('es', 283): 'Gambas en mal estado: cómo saber si están malas',
    ('es', 285): 'Huevos que flotan: qué significa y si están malos',
    ('es', 838): 'Uvas que se desprenden del racimo: ¿están frescas?',
    ('es', 840): 'Fresas blancas o verdes: ¿madurez o problema?',
    ('es', 844): 'Jugo en el envase de frambuesas: ¿están malas?',
    ('es', 846): 'Naranja seca por dentro: por qué ocurre',
    ('es', 849): 'Mandarina con piel separada: qué significa',
    ('es', 851): 'Pomelo con zonas pálidas o secas: ¿está malo?',
    ('es', 858): 'Piña con centro marrón: qué significa y si se puede comer',
    ('es', 866): 'Plátano rojo o rosado por dentro: qué significa',
    ('es', 874): 'Leche en mal estado: olor, grumos y textura',
    ('es', 876): 'Nata montada que suelta agua: por qué ocurre y si es segura',
    ('es', 877): 'Mantequilla con zonas oscuras: qué significa',
    ('es', 889): 'Arroz seco con insectos: qué hacer',
    ('es', 900): 'Semillas de chía con olor rancio: ¿están malas?',
    ('es', 901): 'Conserva con tapa abombada: por qué desecharla',
    ('es', 902): 'Óxido en una lata: cuándo es seguro y cuándo desecharla',
    ('es', 904): 'Cristales de azúcar en mermelada: ¿se puede comer?',
    ('es', 905): 'Agua separada en salsa de tomate: por qué ocurre',
    ('es', 906): 'Kétchup oscuro tras abrirlo: cuándo desecharlo',
    ('es', 908): 'Sedimento o cristales en salsa de soja: ¿sigue buena?',
    ('es', 996): 'Atún crudo: seguridad y diferencia con el atún para sushi',
    ('en', 1289): 'Mechanically Separated Meat: What It Is and How to Spot It',
    ('en', 1355): 'Agave Syrup: Fructose and Glycemic Index Explained',
    ('en', 653): 'Foods Highest in Electrolytes: Natural Sources Compared',
    ('en', 844): 'Raspberry Juice in the Container: Is It Spoiled?',
    ('en', 851): 'Pink Grapefruit with Pale or Dry Areas: Is It Spoiled?',
    ('en', 859): 'Pineapple Smells Like Alcohol: When to Discard It',
    ('en', 862): 'Yellow or Translucent Watermelon: Is It Spoiled?',
    ('en', 871): 'Fresh Figs Leaking or Fermented: When to Discard Them',
    ('en', 873): 'Pomegranate with Mold Inside: What It Means',
    ('en', 881): 'Watery or Sour Ricotta: How to Tell If It’s Spoiled',
    ('en', 889): 'Insects in Dry Rice: What to Do',
    ('en', 896): 'Black Spots on Tempeh: Normal or Spoiled?',
    ('en', 899): 'Insects or Powder in Nuts: What to Do',
    ('en', 900): 'Rancid-Smelling Chia Seeds: Are They Spoiled?',
    ('en', 904): 'Sugar Crystals in Jam: Why They Form and If It’s Safe',
    ('en', 906): 'Darkened Ketchup After Opening: When to Discard It',
    ('en', 913): 'Clumpy or Damp Ground Coffee: When to Discard It',
    ('en', 997): 'Sushi Grade: What It Means and Whether It Guarantees Safety',
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
