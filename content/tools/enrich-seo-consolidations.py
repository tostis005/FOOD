#!/usr/bin/env python3
import json, re
from pathlib import Path

ROOT=Path('content/articles')
MAP=ROOT/'SEO-CONSOLIDATIONS.json'

def key_url(value):
    return re.sub(r'[#?].*$', '', str(value or '').strip()).rstrip('/').lower()

def key_text(value):
    return re.sub(r'\s+', ' ', str(value or '')).strip().lower()

def merge(items_a, items_b, key_fn, field):
    out=[]; seen=set()
    for item in list(items_a or [])+list(items_b or []):
        if not isinstance(item,dict): continue
        key=key_fn(item.get(field))
        if not key or key in seen: continue
        seen.add(key); out.append(item)
    return out

mapping={int(k):int(v) for k,v in json.loads(MAP.read_text(encoding='utf-8')).items()}
index={}; paths={}
for lang in ('es','en'):
    for path in sorted((ROOT/lang).glob('*.json')):
        data=json.loads(path.read_text(encoding='utf-8'))
        number=data.get('article_number')
        if isinstance(number,int):
            index[(number,lang)]=data; paths[(number,lang)]=path

changed=[]
for source_number,target_number in sorted(mapping.items()):
    for lang in ('es','en'):
        source=index.get((source_number,lang)); target=index.get((target_number,lang)); path=paths.get((target_number,lang))
        if not source or not target or not path:
            raise SystemExit(f'Missing article for {source_number}->{target_number} [{lang}]')
        before=json.dumps([target.get('sources',[]),target.get('faq',[])],ensure_ascii=False,sort_keys=True)
        target['sources']=merge(target.get('sources',[]),source.get('sources',[]),key_url,'url')
        target['faq']=merge(target.get('faq',[]),source.get('faq',[]),key_text,'question')
        after=json.dumps([target.get('sources',[]),target.get('faq',[])],ensure_ascii=False,sort_keys=True)
        if before!=after:
            path.write_text(json.dumps(target,ensure_ascii=False,indent=2)+'\n',encoding='utf-8')
            changed.append(path.as_posix())

print(f'CONSOLIDATIONS={len(mapping)}')
print(f'FILES_CHANGED={len(changed)}')
for path in changed: print(f'CHANGED={path}')
