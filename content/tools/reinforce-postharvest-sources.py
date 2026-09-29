#!/usr/bin/env python3
from __future__ import annotations
import json
from pathlib import Path

ROOT = Path("content/articles")
NUMBERS = {429,430,440,442,444,448,449,454,464,465,467,819,824,825,826,827}
URL = "https://www.ars.usda.gov/is/np/CommercialStorage/CommercialStorage.pdf"

SOURCE = {
    "es": {
        "name": "USDA ARS — Agriculture Handbook 66: The Commercial Storage of Fruits, Vegetables, and Florist and Nursery Stocks",
        "url": URL,
        "note": "Manual técnico de poscosecha con índices de madurez, etileno, almacenamiento, calidad y trastornos fisiológicos por cultivo."
    },
    "en": {
        "name": "USDA ARS — Agriculture Handbook 66: The Commercial Storage of Fruits, Vegetables, and Florist and Nursery Stocks",
        "url": URL,
        "note": "Technical postharvest handbook covering maturity indices, ethylene, storage, quality, and physiological disorders by commodity."
    }
}

def file_for(lang, number):
    matches=list((ROOT/lang).glob(f"{number:03d}-*.json"))
    if len(matches)!=1:
        raise SystemExit(f"Expected one {lang} file for #{number}, found {matches}")
    return matches[0]

def write_like(path,data,raw):
    compact=raw.strip().count("\n")==0
    payload=json.dumps(data,ensure_ascii=False,separators=(",",":")) if compact else json.dumps(data,ensure_ascii=False,indent=2)
    path.write_text(payload+"\n",encoding="utf-8")

def main():
    changed=[]
    for number in sorted(NUMBERS):
        for lang in ("es","en"):
            path=file_for(lang,number)
            raw=path.read_text(encoding="utf-8")
            data=json.loads(raw)
            sources=data.get("sources") if isinstance(data.get("sources"),list) else []
            seen={str(s.get("url") or "").rstrip("/") for s in sources if isinstance(s,dict)}
            if URL.rstrip("/") not in seen:
                sources.append(SOURCE[lang])
            data["sources"]=sources
            write_like(path,data,raw)
            if path.read_text(encoding="utf-8")!=raw:
                changed.append(path.as_posix())
    print("POSTHARVEST_SOURCE_REINFORCEMENT")
    print(f"LOGICAL_ARTICLES={len(NUMBERS)}")
    print(f"VERSIONS_CHANGED={len(changed)}")
    for path in changed:
        print("CHANGED="+path)

if __name__=="__main__":
    main()
