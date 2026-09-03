#!/usr/bin/env python3
"""Structural/editorial regression audit for the Quinnoa bilingual article corpus.

This is intentionally not a writing template. It catches objective regressions
that previously produced thin, card-like batches or broke WordPress taxonomy
and internal-link architecture. Narrative quality still requires a cold read.
"""
from __future__ import annotations
import json, re, sys
from collections import Counter, defaultdict
from html import unescape
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1] / 'articles'
EXPECTED = set(range(1, 636))
LANGS = ('es','en')
ALLOWED_FAMILY = {
 'general','meat','fish-seafood','eggs','dairy-cheese','legumes-soy','nuts-seeds',
 'grains-pseudocereals','tubers','vegetables-mushrooms','fruit','oils-fats',
 'beverages','cocoa-sweets','fermented','other-foods'
}
ALLOWED_TYPES = {
 'nutrition-composition','rankings','comparisons','food-safety','storage',
 'freezing-thawing','cooking-science','cooking-techniques','health-daily-consumption',
 'nutrition-concepts','myths-faq','processing-production','buying-quality-ripeness'
}
RANK_ES = re.compile(r'(?:\branking\b|\bcon más\b|\bcon menos\b|\bmás ricos?\b|\bmás ricas?\b|\bmejores fuentes\b|\blos mejores\b|\blas mejores\b|\btienen más\b)', re.I)
RANK_EN = re.compile(r'(?:\branking\b|\bhighest in\b|\blowest in\b|\bmost protein\b|\bmost fiber\b|\bbest sources?\b|\bhighest-protein\b|\blowest-calorie\b)', re.I)
FORBIDDEN = {
 'es': ('hemos elegido','deliberadamente','tabla principal','tabla anterior','la tabla no pretende','como vimos','otro artículo'),
 'en': ('we chose','deliberately','main table','the table above','as we saw','another article'),
}

def words(html:str)->int:
    text=re.sub(r'<[^>]+>',' ',html)
    return len(re.findall(r"\b[\wÀ-ÿ'-]+\b", unescape(text)))

def load(lang:str):
    by={}; errors=[]
    for f in sorted((ROOT/lang).glob('*.json')):
        try: d=json.loads(f.read_text(encoding='utf-8'))
        except Exception as e: errors.append(f'{f}: invalid JSON: {e}'); continue
        n=int(d.get('article_number') or 0)
        if n in by: errors.append(f'{lang} duplicate article_number {n}')
        by[n]=(f,d)
    return by,errors

def main()->int:
    failures=[]; warnings=[]; corp={}
    for lang in LANGS:
        corp[lang],errs=load(lang); failures+=errs
        nums=set(corp[lang])
        miss=EXPECTED-nums; extra=nums-EXPECTED
        if miss: failures.append(f'{lang}: missing article numbers {sorted(miss)}')
        if extra: failures.append(f'{lang}: unexpected article numbers {sorted(extra)}')

    for n in sorted(EXPECTED & set(corp['es']) & set(corp['en'])):
        es=corp['es'][n][1]; en=corp['en'][n][1]
        if es.get('translation_group') != en.get('translation_group'):
            failures.append(f'{n:03}: translation_group mismatch')
        for lang,d in [('es',es),('en',en)]:
            prefix=f'{lang} {n:03}'
            for key in ('id','title','slug','excerpt','content_html'):
                if not str(d.get(key,'')).strip(): failures.append(f'{prefix}: missing {key}')
            seo=d.get('seo') or {}
            if not str(seo.get('title','')).strip(): failures.append(f'{prefix}: missing seo.title')
            if not str(seo.get('meta_description','')).strip(): failures.append(f'{prefix}: missing seo.meta_description')
            tax=d.get('taxonomy') or {}
            fam=tax.get('food_family','general')
            if fam not in ALLOWED_FAMILY: failures.append(f'{prefix}: unsupported food_family {fam!r}')
            types=tax.get('article_types') or []
            bad=[x for x in types if x not in ALLOWED_TYPES]
            if bad: failures.append(f'{prefix}: unsupported article_types {bad}')
            primary=tax.get('primary_article_type','')
            if primary and primary not in ALLOWED_TYPES: failures.append(f'{prefix}: unsupported primary_article_type {primary!r}')
            if primary and primary not in types: failures.append(f'{prefix}: primary type {primary!r} not present in article_types')

            html=d['content_html']; w=words(html); h2=len(re.findall(r'<h2\b',html,re.I))
            floor=220 if lang=='es' else 200
            if w < floor: failures.append(f'{prefix}: unusually thin article ({w} words)')
            if h2 >= 5 and w/max(h2,1) < 35:
                failures.append(f'{prefix}: catalogue-effect structure ({w} words / {h2} H2s)')
            elif h2 >=5 and w/max(h2,1) <45:
                warnings.append(f'{prefix}: cold-read dense headings ({w} words / {h2} H2s)')

            is_rank='rankings' in types
            title=d.get('title','')
            title_rank=(RANK_ES if lang=='es' else RANK_EN).search(title) is not None
            if (is_rank or title_rank) and '<table' not in html.lower():
                failures.append(f'{prefix}: measurable ranking promise without table')
            if is_rank and '<ol' in html.lower():
                failures.append(f'{prefix}: ranking uses ordered-list presentation instead of table')
            if is_rank and '<table' in html.lower():
                # There should be human framing on both sides of the first table.
                pre,post=html.lower().split('<table',1)
                post=post.split('</table>',1)[1] if '</table>' in post else ''
                if '<p' not in pre[-700:]: warnings.append(f'{prefix}: ranking table lacks nearby framing paragraph')
                if '<p' not in post[:900]: warnings.append(f'{prefix}: ranking table lacks nearby interpretation paragraph')

            low=html.lower()
            for phrase in FORBIDDEN[lang]:
                if phrase in low: failures.append(f'{prefix}: forbidden process/reference phrase {phrase!r}')

            excerpt=re.sub(r'\s+',' ',str(d.get('excerpt','')).strip())
            body_plain=re.sub(r'\s+',' ',unescape(re.sub(r'<[^>]+>',' ',html))).strip()
            if excerpt and excerpt in body_plain:
                warnings.append(f'{prefix}: excerpt appears verbatim in body')

    # Curated internal-link graph.
    p=ROOT/'INTERNAL-LINK-MAP.json'
    if not p.exists(): failures.append('missing INTERNAL-LINK-MAP.json')
    else:
        try: m=json.loads(p.read_text(encoding='utf-8'))
        except Exception as e: failures.append(f'internal link map invalid JSON: {e}'); m={}
        keys={int(k) for k in m if str(k).isdigit()}
        if keys != EXPECTED:
            failures.append(f'internal link map must contain exactly 1..635; missing={sorted(EXPECTED-keys)} extra={sorted(keys-EXPECTED)}')
        inbound=Counter()
        for k,v in m.items():
            if not str(k).isdigit(): continue
            n=int(k)
            if not isinstance(v,list): failures.append(f'link map {n}: targets not a list'); continue
            vals=[int(x) for x in v if isinstance(x,(int,str)) and str(x).isdigit()]
            if len(vals) != len(set(vals)): failures.append(f'link map {n}: duplicate target')
            if n in vals: failures.append(f'link map {n}: self link')
            if not (2 <= len(vals) <= 5): failures.append(f'link map {n}: expected 2-5 curated links, got {len(vals)}')
            for t in vals:
                if t not in EXPECTED: failures.append(f'link map {n}: invalid target {t}')
                else: inbound[t]+=1
        orphan=[n for n in EXPECTED if inbound[n]==0]
        if orphan: failures.append(f'internal-link orphans: {orphan}')

    print(f'ARTICLES_ES={len(corp.get("es",{}))}')
    print(f'ARTICLES_EN={len(corp.get("en",{}))}')
    print(f'FAILURES={len(failures)}')
    print(f'WARNINGS={len(warnings)}')
    if warnings:
        print('\nWARNINGS (manual cold-read prompts):')
        for x in warnings[:120]: print('WARN',x)
        if len(warnings)>120: print(f'... {len(warnings)-120} more warnings')
    if failures:
        print('\nFAILURES:')
        for x in failures: print('FAIL',x)
        return 1
    print('\nSTRUCTURE_AUDIT=PASS')
    print('MANUAL_COLD_READ=STILL_REQUIRED')
    return 0
if __name__=='__main__': raise SystemExit(main())
