#!/usr/bin/env python3
from __future__ import annotations
import json
from pathlib import Path

ROOT = Path('content/articles')
TAXONOMIES = ROOT / 'taxonomies.json'
CONSOLIDATIONS = ROOT / 'SEO-CONSOLIDATIONS.json'
HIGH_STAKES = {'food-safety', 'health-daily-consumption'}

def unique_sources(items):
    out=[]; seen=set()
    for item in items or []:
        if not isinstance(item,dict):
            continue
        url=str(item.get('url') or '').strip()
        name=str(item.get('name') or '').strip()
        key=(url.rstrip('/').lower() if url else 'name:'+name.lower())
        if not key or key in seen:
            continue
        seen.add(key); out.append(item)
    return out

tax=json.loads(TAXONOMIES.read_text(encoding='utf-8'))
type_aliases={str(k):str(v) for k,v in (tax.get('article_type_aliases') or {}).items()}
consolidations={int(k):int(v) for k,v in json.loads(CONSOLIDATIONS.read_text(encoding='utf-8')).items()}

index={}
paths={}
for lang in ('es','en'):
    for path in sorted((ROOT/lang).glob('*.json')):
        data=json.loads(path.read_text(encoding='utf-8'))
        number=data.get('article_number')
        if isinstance(number,int):
            index[(number,lang)]=data
            paths[(number,lang)]=path

changed=[]
for number in sorted({n for n,_ in index}):
    if number in consolidations:
        continue
    pair={lang:index.get((number,lang)) for lang in ('es','en')}
    if not pair['es'] or not pair['en']:
        continue

    # Treat an article as high-stakes when either language classifies it as
    # food safety or health/regular-consumption after alias normalization.
    canonical_types=set()
    for lang in ('es','en'):
        raw=(pair[lang].get('taxonomy') or {}).get('article_types') or []
        canonical_types.update(type_aliases.get(str(t),str(t)) for t in raw)
    if not (canonical_types & HIGH_STAKES):
        continue

    sources={lang:unique_sources(pair[lang].get('sources') or []) for lang in ('es','en')}
    counts={lang:len(sources[lang]) for lang in ('es','en')}

    for lang,other in (('es','en'),('en','es')):
        if counts[lang] >= 2 or counts[other] < 2:
            continue

        target=list(sources[lang])
        seen={str(item.get('url') or '').strip().rstrip('/').lower() for item in target if item.get('url')}
        for source in sources[other]:
            url=str(source.get('url') or '').strip()
            key=url.rstrip('/').lower()
            if not url or key in seen:
                continue
            # Cross-language sync keeps only language-neutral bibliographic
            # fields so an English editorial note never leaks into Spanish,
            # or vice versa.
            target.append({
                'name': str(source.get('name') or '').strip() or url,
                'url': url,
            })
            seen.add(key)
            if len(target) >= 2:
                break

        if len(target) <= counts[lang]:
            continue

        pair[lang]['sources']=target
        path=paths[(number,lang)]
        path.write_text(json.dumps(pair[lang],ensure_ascii=False,indent=2)+'\n',encoding='utf-8')
        changed.append(path.as_posix())

print('BILINGUAL_HIGH_STAKES_SOURCE_SYNC')
print(f'FILES_CHANGED={len(changed)}')
for path in changed:
    print(f'CHANGED={path}')
