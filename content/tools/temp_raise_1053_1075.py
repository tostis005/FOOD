#!/usr/bin/env python3
import html
import json
import re
from pathlib import Path

MIN_WORDS = 631

ADD = {
    ('es',1053): "<p>La intensidad de ese frescor también depende de la dosis y del contexto. Un caramelo de menta concentrado activa más receptores TRPM8 que una infusión suave, y el efecto puede seguir unos minutos después de tragar porque el mentol permanece un tiempo en contacto con las mucosas. Beber aire frío o agua fresca puede reforzar la sensación, no porque el mentol haya enfriado físicamente la boca, sino porque ambas señales convergen en circuitos sensoriales relacionados con el frío. Por eso dos productos con la misma temperatura pueden sentirse muy distintos si uno contiene suficiente mentol.</p>",
    ('es',1068): "<p>También conviene distinguir cantidad total de proteína y concentración por peso. Al cocinar una pieza de carne suele perderse agua y parte de la grasa, de modo que 100 gramos de carne cocinada pueden concentrar más proteína que 100 gramos de la misma carne cruda aunque no se haya creado proteína nueva. El calor desnaturaliza las proteínas, es decir, cambia su estructura tridimensional, pero eso no equivale a hacer desaparecer los aminoácidos. Con cocciones normales, la cuestión práctica es sobre todo cuánto líquido y grasa se pierden y qué peso final queda, no una supuesta destrucción masiva de proteína.</p>",
    ('es',1070): "<p>La comparación se vuelve mucho más clara si se sigue el mismo lote desde seco hasta cocido. Una cantidad concreta de arroz seco conserva aproximadamente la misma energía total después de hervirse, pero acaba pesando bastante más porque ha absorbido agua. Por eso una ración de 70 gramos en seco no debe compararse directamente con 70 gramos cocidos. Para registrar una dieta, lo más consistente es elegir un método —peso seco con datos de arroz seco o peso cocido con datos de arroz cocido— y mantenerlo, especialmente cuando cambia la cantidad de agua absorbida entre variedades y métodos de cocción.</p>",
    ('es',1074): "<p>Lo que sí cambia con el calor es la estructura física de los tejidos vegetales. Las paredes celulares se ablandan, parte de las pectinas se solubilizan y la fibra puede quedar más accesible o repartirse de otra manera entre fracciones solubles e insolubles. Si se hierve una verdura y se desecha el agua, algunos componentes solubles pueden perderse con ella; al vapor o al horno esa pérdida suele ser menor. Nada de esto convierte una verdura cocinada en un alimento sin fibra. Para la cantidad final importan tanto el método de cocción como si se consume o se descarta el líquido.</p>",
    ('en',1062): "<p>Bitterness also changes with brewing, not just with the bean itself. A darker roast, a finer grind, hotter water, or a longer extraction can shift which bitter compounds reach the cup and how strongly they are perceived. Caffeine contributes, but it is only part of the picture: chlorogenic-acid-derived compounds and other roasting products also matter. That is why two coffees with similar caffeine can taste very different. If a brew seems excessively bitter, changing extraction time, grind size, water temperature, or the coffee-to-water ratio can alter the balance without changing the underlying bean.</p>",
    ('en',1064): "<p>The amount of quinine is only one part of how bitter tonic tastes. Sugar or non-sugar sweeteners can mask some bitterness, carbonation adds sharpness, and serving temperature changes the balance of sweetness, aroma, and bitter perception. This is why a dry tonic can taste much more bitter than a sweeter one even when both use quinine for the characteristic flavor. The bitterness itself is therefore expected and does not indicate spoilage. Freshness should be judged from the package condition, storage history, carbonation, and any genuinely abnormal odor or appearance rather than from quinine's normal bitter taste.</p>",
    ('en',1065): "<p>Apple buoyancy can vary because the proportion of internal air is not identical in every fruit. Cultivar, maturity, growing conditions, and storage can change tissue structure and the size of intercellular spaces. A very dense apple may sit lower in the water than a highly porous one, yet both can still float if their average density remains below that of water. The useful distinction is between density and weight: a large apple can be heavy and still float, because flotation depends on mass relative to volume, not on mass alone.</p>",
    ('en',1067): "<p>Orange color is therefore mainly a manufacturing choice rather than evidence that the cheese contains more fat, more protein, or a stronger flavor. Annatto can produce a consistent color across seasons, while an uncolored cheddar can be pale cream even when it is made with a similar process and aged for a similar time. Color intensity also varies by producer and style. When comparing cheddars, age, moisture, cultures, salt, milk composition, and maturation conditions tell you far more about texture and flavor than whether the cheese happens to be orange or white.</p>",
    ('en',1068): "<p>It also helps to separate total protein from protein per 100 grams. Meat commonly loses water and some fat during cooking, so the cooked piece weighs less. That can make the protein concentration per 100 grams look higher even though cooking did not create extra protein. Heat denatures proteins by unfolding their structure, but denaturation is not the same as removing their amino acids. Under ordinary cooking conditions, the practical nutritional change is driven much more by moisture loss, fat loss, and the final serving weight than by any large-scale disappearance of protein.</p>",
    ('en',1069): "<p>The easiest way to see the effect is to follow one piece of chicken through cooking. If a raw portion loses water in the pan, the finished portion weighs less while retaining most of its protein. Comparing 100 grams raw with 100 grams cooked therefore compares different amounts of the original chicken. For food tracking, consistency matters: use raw weight with raw nutrition data or cooked weight with cooked nutrition data. Mixing the two can make protein intake look artificially high or low even when the actual portion has not changed.</p>",
    ('en',1070): "<p>A practical comparison should start with the same batch of rice. The calories in a measured amount of dry rice are spread across a larger final mass after the grains absorb water, so calories per 100 grams fall. Different varieties and cooking methods absorb different amounts of water, which is why cooked values can vary noticeably between databases or recipes. For calorie tracking, choose either dry weight with dry-rice data or cooked weight with cooked-rice data and use that method consistently. Switching between the two is a common source of large logging errors.</p>",
    ('en',1071): "<p>The same principle explains why two servings that look similar on a nutrition app may not be comparable. Dry pasta gains substantial water during boiling, so a fixed amount of dry pasta becomes a heavier cooked portion without gaining the corresponding calories. Shape, cooking time, and how thoroughly the pasta is drained all affect final water content. Fresh pasta is another case again because it already contains more water before cooking. For reliable tracking, match the nutrition entry to the state in which the pasta was weighed rather than converting dry and cooked weights as though they were interchangeable.</p>",
    ('en',1072): "<p>The reduction is real, but it is not a fixed percentage for every pan of ground beef. How much fat renders out depends on the starting fat level, cooking temperature, how finely the meat is broken up, cooking time, and how aggressively the liquid is drained or blotted. Some water is lost at the same time, so the final weight also changes. That makes exact calorie estimates difficult without a specific tested method. For routine tracking, nutrition data for cooked and drained ground beef are more defensible than subtracting an arbitrary number of calories from the raw label.</p>",
    ('en',1073): "<p>What blending changes most is the physical structure of the fruit. Cell walls are broken into smaller particles, the mixture becomes easier to drink quickly, and viscosity can change, but the fiber has not simply vanished if the pulp remains in the drink. Straining is different because it can physically remove much of the fiber-containing material. The distinction matters when comparing a whole-fruit smoothie with clear juice: both may come from the same fruit, yet only the strained product has had a substantial portion of the solid plant material removed.</p>",
    ('en',1074): "<p>Heating does change the architecture of plant tissues. Cell walls soften, some pectins become more soluble, and the balance between soluble and insoluble fiber can shift. Boiling can also move soluble material into the cooking water, so discarding that liquid may reduce what ends up on the plate. Steaming, roasting, or eating the cooking liquid changes that outcome. These structural changes can make vegetables softer and sometimes easier to digest, but they do not turn a normally fibrous vegetable into a fiber-free food. Method and what is actually eaten both matter.</p>",
    ('en',1075): "<p>Another source of mismatch is that food labels are not built by simply multiplying the displayed rounded grams by 4, 4, and 9. Regulators allow specific energy factors for components such as fiber, sugar alcohols, and alcohol, and the displayed grams and calories can each be rounded under labeling rules. Manufacturers may also use laboratory analysis or approved food-composition data. Small differences therefore accumulate, especially in products with fiber or polyols. Recalculating from the visible macro numbers is useful as a rough check, but it should not be expected to reproduce the printed calorie value exactly.</p>",
}

def visible_text(content_html):
    text = re.sub(r'(?is)<(?:script|style)\b[^>]*>.*?</(?:script|style)>', ' ', content_html)
    text = re.sub(r'<[^>]+>', ' ', text)
    text = html.unescape(text)
    return re.sub(r'\s+', ' ', text).strip()

def wc(text):
    return len(re.findall(r"\b[^\W_]+(?:['’\-][^\W_]+)*\b", text, flags=re.UNICODE))

changed = []
for (lang, num), paragraph in ADD.items():
    matches = []
    for path in Path('content/articles', lang).glob(f'{num}-*.json'):
        matches.append(path)
    if len(matches) != 1:
        raise SystemExit(f'{lang}-{num}: expected one JSON, found {len(matches)}')
    path = matches[0]
    data = json.loads(path.read_text(encoding='utf-8'))
    before = wc(visible_text(str(data.get('content_html',''))))
    if before >= MIN_WORDS:
        print(f'PASS_EXISTING {lang}-{num} words={before}')
        continue
    data['content_html'] = str(data.get('content_html','')).rstrip() + paragraph
    after = wc(visible_text(data['content_html']))
    if after < MIN_WORDS:
        raise SystemExit(f'{lang}-{num}: still short after expansion: {after}')
    path.write_text(json.dumps(data, ensure_ascii=False, indent=2) + '\n', encoding='utf-8')
    changed.append(path.as_posix())
    print(f'EXPANDED {lang}-{num} words={before}->{after}')

print(f'CHANGED={len(changed)}')
for path in changed:
    print(path)
