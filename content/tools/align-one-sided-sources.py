#!/usr/bin/env python3
from __future__ import annotations
import json
from pathlib import Path

ROOT = Path("content/articles")

ADDITIONS = {
    ("en",133): [{"name":"European Commission — Egg marketing standards","url":"https://agriculture.ec.europa.eu/farming/animal-products/eggs_en","note":"EU commercial egg-size categories used to contextualize how protein per egg changes with egg size."}],
    ("en",188): [{"name":"BEDCA — Base de Datos Española de Composición de Alimentos","url":"https://www.bedca.net/","note":"Spanish food-composition reference for fresh and aged cheeses."}],
    ("en",268): [{"name":"BEDCA — Base de Datos Española de Composición de Alimentos","url":"https://www.bedca.net/","note":"Spanish composition data for hake, monkfish, cod, and other commonly eaten fish."}],
    ("en",365): [{"name":"MAPA — Jamón serrano","url":"https://www.mapa.gob.es/es/ministerio/servicios/informacion/jamon%20serrano_tcm30-102527.pdf","note":"Spanish Ministry of Agriculture reference describing cured ham and white tyrosine crystals in long-cured products."}],
    ("es",431): [{"name":"FDA — Using the Nutrition Facts Label to Choose Milk and Plant-Based Beverages","url":"https://www.fda.gov/food/nutrition-education-resources-materials/using-nutrition-facts-label-choose-milk-and-plant-based-beverages","note":"Guía para comparar proteína, calcio, vitamina D, potasio, grasa saturada y azúcares añadidos entre bebidas vegetales."}],
    ("en",455): [{"name":"UC Davis — Why do some fruit ripen after they are picked?","url":"https://postharvest.ucdavis.edu/es/node/7350","note":"Explains the role of ethylene in bananas and other climacteric fruits after harvest."}],
    ("es",678): [
        {"name":"USDA FoodData Central","url":"https://fdc.nal.usda.gov/","note":"Base de referencia para composición y valores nutricionales de alimentos."},
        {"name":"FDA — Standards of Identity for Food","url":"https://www.fda.gov/food/nutrition-food-labeling-and-critical-foods/standards-identity-food","note":"Normas de identidad de productos lácteos, quesos y alimentos relacionados."}
    ],
    ("es",679): [
        {"name":"USDA FoodData Central","url":"https://fdc.nal.usda.gov/","note":"Base de referencia para comparar composición de yogur y crema agria."},
        {"name":"FDA — Standards of Identity for Food","url":"https://www.fda.gov/food/nutrition-food-labeling-and-critical-foods/standards-identity-food","note":"Normas de identidad aplicables a productos lácteos y cremas."}
    ],
    ("es",680): [
        {"name":"FDA — Standards of Identity for Food","url":"https://www.fda.gov/food/nutrition-food-labeling-and-critical-foods/standards-identity-food","note":"Normas de identidad para queso crema, crema agria y productos lácteos relacionados."},
        {"name":"USDA FoodData Central","url":"https://fdc.nal.usda.gov/","note":"Base de referencia para comparar grasa, proteína y energía."}
    ],
    ("es",681): [
        {"name":"FDA — Standards of Identity for Food","url":"https://www.fda.gov/food/nutrition-food-labeling-and-critical-foods/standards-identity-food","note":"Normas de identidad para mozzarella y otros quesos."},
        {"name":"USDA FoodData Central","url":"https://fdc.nal.usda.gov/","note":"Base de referencia para comparar composición nutricional de quesos."}
    ],
    ("es",682): [
        {"name":"FDA — Standards of Identity for Food","url":"https://www.fda.gov/food/nutrition-food-labeling-and-critical-foods/standards-identity-food","note":"Normas de identidad que distinguen distintos tipos de queso y queso procesado."},
        {"name":"USDA FoodData Central","url":"https://fdc.nal.usda.gov/","note":"Base de referencia para comparar composición de quesos tradicionales y procesados."}
    ],
    ("es",717): [
        {"name":"FDA — Serving Size on the Nutrition Facts Label","url":"https://www.fda.gov/food/nutrition-facts-label/serving-size-nutrition-facts-label","note":"Explica que el tamaño de ración de la etiqueta refleja cantidades habitualmente consumidas y no una recomendación de ingesta."},
        {"name":"FDA — How to Understand and Use the Nutrition Facts Label","url":"https://www.fda.gov/food/nutrition-facts-label/how-understand-and-use-nutrition-facts-label","note":"Guía sobre tamaño de ración, raciones por envase y etiquetado en doble columna."}
    ],
}

def find_article(lang: str, number: int) -> Path:
    prefix = f"{number:03d}-"
    matches = list((ROOT / lang).glob(prefix + "*.json"))
    if len(matches) != 1:
        raise SystemExit(f"Expected one file for {lang} #{number}, found {matches}")
    return matches[0]

def dump_like(path: Path, data: dict, raw: str) -> None:
    compact = raw.strip().count("\n") == 0
    text = json.dumps(data, ensure_ascii=False, separators=(",", ":")) if compact else json.dumps(data, ensure_ascii=False, indent=2)
    path.write_text(text + "\n", encoding="utf-8")

def main():
    changed = []
    for key, additions in ADDITIONS.items():
        lang, number = key
        path = find_article(lang, number)
        raw = path.read_text(encoding="utf-8")
        data = json.loads(raw)
        sources = data.get("sources")
        if not isinstance(sources, list):
            sources = []
        seen = {str(s.get("url") or "").rstrip("/") for s in sources if isinstance(s, dict)}
        for src in additions:
            u = src["url"].rstrip("/")
            if u not in seen:
                sources.append(src)
                seen.add(u)
        data["sources"] = sources
        if len(sources) < 2:
            raise SystemExit(f"{path} still has fewer than two sources")
        new_raw_before = raw
        dump_like(path, data, raw)
        if path.read_text(encoding="utf-8") != new_raw_before:
            changed.append(path.as_posix())
    print("BILINGUAL_SOURCE_ALIGNMENT")
    print(f"VERSIONS_CHANGED={len(changed)}")
    for path in changed:
        print("CHANGED=" + path)

if __name__ == "__main__":
    main()
