#!/usr/bin/env python3
import json
import re
from pathlib import Path

ROOT = Path('content/articles')

COMMON_REPLACEMENTS = {
    'cocinado con calor seco': 'cocinado',
    'cocinada con calor seco': 'cocinada',
    'conserva en aceite, escurrida con espina': 'en conserva, escurrida',
    'conserva en aceite, escurrida': 'en conserva, escurrida',
    'conserva en agua, escurrido': 'al natural, escurrido',
    'cooked with dry heat': 'cooked',
    'cooked, dry heat': 'cooked',
    'canned in oil, drained solids with bone': 'canned, drained',
    'canned in oil, drained': 'canned, drained',
    'registro concreto de composición': 'referencia nutricional concreta',
    'registro USDA utilizado': 'datos de USDA utilizados',
    'registro utilizado': 'datos utilizados',
    'registro consultado': 'datos de referencia',
    'registros USDA': 'datos de USDA',
    'specific USDA record': 'USDA reference data',
    'record used': 'reference data',
    'database entry': 'reference data',
}

FORBIDDEN = [
    'merece un artículo específico',
    'merecen un artículo específico',
    'merece un artículo aparte',
    'merecen un artículo aparte',
    'en otro artículo',
    'lo veremos aparte',
    'se tratará aparte',
    'deserves its own article',
    'deserve their own article',
    'in another article',
    'covered elsewhere',
    'we will cover',
    "we'll cover",
]


def clean_string(text: str, language: str) -> str:
    out = text
    for old, new in COMMON_REPLACEMENTS.items():
        out = out.replace(old, new)

    if language == 'es':
        replacements = {
            'por lo que las recomendaciones de consumo para embarazadas y niños merecen un artículo específico.':
                'por lo que el contenido de mercurio también debe tenerse en cuenta al valorar con qué frecuencia se consume, aunque aquí estemos comparando proteína.',
            'Eso se desarrolla mejor en artículos específicos que convertir este ranking en una lista de prohibiciones.':
                'También conviene valorar la sal y, en especies grandes como algunos atunes, el mercurio, aunque aquí el criterio principal sea la relación entre proteína y calorías.',
            'Pueden ser excelentes fuentes de proteína, pero merecen su propia categoría.':
                'Pueden ser excelentes fuentes de proteína, pero se comparan por separado porque su contenido de agua es distinto.',
            'Por eso esta tabla no utiliza rangos ambiguos. Cada fila corresponde a un registro concreto de composición, con su forma de preparación, y se ordena de mayor a menor según ese valor.':
                'Para que la comparación sea clara, la tabla utiliza una forma de consumo habitual y la indica de manera sencilla: cocinado o en conserva cuando corresponde. La preparación puede mover algo las cifras por cambios de agua, pero no convierte un pescado normal en una fuente de proteína completamente distinta.',
            'La cocción pierde agua y concentra proteína; una conserva puede perder todavía más agua o incorporar aceite.':
                'Al cocinar, el pescado pierde parte de su agua y la proteína queda algo más concentrada por 100 g. En las conservas también influye el líquido de cobertura.',
            'Al ser un gran depredador, puede acumular mercurio. En España y la UE existen recomendaciones especiales para grupos vulnerables respecto a especies con alto contenido en mercurio. Por eso “más proteína” no equivale a “conviene comerlo más veces”.':
                'Al ser un gran depredador, puede acumular más mercurio que especies pequeñas. En España y la UE existen recomendaciones específicas para grupos vulnerables. Por eso que tenga unos 23–24 g de proteína por 100 g no significa que deba elegirse con más frecuencia que otros pescados.',
            'Esto lo convierte también en un alimento muy eficiente por caloría. Sin embargo, el atún no debe evaluarse solo por proteína: especies grandes pueden acumular más mercurio, por lo que las recomendaciones de consumo para embarazadas y niños merecen un artículo específico.':
                'Esto lo convierte también en un alimento muy eficiente por caloría. Aun así, no conviene valorar el atún solo por la proteína: las especies grandes pueden acumular más mercurio que pescados pequeños, de modo que la variedad sigue siendo importante.',
            'El salmón atlántico de acuicultura cocinado aporta unos <strong>22,10 g por 100 g</strong>. Queda por debajo de atún o sardina en este ranking, pero contiene mucha más grasa y energía.':
                'El salmón atlántico cocinado aporta unos <strong>22,10 g de proteína por 100 g</strong>. En esta referencia queda por debajo del atún, con 29,15 g, y de la sardina en conserva, con 24,62 g. A cambio, contiene bastante más grasa que el atún y aporta omega-3 de cadena larga.',
            'La gran diferencia está en la grasa. Bacalao o eglefino son muy magros; caballa o salmón contienen bastante más grasa. El grupo culinario no determina una superioridad proteica.':
                'La diferencia más clara está en la grasa. En los datos utilizados, el bacalao cocinado aporta unas 105 kcal por 100 g, mientras la caballa ronda 262 kcal y el salmón unas 206 kcal. La proteína, en cambio, se mantiene en un rango bastante estrecho: aproximadamente 20–24 g por 100 g en estos ejemplos.',
        }
    else:
        replacements = {
            'Protein density should not override those species-specific recommendations.':
                'Protein density is only one part of the choice: mercury level, omega-3 content, serving size and variety also matter.',
            'Tuna is extremely protein-dense, especially drained canned tuna in water. Species matters for mercury: larger tuna species generally accumulate more than smaller “light” tuna species.':
                'Tuna is highly protein-dense. Drained canned tuna in water often provides about 24–26 g of protein per 100 g, while some cooked tuna entries approach 29 g. Larger tuna species can also contain more mercury than smaller light-tuna species, so variety matters.',
            'They contain more fat and calories than lean white fish, but much of the nutritional interest lies in long-chain omega-3 fats. A protein-per-calorie ranking would therefore place them lower without making them less nutritious overall.':
                'They contain more fat and calories than lean white fish. For example, cooked salmon commonly provides about 22–25 g of protein per 100 g while also supplying long-chain omega-3 fats. Cod can provide a similar amount of protein with far fewer calories because it is much leaner.',
            'A ranking can show concentration, but the spread is narrow enough that choosing fish solely because it has 25 instead of 22 grams of protein misses important differences in fat, omega-3s and mercury.':
                'A ranking can show concentration, but the spread is fairly narrow: many common fish provide roughly 20–29 g of protein per 100 g. A difference of 2 or 3 g is often less important than the fish\'s fat, omega-3 content, mercury level and the portion you actually eat.',
        }

    for old, new in replacements.items():
        out = out.replace(old, new)

    # Remove editorial hand-offs while keeping the sentence self-contained.
    if language == 'es':
        out = re.sub(r'[^.<>]{0,120}(?:merece|merecen) un artículo (?:específico|aparte)[^.<>]*\.', '', out, flags=re.I)
        out = re.sub(r'[^.<>]{0,120}en otro artículo[^.<>]*\.', '', out, flags=re.I)
    else:
        out = re.sub(r'[^.<>]{0,120}deserve(?:s)? (?:its|their) own article[^.<>]*\.', '', out, flags=re.I)
        out = re.sub(r'[^.<>]{0,120}in another article[^.<>]*\.', '', out, flags=re.I)

    # Tidy accidental double spaces created by replacements without touching HTML structure.
    out = re.sub(r' {2,}', ' ', out)
    return out


def clean_value(value, language):
    if isinstance(value, str):
        return clean_string(value, language)
    if isinstance(value, list):
        return [clean_value(v, language) for v in value]
    if isinstance(value, dict):
        return {k: clean_value(v, language) for k, v in value.items()}
    return value


def polish_fish_article(data):
    lang = data.get('language')
    if data.get('article_number') != 14:
        return data
    html = data.get('content_html', '')
    if lang == 'es':
        html = html.replace('Atún aleta amarilla, cocinado', 'Atún, cocinado')
        html = html.replace('Anchoa europea, en conserva, escurrida', 'Anchoas, en conserva, escurridas')
        html = html.replace('Sardina atlántica, en conserva, escurrida', 'Sardinas, en conserva, escurridas')
        html = html.replace('Trucha arcoíris de acuicultura, cocinada', 'Trucha, cocinada')
        html = html.replace('Trucha arcoíris salvaje, cocinada', 'Trucha, cocinada (otra referencia)')
        html = html.replace('Salmón atlántico de acuicultura, cocinado', 'Salmón, cocinado')
        html = html.replace('Bacalao atlántico, cocinado', 'Bacalao, cocinado')
        html = html.replace('Caballa atlántica, cocinada', 'Caballa, cocinada')
        html = html.replace('Pescado y preparación', 'Pescado y forma habitual')
        html = html.replace('<h2>1. Atún aleta amarilla: 29,15 g</h2>', '<h2>1. Atún: alrededor de 29 g</h2>')
        html = html.replace('<h2>2. Anchoas en conserva: 28,89 g</h2>', '<h2>2. Anchoas en conserva: alrededor de 29 g</h2>')
        data['excerpt'] = 'El atún cocinado se acerca a 29 g de proteína por 100 g en la referencia utilizada. Anchoas, sardinas, caballa, trucha, pez espada, bacalao y salmón también aportan cantidades altas. La tabla diferencia de forma sencilla entre pescado cocinado y en conserva, sin convertir cada método de preparación en un ranking distinto.'
    else:
        html = html.replace('Water-packed tuna, drained', 'Canned tuna in water, drained')
        html = html.replace('Lean white fish such as pollock', 'Lean white fish, such as pollock')
        data['excerpt'] = 'Tuna is among the most protein-dense fish, but sardines, cod, salmon, mackerel and other common fish all provide substantial protein. The comparison keeps preparation simple—mainly cooked fish or drained canned fish—so the numbers stay useful instead of becoming a database exercise.'
    data['content_html'] = html
    return data


def main():
    paths = sorted(ROOT.glob('es/*.json')) + sorted(ROOT.glob('en/*.json'))
    if len(paths) != 50:
        raise SystemExit(f'Expected 50 article JSON files, found {len(paths)}')

    changed = 0
    forbidden_hits = []
    for path in paths:
        original = json.loads(path.read_text(encoding='utf-8'))
        language = original.get('language', 'es')
        cleaned = clean_value(original, language)
        cleaned = polish_fish_article(cleaned)

        # Force a normalized, readable JSON representation for the whole reviewed set.
        text = json.dumps(cleaned, ensure_ascii=False, indent=2) + '\n'
        path.write_text(text, encoding='utf-8')
        changed += 1

        haystack = text.lower()
        for phrase in FORBIDDEN:
            if phrase.lower() in haystack:
                forbidden_hits.append((str(path), phrase))

    if forbidden_hits:
        for path, phrase in forbidden_hits:
            print(f'FORBIDDEN {path}: {phrase}')
        raise SystemExit('Editorial hand-off phrases remain after clean pass')

    print(f'CLEANED_ARTICLES={changed}')
    print('SELF_CONTAINED_ARTICLE_AUDIT=PASS')


if __name__ == '__main__':
    main()
