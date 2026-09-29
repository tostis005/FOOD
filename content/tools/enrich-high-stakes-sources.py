#!/usr/bin/env python3
import json
from pathlib import Path

ROOT=Path('content/articles')
MAP=ROOT/'HIGH-STAKES-SOURCE-MAP.json'

mapping=json.loads(MAP.read_text(encoding='utf-8'))
index={}
paths={}
for lang in ('es','en'):
    for path in sorted((ROOT/lang).glob('*.json')):
        data=json.loads(path.read_text(encoding='utf-8'))
        n=data.get('article_number')
        if isinstance(n,int):
            index[(n,lang)]=data
            paths[(n,lang)]=path

changed=[]
for raw_number, source in sorted(mapping.items(), key=lambda item:int(item[0])):
    number=int(raw_number)
    for lang in ('es','en'):
        data=index.get((number,lang)); path=paths.get((number,lang))
        if not data or not path:
            raise SystemExit(f'Missing article {number} [{lang}]')
        sources=data.get('sources') if isinstance(data.get('sources'),list) else []
        target_url=str(source.get('url') or '').rstrip('/').lower()
        if any(str(item.get('url') or '').rstrip('/').lower()==target_url for item in sources if isinstance(item,dict)):
            continue
        item={
            'name':str(source.get('name') or '').strip(),
            'url':str(source.get('url') or '').strip(),
            'note':str(source.get('note_'+lang) or '').strip(),
        }
        sources.append(item)
        data['sources']=sources
        path.write_text(json.dumps(data,ensure_ascii=False,indent=2)+'\n',encoding='utf-8')
        changed.append(path.as_posix())

print('HIGH_STAKES_SOURCE_ENRICHMENT')
print(f'ARTICLES_MAPPED={len(mapping)}')
print(f'FILES_CHANGED={len(changed)}')
for path in changed:
    print(f'CHANGED={path}')
