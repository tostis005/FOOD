#!/usr/bin/env python3
from __future__ import annotations
import argparse, html, json, math, re, unicodedata
from collections import Counter, defaultdict
from pathlib import Path

ROOT = Path('content/articles')
TAXONOMIES = ROOT / 'taxonomies.json'
OUT_JSON = Path('content/audits/SEO-LIBRARY-AUDIT.json')
OUT_MD = Path('content/audits/SEO-LIBRARY-AUDIT.md')
CONSOLIDATIONS_PATH = ROOT / 'SEO-CONSOLIDATIONS.json'
CANNIBAL_ALLOWLIST_PATH = ROOT / 'SEO-CANNIBALIZATION-ALLOWLIST.json'
STOP = set("""a al algo and are as at be by can como con cual cuando de del desde do does el en es esta este esto for from ha hay how if in into is it la las lo los mas me menos mi no of o on or para pero por que se si sin sobre su sus than the to un una uno unos unas vs what when where which who why with y ya you your article articulo articulos food foods alimento alimentos guia guide best better mejor mejores difference differences diferencia diferencias""".split())
WORD_RE = re.compile(r"[\wÀ-ÿ]+(?:['’\-][\wÀ-ÿ]+)*", re.UNICODE)

def load_consolidations():
    try:
        data=json.loads(CONSOLIDATIONS_PATH.read_text(encoding='utf-8'))
    except Exception:
        return {}
    return {int(k):int(v) for k,v in data.items() if str(k).isdigit() and int(v)>0 and int(k)!=int(v)}

CONSOLIDATIONS=load_consolidations()

def load_cannibal_allowlist():
    try:
        rows=json.loads(CANNIBAL_ALLOWLIST_PATH.read_text(encoding='utf-8'))
    except Exception:
        return set(), []
    pairs=set(); clean=[]
    if not isinstance(rows,list):
        return pairs, clean
    for row in rows:
        if not isinstance(row,dict):
            continue
        try:
            a=int(row.get('a')); b=int(row.get('b'))
        except (TypeError,ValueError):
            continue
        if a<=0 or b<=0 or a==b:
            continue
        pair=tuple(sorted((a,b)))
        pairs.add(pair)
        clean.append({'a':pair[0],'b':pair[1],'reason':str(row.get('reason') or '').strip()})
    clean.sort(key=lambda x:(x['a'],x['b']))
    return pairs, clean

CANNIBAL_ALLOWLIST, CANNIBAL_ALLOWLIST_ROWS=load_cannibal_allowlist()

def canonical_number(number):
    if not isinstance(number,int):
        return number
    seen=set()
    while number in CONSOLIDATIONS and number not in seen:
        seen.add(number)
        number=CONSOLIDATIONS[number]
    return number

def norm(text):
    text = unicodedata.normalize('NFKD', str(text or ''))
    text = ''.join(ch for ch in text if not unicodedata.combining(ch))
    return re.sub(r'\s+', ' ', text.lower()).strip()

def visible(value):
    return re.sub(r'\s+', ' ', html.unescape(re.sub(r'<[^>]+>', ' ', str(value or '')))).strip()

def runtime_meta_description(meta, excerpt, body=''):
    meta=visible(meta)
    excerpt=visible(excerpt)
    body=visible(body)
    description=meta
    if len(excerpt) >= 90 and (len(description) < 90 or (len(description) > 220 and len(excerpt) <= 220)):
        description=excerpt
    if len(description) < 90 and len(excerpt) < 90:
        description=body
    if not description:
        description=excerpt or body
    if len(description) <= 158:
        return description
    shortened=description[:157]
    shortened=re.sub(r'\\s+\\S*$', '', shortened)
    shortened=shortened.rstrip(' ,;:–—-')
    return shortened + '…'

def word_list(text):
    return WORD_RE.findall(text)

def toks(text):
    out=[]
    for token in re.findall(r'[a-z0-9]+', norm(text)):
        if len(token)<3 or token in STOP or token.isdigit():
            continue
        if len(token)>5 and token.endswith('s') and not token.endswith(('ss','us','is')):
            token=token[:-1]
        out.append(token)
    return out

def load_rows(root):
    rows=[]
    for lang in ('es','en'):
        for path in sorted((root/lang).glob('*.json')):
            try:
                data=json.loads(path.read_text(encoding='utf-8'))
            except Exception as exc:
                rows.append({'language':lang,'path':path.as_posix(),'invalid_json':str(exc)})
                continue
            rows.append({'language':lang,'path':path.as_posix(),'data':data})
    return rows

def metric(row):
    d=row['data']; seo=d.get('seo') if isinstance(d.get('seo'),dict) else {}; tax=d.get('taxonomy') if isinstance(d.get('taxonomy'),dict) else {}
    body=visible(d.get('content_html','')); title=str(d.get('title') or '').strip(); seo_title=str(seo.get('title') or '').strip(); meta=str(seo.get('meta_description') or '').strip()
    intent=str(seo.get('search_intent') or '').strip(); excerpt=str(d.get('excerpt') or '').strip(); faq=d.get('faq') if isinstance(d.get('faq'),list) else []; sources=d.get('sources') if isinstance(d.get('sources'),list) else []
    faq_text=' '.join(str(item.get('question',''))+' '+str(item.get('answer','')) for item in faq if isinstance(item,dict))
    body_words=len(word_list(body))
    faq_words=len(word_list(faq_text))
    page_words=body_words+faq_words
    effective_seo_title=seo_title + ' | Quinnoa' if seo_title and len(seo_title)<=55 else seo_title
    effective_meta=runtime_meta_description(meta,excerpt,body)
    feature=' '.join([title,seo_title,str(d.get('slug') or ''),intent,excerpt[:260]])
    return {
        'language':row['language'],'path':row['path'],'number':d.get('article_number'),'translation_group':str(d.get('translation_group') or ''),
        'title':title,'slug':str(d.get('slug') or ''),'seo_title':seo_title,'seo_title_len':len(seo_title),'effective_seo_title':effective_seo_title,'effective_seo_title_len':len(effective_seo_title),'meta_description':meta,'meta_len':len(meta),'effective_meta_description':effective_meta,'effective_meta_len':len(effective_meta),
        'search_intent':intent,'excerpt_len':len(excerpt),'words':page_words,'body_words':body_words,'faq_words':faq_words,'h2':len(re.findall(r'<h2\b',str(d.get('content_html') or ''),re.I)),
        'faq_count':len(faq),'sources_count':len(sources),'food_family':str(tax.get('food_family') or ''),
        'article_types':[str(x) for x in (tax.get('article_types') or []) if isinstance(x,str)],'primary_article_type':str(tax.get('primary_article_type') or ''),
        'status':str(d.get('status') or ''),'first_answer':' '.join(word_list(body)[:32]),'feature_tokens':toks(feature),'title_tokens':set(toks(title+' '+seo_title)),
    }

def dup_groups(metrics,key):
    buckets=defaultdict(list)
    for m in metrics:
        v=norm(m.get(key,''))
        if v: buckets[(m['language'],v)].append(m)
    out=[]
    for (_,value),items in buckets.items():
        nums=sorted({x['number'] for x in items if isinstance(x.get('number'),int)})
        if len(nums)>1 and len({canonical_number(n) for n in nums})>1:
            out.append({'language':items[0]['language'],'value':value,'articles':[{'number':x['number'],'title':x['title'],'path':x['path']} for x in items]})
    return sorted(out,key=lambda g:(g['language'],g['articles'][0]['number'] or 0))

def cannibal_pairs(metrics,limit=250):
    by_lang=defaultdict(list)
    for m in metrics: by_lang[m['language']].append(m)
    results=[]
    for lang,items in by_lang.items():
        df=Counter(); tfs={}
        for m in items:
            tf=Counter(m['feature_tokens']); tfs[m['number']]=tf
            for t in tf: df[t]+=1
        n=len(items); vectors={}; norms={}; idf={}
        for token,count in df.items():
            idf[token]=math.log((n+1)/(count+1))+1.0
        for m in items:
            vec={}
            for t,c in tfs[m['number']].items():
                vec[t]=c*idf[t]
            vectors[m['number']]=vec; norms[m['number']]=math.sqrt(sum(v*v for v in vec.values())) or 1.0
        lookup={m['number']:m for m in items}; nums=sorted(lookup)
        for i,a_num in enumerate(nums):
            a=lookup[a_num]; va=vectors[a_num]
            for b_num in nums[i+1:]:
                b=lookup[b_num]; family=bool(a['food_family'] and a['food_family']==b['food_family']); type_overlap=len(set(a['article_types'])&set(b['article_types'])); shared_title_tokens=a['title_tokens']&b['title_tokens']; shared_title=len(shared_title_tokens)
                if not family and type_overlap==0 and shared_title<2: continue
                vb=vectors[b_num]; shared=set(va)&set(vb)
                if not shared: continue
                sim=sum(va[t]*vb[t] for t in shared)/(norms[a_num]*norms[b_num])
                union=a['title_tokens']|b['title_tokens']; jac=shared_title/len(union) if union else 0.0

                # Raw title Jaccard overvalues repeated editorial templates such
                # as "protein, fat and nutrition" or "how long ... in the
                # fridge". Weight title overlap by corpus rarity so entity
                # terms carry more evidence than generic query scaffolding.
                weighted_num=sum(idf.get(t,1.0)**2 for t in shared_title_tokens)
                weighted_den=sum(idf.get(t,1.0)**2 for t in union)
                weighted_jac=weighted_num/weighted_den if weighted_den else 0.0
                rare_shared=max((idf.get(t,1.0) for t in shared_title_tokens),default=0.0)
                subject_shared_tokens={t for t in shared_title_tokens if idf.get(t,1.0)>=5.5}
                subject_shared_idf=max((idf.get(t,1.0) for t in subject_shared_tokens),default=0.0)

                # Repeated editorial templates can still look very similar
                # after IDF weighting. Unless the full article text is nearly
                # identical, require a genuinely rare shared subject term.
                # This drops "raw/cooked chicken", different sauces, and
                # "healthy every day" templates while retaining pairs about
                # the same named food or ingredient.
                strong_text=sim>=0.76
                strong_title=bool(subject_shared_tokens) and weighted_jac>=0.50
                combined=bool(subject_shared_tokens) and sim>=0.66 and weighted_jac>=0.28
                if not (strong_text or strong_title or combined):
                    continue
                if shared_title<2 and sim<0.78:
                    continue
                if canonical_number(a_num)==canonical_number(b_num):
                    continue
                if tuple(sorted((a_num,b_num))) in CANNIBAL_ALLOWLIST:
                    continue
                results.append({'language':lang,'a':a_num,'a_title':a['title'],'b':b_num,'b_title':b['title'],'similarity':round(sim,4),'title_jaccard':round(jac,4),'weighted_title_jaccard':round(weighted_jac,4),'rare_shared_idf':round(rare_shared,4),'subject_shared_idf':round(subject_shared_idf,4),'subject_shared_tokens':sorted(subject_shared_tokens),'family_match':family,'type_overlap':type_overlap})
    results.sort(key=lambda x:(-max(x['similarity'],x['weighted_title_jaccard']),-x['similarity'],x['language'],x['a'],x['b']))
    return results[:limit]

def main():
    ap=argparse.ArgumentParser(); ap.add_argument('--root',default=str(ROOT)); ap.add_argument('--json-out',default=str(OUT_JSON)); ap.add_argument('--md-out',default=str(OUT_MD)); args=ap.parse_args()
    taxonomy=json.loads(TAXONOMIES.read_text(encoding='utf-8')); allowed_f={x['key'] for x in taxonomy.get('food_families',[])}; allowed_t={x['key'] for x in taxonomy.get('article_types',[])}
    family_aliases={str(k):str(v) for k,v in (taxonomy.get('food_family_aliases') or {}).items()}
    type_aliases={str(k):str(v) for k,v in (taxonomy.get('article_type_aliases') or {}).items()}
    recognized_f=allowed_f|set(family_aliases)
    recognized_t=allowed_t|set(type_aliases)
    raw=load_rows(Path(args.root)); invalid=[x for x in raw if 'invalid_json' in x]; metrics=[metric(x) for x in raw if 'data' in x]
    nums=defaultdict(set); pairs=defaultdict(dict); unknown_f=Counter(); unknown_t=Counter(); alias_f=Counter(); alias_t=Counter(); issues=defaultdict(list); firsts=defaultdict(list)
    for m in metrics:
        lang=m['language']; n=m['number']
        if isinstance(n,int): nums[lang].add(n); pairs[n][lang]=m
        if m['food_family']:
            if m['food_family'] not in recognized_f: unknown_f[m['food_family']]+=1
            elif m['food_family'] in family_aliases: alias_f[m['food_family']]+=1
        for t in m['article_types']:
            if t not in recognized_t: unknown_t[t]+=1
            elif t in type_aliases: alias_t[t]+=1
        if m['primary_article_type']:
            if m['primary_article_type'] not in recognized_t: unknown_t[m['primary_article_type']]+=1
            elif m['primary_article_type'] in type_aliases: alias_t[m['primary_article_type']]+=1

        # Redirected duplicate URLs are kept in the inventory for integrity
        # checks, but they are not part of the indexable corpus and must not
        # inflate content-quality review signals.
        if isinstance(n,int) and n in CONSOLIDATIONS:
            continue

        canonical_types={type_aliases.get(t,t) for t in m['article_types']}
        high_stakes=bool({'food-safety','health-daily-consumption'} & canonical_types)
        checks={
            'missing_seo_title':not m['seo_title'],'seo_title_over_65':m['seo_title_len']>65,'effective_seo_title_over_65':m['effective_seo_title_len']>65,'effective_seo_title_under_30':0<m['effective_seo_title_len']<30,
            'missing_meta':not m['meta_description'],'effective_meta_over_160':m['effective_meta_len']>160,'effective_meta_under_90':0<m['effective_meta_len']<90,
            'page_under_631':m['words']<631,'body_under_450':m['body_words']<450,'h2_under_3':m['h2']<3,'sources_under_2':m['sources_count']<2,'high_stakes_sources_under_2':high_stakes and m['sources_count']<2,'faq_under_2':m['faq_count']<2,'status_not_publish':m['status']!='publish'
        }
        value_map={'seo_title_over_65':m['seo_title_len'],'effective_seo_title_over_65':m['effective_seo_title_len'],'effective_seo_title_under_30':m['effective_seo_title_len'],'effective_meta_over_160':m['effective_meta_len'],'effective_meta_under_90':m['effective_meta_len'],'page_under_631':m['words'],'body_under_450':m['body_words'],'h2_under_3':m['h2'],'sources_under_2':m['sources_count'],'high_stakes_sources_under_2':m['sources_count'],'faq_under_2':m['faq_count'],'status_not_publish':m['status']}
        for key,hit in checks.items():
            if hit: issues[key].append({'language':lang,'number':n,'title':m['title'],'path':m['path'],'value':value_map.get(key)})
        fk=norm(m['first_answer'])
        if len(fk)>=80: firsts[(lang,fk)].append(m)
    all_nums=sorted(nums['es']|nums['en']); missing_pairs=[]; mismatches=[]
    for n in all_nums:
        p=pairs[n]
        if 'es' not in p or 'en' not in p: missing_pairs.append({'number':n,'languages':sorted(p)})
        elif p['es']['translation_group']!=p['en']['translation_group']: mismatches.append({'number':n,'es':p['es']['translation_group'],'en':p['en']['translation_group']})
    repeated=[]
    for (lang,value),items in firsts.items():
        if len(items)>1: repeated.append({'language':lang,'count':len(items),'articles':[{'number':x['number'],'title':x['title']} for x in items[:20]],'prefix':value[:220]})
    repeated.sort(key=lambda x:(-x['count'],x['language']))
    active_metrics=[m for m in metrics if not (isinstance(m.get('number'),int) and m['number'] in CONSOLIDATIONS)]
    indexable_numbers=[n for n in all_nums if n not in CONSOLIDATIONS]
    report={
        'summary':{'versions':len(metrics),'es':len(nums['es']),'en':len(nums['en']),'logical_articles':len(all_nums),'indexable_logical_articles':len(indexable_numbers),'consolidated_redirects':len(CONSOLIDATIONS),'max_article_number':max(all_nums) if all_nums else 0,'invalid_json':len(invalid),'missing_translation_pairs':len(missing_pairs),'translation_group_mismatches':len(mismatches)},
        'issue_counts':{k:len(v) for k,v in sorted(issues.items())},'source_data_signals':{
            'raw_seo_title_under_28':sum(1 for m in active_metrics if 0<m['seo_title_len']<28),
            'raw_meta_over_220':sum(1 for m in active_metrics if m['meta_len']>220),
        },'cannibalization_allowlist':CANNIBAL_ALLOWLIST_ROWS,'unknown_families':dict(unknown_f.most_common()),'unknown_article_types':dict(unknown_t.most_common()),'family_alias_usage':dict(alias_f.most_common()),'article_type_alias_usage':dict(alias_t.most_common()),
        'known_consolidations':{str(k):v for k,v in sorted(CONSOLIDATIONS.items())},
        'missing_translation_pairs':missing_pairs,'translation_group_mismatches':mismatches,'exact_duplicate_titles':dup_groups(active_metrics,'title'),'exact_duplicate_seo_titles':dup_groups(active_metrics,'seo_title'),
        'exact_duplicate_meta_descriptions':dup_groups(active_metrics,'meta_description'),'exact_duplicate_search_intents':dup_groups(active_metrics,'search_intent'),
        'repeated_first_answers':repeated[:100],'cannibalization_candidates':cannibal_pairs(active_metrics,250),'issues':{k:v[:500] for k,v in sorted(issues.items())},'invalid_json':invalid
    }
    outj=Path(args.json_out); outm=Path(args.md_out); outj.parent.mkdir(parents=True,exist_ok=True); outj.write_text(json.dumps(report,ensure_ascii=False,indent=2)+'\n',encoding='utf-8')
    s=report['summary']; lines=['# Quinnoa SEO Library Audit','',f"- Logical articles: **{s['logical_articles']}**",f"- Indexable logical articles after consolidations: **{s['indexable_logical_articles']}**",f"- 301 consolidations: **{s['consolidated_redirects']}**",f"- Versions: **{s['versions']}** ({s['es']} ES + {s['en']} EN)",f"- Highest article number: **{s['max_article_number']}**",f"- Invalid JSON: **{s['invalid_json']}**",f"- Missing translation pairs: **{s['missing_translation_pairs']}**",f"- Translation-group mismatches: **{s['translation_group_mismatches']}**",'','## Review signals','','| Signal | Count |','| --- | ---: |']
    for key,count in sorted(report['issue_counts'].items(),key=lambda kv:(-kv[1],kv[0])): lines.append(f'| {key} | {count} |')
    lines += ['','## Vocabulary drift','',('Unknown food families: '+(', '.join(report['unknown_families']) if report['unknown_families'] else 'none.')),('Unknown article types: '+(', '.join(f"{k} ({v})" for k,v in report['unknown_article_types'].items()) if report['unknown_article_types'] else 'none.')),('Recognized family aliases in use: '+(', '.join(f"{k} ({v})" for k,v in report['family_alias_usage'].items()) if report['family_alias_usage'] else 'none.')),('Recognized article-type aliases in use: '+(', '.join(f"{k} ({v})" for k,v in report['article_type_alias_usage'].items()) if report['article_type_alias_usage'] else 'none.')),'','## Exact duplicates','',f"- Titles: {len(report['exact_duplicate_titles'])}",f"- SEO titles: {len(report['exact_duplicate_seo_titles'])}",f"- Meta descriptions: {len(report['exact_duplicate_meta_descriptions'])}",f"- Search intents: {len(report['exact_duplicate_search_intents'])}",f"- Repeated opening answers: {len(report['repeated_first_answers'])}",'','## Potential cannibalization','',f"Candidates retained for manual review: **{len(report['cannibalization_candidates'])}**.",'These are similarity candidates, not automatic cannibalization verdicts.','','| Lang | A | B | Similarity | Title overlap |','| --- | ---: | ---: | ---: | ---: |']
    for item in report['cannibalization_candidates'][:80]: lines.append(f"| {item['language']} | {item['a']} - {item['a_title'].replace('|','/')} | {item['b']} - {item['b_title'].replace('|','/')} | {item['similarity']:.3f} | {item['title_jaccard']:.3f} |")
    outm.write_text('\n'.join(lines)+'\n',encoding='utf-8')
    print('SEO_LIBRARY_AUDIT')
    for k,v in s.items(): print(f'{k.upper()}={v}')
    for k,v in sorted(report['issue_counts'].items()): print(f'{k.upper()}={v}')
    print('UNKNOWN_ARTICLE_TYPES='+json.dumps(report['unknown_article_types'],ensure_ascii=False))
    print('EXACT_DUPLICATE_SEO_TITLES='+str(len(report['exact_duplicate_seo_titles'])))
    print('EXACT_DUPLICATE_META_DESCRIPTIONS='+str(len(report['exact_duplicate_meta_descriptions'])))
    print('CANNIBALIZATION_CANDIDATES='+str(len(report['cannibalization_candidates'])))
    return 0

if __name__=='__main__': raise SystemExit(main())
