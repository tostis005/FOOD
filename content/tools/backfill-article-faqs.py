#!/usr/bin/env python3
import json
from pathlib import Path

ROOT=Path('content/articles')
MAP=ROOT/'FAQ-BACKFILL-MAP.json'

mapping=json.loads(MAP.read_text(encoding='utf-8'))
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
for raw_number,localized in sorted(mapping.items(), key=lambda item:int(item[0])):
    number=int(raw_number)
    for lang in ('es','en'):
        data=index.get((number,lang)); path=paths.get((number,lang))
        if not data or not path:
            raise SystemExit(f'Missing article {number} [{lang}]')
        faq=localized.get(lang) or []
        if len(faq)<2:
            raise SystemExit(f'FAQ map for {number} [{lang}] has fewer than two items')
        current=data.get('faq') if isinstance(data.get('faq'),list) else []
        if len(current)>=2:
            continue
        data['faq']=faq
        path.write_text(json.dumps(data,ensure_ascii=False,indent=2)+'\n',encoding='utf-8')
        changed.append(path.as_posix())

print('FAQ_BACKFILL')
print(f'ARTICLES_MAPPED={len(mapping)}')
print(f'FILES_CHANGED={len(changed)}')
for path in changed:
    print(f'CHANGED={path}')
