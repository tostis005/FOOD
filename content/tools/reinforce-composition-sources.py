#!/usr/bin/env python3
from __future__ import annotations
import json
from pathlib import Path

ROOT = Path("content/articles")
NUMBERS = {
    7,11,12,15,16,17,18,47,48,49,55,57,58,59,60,76,77,96,98,129,
    168,170,173,174,182,191,192,200,203,209,233,275,281,387,388,
    433,435,436,441,462,463,490,606
}

SOURCES = {
    "es": [
        {
            "name": "USDA FoodData Central",
            "url": "https://fdc.nal.usda.gov/",
            "note": "Base de datos de composición de alimentos utilizada para contrastar energía, macronutrientes y micronutrientes."
        },
        {
            "name": "BEDCA — Base de Datos Española de Composición de Alimentos",
            "url": "https://www.bedca.net/",
            "note": "Referencia española independiente para contrastar valores de composición de alimentos."
        },
    ],
    "en": [
        {
            "name": "USDA FoodData Central",
            "url": "https://fdc.nal.usda.gov/",
            "note": "Food-composition database used to cross-check energy, macronutrients, and micronutrients."
        },
        {
            "name": "BEDCA — Base de Datos Española de Composición de Alimentos",
            "url": "https://www.bedca.net/",
            "note": "Independent Spanish food-composition database used to cross-check nutrient values."
        },
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
    for number in sorted(NUMBERS):
        for lang in ("es", "en"):
            path = file_for(lang, number)
            raw = path.read_text(encoding="utf-8")
            data = json.loads(raw)
            sources = data.get("sources") if isinstance(data.get("sources"), list) else []
            seen = {str(s.get("url") or "").rstrip("/") for s in sources if isinstance(s, dict)}
            for source in SOURCES[lang]:
                key = source["url"].rstrip("/")
                if key not in seen:
                    sources.append(source)
                    seen.add(key)
            if len(sources) < 2:
                raise SystemExit(f"{path} still has fewer than two sources")
            data["sources"] = sources
            write_like(path, data, raw)
            if path.read_text(encoding="utf-8") != raw:
                changed.append(path.as_posix())

    print("COMPOSITION_SOURCE_REINFORCEMENT")
    print(f"LOGICAL_ARTICLES={len(NUMBERS)}")
    print(f"VERSIONS_CHANGED={len(changed)}")
    for path in changed:
        print("CHANGED=" + path)

if __name__ == "__main__":
    main()
