#!/usr/bin/env python3
from __future__ import annotations
import json
from pathlib import Path

ROOT = Path("content/articles")

SOURCES = {
  10: [
    ("Factors affecting the water holding capacity of red meat products: a review", "https://pubmed.ncbi.nlm.nih.gov/18274969/",
     "Revisión sobre capacidad de retención de agua en carne y factores que influyen en la pérdida de jugos.",
     "Review of water-holding capacity in meat and the factors that influence moisture loss."),
    ("Meat flavor precursors and factors influencing flavor precursors — systematic review", "https://pubmed.ncbi.nlm.nih.gov/26319308/",
     "Revisión sobre precursores del sabor y reacciones que contribuyen al dorado y aroma de la carne cocinada.",
     "Review of meat flavor precursors and reactions that contribute to browning and cooked-meat aroma."),
  ],
  82: [
    ("FAO — Ultra-processed foods, diet quality and human health", "https://openknowledge.fao.org/handle/20.500.14283/ca5644en",
     "Informe de FAO basado en la clasificación NOVA y su definición de alimentos ultraprocesados.",
     "FAO report based on the NOVA classification and its definition of ultra-processed foods."),
    ("PAHO — Ultra-processed food and drink products in Latin America", "https://iris.paho.org/bitstream/handle/10665.2/51094/9789275120323_eng.pdf?sequence=5",
     "Documento de la OPS que define los productos ultraprocesados con el marco NOVA.",
     "PAHO report defining ultra-processed products using the NOVA framework."),
  ],
  138: [
    ("ISAPP consensus statement on probiotics", "https://pubmed.ncbi.nlm.nih.gov/24912386/",
     "Consenso internacional que define qué es un probiótico y cuándo puede utilizarse el término.",
     "International consensus defining probiotics and appropriate use of the term."),
    ("ISAPP consensus statement on prebiotics", "https://pubmed.ncbi.nlm.nih.gov/28611480/",
     "Consenso internacional que actualiza la definición y alcance del término prebiótico.",
     "International consensus updating the definition and scope of the term prebiotic."),
  ],
  153: [
    ("FoodSafety.gov — FoodKeeper App", "https://www.foodsafety.gov/keep-food-safe/foodkeeper-app",
     "Guía oficial de almacenamiento y conservación de numerosos alimentos.",
     "Official storage and keeping-time guidance for a wide range of foods."),
    ("USDA — Storing Fresh Produce", "https://www.fns.usda.gov/fs/produce-safety/storage",
     "Guía sobre qué frutas y verduras conviene refrigerar y cuáles se conservan mejor fuera de la nevera.",
     "Guidance on which produce should be refrigerated and which is better stored outside the refrigerator."),
  ],
  154: [
    ("Vitamin retention in eight fruits and vegetables: fresh versus frozen", "https://pubmed.ncbi.nlm.nih.gov/25526594/",
     "Comparación analítica de vitaminas en frutas y verduras frescas, refrigeradas y congeladas.",
     "Analytical comparison of vitamins in fresh, refrigerated, and frozen fruits and vegetables."),
    ("Mineral, fiber and phenolic retention: fresh versus frozen", "https://pubmed.ncbi.nlm.nih.gov/25525668/",
     "Comparación de minerales, fibra y compuestos fenólicos entre productos frescos y congelados.",
     "Comparison of minerals, fiber, and phenolics in fresh and frozen produce."),
  ],
  155: [
    ("Vitamin retention in eight fruits and vegetables: fresh versus frozen", "https://pubmed.ncbi.nlm.nih.gov/25526594/",
     "Comparación analítica de vitaminas en verduras frescas, refrigeradas y congeladas.",
     "Analytical comparison of vitamins in fresh, refrigerated, and frozen vegetables."),
    ("Mineral, fiber and phenolic retention: fresh versus frozen", "https://pubmed.ncbi.nlm.nih.gov/25525668/",
     "Comparación de minerales, fibra y compuestos fenólicos entre verduras frescas y congeladas.",
     "Comparison of minerals, fiber, and phenolics in fresh and frozen vegetables."),
  ],
  156: [
    ("Effects of food processing on nutritive values", "https://pubmed.ncbi.nlm.nih.gov/10790761/",
     "Revisión sobre cómo distintos métodos de procesado, incluida la conserva, afectan al valor nutritivo.",
     "Review of how processing methods, including canning, affect nutritive value."),
    ("Vegetable cost metrics and nutrient density across fresh, frozen and canned forms", "https://pmc.ncbi.nlm.nih.gov/articles/PMC3654977/",
     "Análisis que compara densidad nutricional en verduras frescas, congeladas y en conserva.",
     "Analysis comparing nutrient density in fresh, frozen, and canned vegetables."),
  ],
  159: [
    ("Carotenoid accessibility from carrots: effects of cooking and particle size", "https://pubmed.ncbi.nlm.nih.gov/12001013/",
     "Estudio sobre cómo la cocción y el tamaño de partícula modifican la accesibilidad de carotenoides de la zanahoria.",
     "Study of how cooking and particle size affect carotenoid accessibility from carrots."),
    ("Beta-carotene bioavailability from raw chopped and cooked pureed carrots", "https://pubmed.ncbi.nlm.nih.gov/14673607/",
     "Ensayo que compara la biodisponibilidad de beta-caroteno de zanahoria cruda y cocinada.",
     "Trial comparing beta-carotene bioavailability from raw and cooked carrots."),
  ],
  376: [
    ("BOE — Real Decreto 4/2014, norma de calidad para la carne, el jamón, la paleta y la caña de lomo ibérico", "https://www.boe.es/eli/es/rd/2014/01/10/4",
     "Norma oficial que establece las categorías y colores de los precintos del ibérico.",
     "Official Spanish regulation establishing Iberian-product categories and seal colors."),
    ("MAPA — Protocolos de actuación del sector ibérico", "https://www.mapa.gob.es/es/alimentacion/temas/control-calidad/mesa-iberico/protocolos-de-actuacion",
     "Documentación oficial del Ministerio de Agricultura sobre control y aplicación de la norma del ibérico.",
     "Official Ministry of Agriculture documentation on implementation and control of Iberian-product rules."),
  ],
  378: [
    ("European Commission — Delegated Regulation (EU) 2022/2104", "https://eur-lex.europa.eu/legal-content/EN/TXT/?uri=CELEX:32022R2104",
     "Normativa vigente que reserva «extracción en frío» a aceites obtenidos por debajo de 27 °C mediante percolación o centrifugación.",
     "Current EU regulation reserving “cold extraction” for oils obtained below 27 °C by percolation or centrifugation."),
    ("International Olive Council — Media Watch on cold extraction", "https://www.internationaloliveoil.org/wp-content/uploads/2019/12/MediaWatchApril2012.pdf",
     "Documento del Consejo Oleícola Internacional que contextualiza el límite europeo de 27 °C para «extracción en frío».",
     "International Olive Council publication explaining the EU 27 °C threshold for the term “cold extraction”."),
  ],
  381: [
    ("Mercasa — Guía práctica del porcino", "https://www.mercasa.es/wp-content/uploads/2022/03/guia_practica_porcino.pdf",
     "Describe la maza como la zona con más carne y la más sabrosa, tierna y jugosa del jamón.",
     "Describes the maza as the meatiest and most tender, juicy, and flavorful part of the ham."),
    ("Consorcio del Jamón Serrano Español — Corte del jamón a cuchillo", "https://consorcioserrano.es/como-cortar-tu-jamon-consorcio-serrano/el-corte-de-jamon-serrano-a-cuchillo/",
     "Guía sectorial sobre las partes principales del jamón y su corte.",
     "Industry guide to the main parts of a Serrano ham and how they are carved."),
  ],
  420: [
    ("National Center for Home Food Preservation — Freezing Avocados", "https://nchfp.uga.edu/how/freeze/fruits/freezing-avocados/",
     "Guía basada en investigación para congelar aguacate; recomienda puré y acidificación para mantener calidad.",
     "Research-based guidance for freezing avocado, recommending puree and acidification for best quality."),
    ("FoodSafety.gov — FoodKeeper App", "https://www.foodsafety.gov/keep-food-safe/foodkeeper-app",
     "Guía oficial de conservación doméstica y tiempos de almacenamiento.",
     "Official home-storage and keeping-time guidance."),
  ],
  461: [
    ("National Center for Home Food Preservation — Freezing Strawberries", "https://nchfp.uga.edu/how/freeze/fruits/freezing-strawberries/",
     "Instrucciones basadas en investigación para congelar fresas con distintos métodos de envasado.",
     "Research-based instructions for freezing strawberries using different packing methods."),
    ("FoodSafety.gov — FoodKeeper App", "https://www.foodsafety.gov/keep-food-safe/foodkeeper-app",
     "Guía oficial de conservación doméstica y tiempos de almacenamiento.",
     "Official home-storage and keeping-time guidance."),
  ],
  466: [
    ("Enzymatic browning reactions in apple and apple products", "https://pubmed.ncbi.nlm.nih.gov/8011143/",
     "Revisión de las reacciones de pardeamiento en manzana y los métodos para controlarlas.",
     "Review of enzymatic browning in apples and methods used to control it."),
    ("Penn State Extension — Preserving Color and Preventing Browning of Foods", "https://extension.psu.edu/preserving-color-and-preventing-browning-of-foods",
     "Explica el pardeamiento en fruta cortada y el uso de ácido ascórbico y cítrico para reducirlo.",
     "Explains browning in cut fruit and the use of ascorbic and citric acids to reduce it."),
  ],
  486: [
    ("FAO — Quinoa recipe platform", "https://www.fao.org/in-action/quinoa-platform/recetarios/receta-del-dia/en/",
     "Guía práctica de preparación de quinoa: lavado, proporción de agua, cocción y tostado opcional.",
     "Practical quinoa preparation guidance covering rinsing, water ratio, cooking, and optional toasting."),
    ("Effects of processing on quinoa saponins", "https://pubmed.ncbi.nlm.nih.gov/27173545/",
     "Estudio sobre cómo lavado y cocción modifican el contenido de saponinas de la quinoa.",
     "Study of how washing and cooking affect quinoa saponin content."),
  ],
  567: [
    ("USDA FoodData Central", "https://fdc.nal.usda.gov/",
     "Base de composición para comparar proteína, grasa, carbohidratos y energía de tofu y soja texturizada.",
     "Composition database for comparing protein, fat, carbohydrate, and energy in tofu and textured soy products."),
    ("Harvard T.H. Chan School of Public Health — The Nutrition Source: Soy", "https://nutritionsource.hsph.harvard.edu/soy/",
     "Revisión nutricional de la soja y alimentos derivados como el tofu.",
     "Nutrition review of soy and soy foods including tofu."),
  ],
  597: [
    ("NCCIH — Turmeric", "https://www.nccih.nih.gov/health/turmeric",
     "Ficha del NIH que distingue la cúrcuma de sus componentes, incluida la curcumina.",
     "NIH fact sheet distinguishing turmeric from its constituents, including curcumin."),
    ("Curcumin, the active substance of turmeric: its effects on health and ways to improve its bioavailability", "https://pubmed.ncbi.nlm.nih.gov/34143894/",
     "Revisión académica sobre la curcumina como componente bioactivo principal de la cúrcuma.",
     "Academic review of curcumin as a major bioactive constituent of turmeric."),
  ],
  629: [
    ("Understanding oil absorption during deep-fat frying", "https://pubmed.ncbi.nlm.nih.gov/19595388/",
     "Revisión sobre los mecanismos y factores que determinan cuánto aceite absorbe un alimento al freírse.",
     "Review of the mechanisms and factors determining oil uptake during frying."),
    ("Nutrient losses and gains during frying", "https://pubmed.ncbi.nlm.nih.gov/9713586/",
     "Revisión de cambios nutricionales durante la fritura, incluida la incorporación de grasa y el aumento de densidad energética.",
     "Review of nutritional changes during frying, including fat uptake and increased energy density."),
  ],
  683: [
    ("Codex Alimentarius — Standard for Aqueous Coconut Products (CXS 240-2003)", "https://www.fao.org/fao-who-codexalimentarius/sh-proxy/es/?lnk=1&url=https%253A%252F%252Fworkspace.fao.org%252Fsites%252Fcodex%252FStandards%252FCXS%2B240-2003%252FCXS_240e.pdf",
     "Norma internacional que define leche de coco y crema de coco y sus características.",
     "International standard defining coconut milk and coconut cream and their characteristics."),
    ("USDA FoodData Central", "https://fdc.nal.usda.gov/",
     "Base de composición para contrastar energía, grasa y otros nutrientes.",
     "Composition database for cross-checking energy, fat, and other nutrients."),
  ],
  684: [
    ("Codex Alimentarius — Standard for Aqueous Coconut Products (CXS 240-2003)", "https://www.fao.org/fao-who-codexalimentarius/sh-proxy/es/?lnk=1&url=https%253A%252F%252Fworkspace.fao.org%252Fsites%252Fcodex%252FStandards%252FCXS%2B240-2003%252FCXS_240e.pdf",
     "Norma internacional que define la leche de coco culinaria.",
     "International standard defining culinary coconut milk."),
    ("FDA — Milk and Plant-Based Milk Alternatives: Know the Nutrient Difference", "https://www.fda.gov/consumers/consumer-updates/milk-and-plant-based-milk-alternatives-know-nutrient-difference",
     "Guía que explica la variabilidad nutricional de las bebidas vegetales comercializadas como alternativas a la leche.",
     "Guidance explaining nutrient variability among plant-based beverages sold as milk alternatives."),
  ],
  918: [
    ("Japan Meat Grading Association — Beef carcass grading standard", "https://www.jmga.or.jp/standard/beef/",
     "Fuente oficial del sistema japonés que combina grado de rendimiento A–C y calidad de carne 1–5.",
     "Official Japanese grading standard combining yield grade A–C and meat quality grade 1–5."),
    ("Japan Ministry of Agriculture, Forestry and Fisheries — Wagyu and Japanese beef guide", "https://www.maff.go.jp/e/data/publish/attach/pdf/jameat-32.pdf",
     "Guía oficial japonesa que explica la clasificación de rendimiento y calidad de la carne de vacuno.",
     "Official Japanese guide explaining beef yield and meat-quality grading."),
  ],
  1140: [
    ("Dietary carotenoids and egg-yolk color and carotenoid content", "https://pubmed.ncbi.nlm.nih.gov/33805547/",
     "Estudio que muestra cómo los carotenoides de la dieta de la gallina modifican el color y el contenido de carotenoides de la yema.",
     "Study showing how dietary carotenoids alter egg-yolk color and carotenoid content."),
    ("Factors affecting egg yolk color through dietary carotenoid supply", "https://pubmed.ncbi.nlm.nih.gov/35248403/",
     "Investigación sobre la relación entre alimentación de la gallina, pigmentos y color de la yema.",
     "Research on the relationship between hen diet, carotenoid supply, and yolk color."),
  ],
}

def file_for(lang: str, number: int) -> Path:
    matches = list((ROOT / lang).glob(f"{number:03d}-*.json"))
    if len(matches) != 1:
        raise SystemExit(f"Expected one {lang} file for #{number}, found {matches}")
    return matches[0]

def write_like(path: Path, data: dict, raw: str) -> None:
    compact = raw.strip().count("\n") == 0
    payload = json.dumps(data, ensure_ascii=False, separators=(",", ":")) if compact else json.dumps(data, ensure_ascii=False, indent=2)
    path.write_text(payload + "\n", encoding="utf-8")

def main():
    changed = []
    for number, refs in sorted(SOURCES.items()):
        for lang in ("es", "en"):
            path = file_for(lang, number)
            raw = path.read_text(encoding="utf-8")
            data = json.loads(raw)
            sources = data.get("sources") if isinstance(data.get("sources"), list) else []
            seen = {str(s.get("url") or "").rstrip("/") for s in sources if isinstance(s, dict)}
            for name, url, es_note, en_note in refs:
                key = url.rstrip("/")
                if key in seen:
                    continue
                sources.append({
                    "name": name,
                    "url": url,
                    "note": es_note if lang == "es" else en_note,
                })
                seen.add(key)
            if len(sources) < 2:
                raise SystemExit(f"{path} still has fewer than two sources")
            data["sources"] = sources
            write_like(path, data, raw)
            if path.read_text(encoding="utf-8") != raw:
                changed.append(path.as_posix())

    print("SPECIALIZED_SOURCE_REINFORCEMENT")
    print(f"LOGICAL_ARTICLES={len(SOURCES)}")
    print(f"VERSIONS_CHANGED={len(changed)}")
    for path in changed:
        print("CHANGED=" + path)

if __name__ == "__main__":
    main()
