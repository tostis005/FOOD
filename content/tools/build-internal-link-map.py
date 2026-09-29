#!/usr/bin/env python3
from __future__ import annotations

import argparse
import json
import math
import re
import unicodedata
from collections import Counter
from pathlib import Path

ROOT = Path('content/articles')
MAP_PATH = ROOT / 'INTERNAL-LINK-MAP.json'
TAXONOMIES_PATH = ROOT / 'taxonomies.json'
CONSOLIDATIONS_PATH = ROOT / 'SEO-CONSOLIDATIONS.json'
LEGACY_CUTOFF = 635
TARGET_LINKS = 4
MAX_LINKS = 5

STOPWORDS = {
    'a','al','algo','como','con','cual','cuando','de','del','desde','el','en','es',
    'esta','este','esto','ha','hay','la','las','lo','los','mas','me','menos','mi','no',
    'o','para','pero','por','que','se','si','sin','sobre','su','sus','un','una','uno',
    'unos','unas','vs','y','ya',
    'an','and','are','as','at','be','best','better','by','can','compared','difference',
    'differences','do','does','for','from','guide','how','i','if','in','into','is','it',
    'of','on','or','than','the','to','versus','what','when','where','which','who','why',
    'with','you','your',
    'article','articulo','articulos','food','foods','alimento','alimentos','guia',
    'beneficio','beneficios','effect','effects','efecto','efectos','health','salud',
    'nutrition','nutritional','nutricion','nutricional','product','products','producto',
    'productos','use','used','using','usar','usa','utiliza','utilizar',
}

BASE_TYPE_ALIASES = {
    'comparativas': 'comparisons',
    'seguridad-alimentaria': 'food-safety',
    'conservacion-almacenamiento': 'storage',
    'congelacion-descongelacion': 'freezing-thawing',
    'nutricion-composicion': 'nutrition-composition',
    'health-regular-consumption': 'health-daily-consumption',
    'salud-consumo-habitual': 'health-daily-consumption',
    'cooking-food-science': 'cooking-science',
    'cocina-ciencia-alimentos': 'cooking-science',
    'preparation-cooking-techniques': 'cooking-techniques',
    'preparacion-tecnicas-cocina': 'cooking-techniques',
    'compra-calidad-maduracion': 'buying-quality-ripeness',
    'procesamiento-produccion-elaboracion': 'processing-production',
    'rankings-best-sources': 'rankings',
    'rankings-mejores-fuentes': 'rankings',
    'conceptos-nutricion': 'nutrition-concepts',
    'myths-common-questions': 'myths-faq',
    'mitos-preguntas-frecuentes': 'myths-faq',
    'etiquetado-ingredientes': 'food-labels',
}

def load_taxonomy_aliases():
    try:
        data = json.loads(TAXONOMIES_PATH.read_text(encoding='utf-8'))
    except Exception:
        return {}, {}
    families = {str(k): str(v) for k, v in (data.get('food_family_aliases') or {}).items()}
    types = {str(k): str(v) for k, v in (data.get('article_type_aliases') or {}).items()}
    return families, types

FAMILY_ALIASES, JSON_TYPE_ALIASES = load_taxonomy_aliases()

def load_consolidations():
    try:
        data = json.loads(CONSOLIDATIONS_PATH.read_text(encoding='utf-8'))
    except Exception:
        return {}
    return {int(k): int(v) for k, v in data.items() if str(k).isdigit() and int(v) > 0 and int(k) != int(v)}

CONSOLIDATIONS = load_consolidations()

def canonical_number(number: int) -> int:
    number = int(number)
    seen = set()
    while number in CONSOLIDATIONS and number not in seen:
        seen.add(number)
        number = int(CONSOLIDATIONS[number])
    return number

def norm(text: str) -> str:
    text = unicodedata.normalize('NFKD', text or '')
    text = ''.join(ch for ch in text if not unicodedata.combining(ch))
    return text.lower()

def stem(token: str) -> str:
    if len(token) > 6 and token.endswith('ies'):
        return token[:-3] + 'y'
    if len(token) > 5 and token.endswith('s') and not token.endswith(('ss', 'us', 'is')):
        return token[:-1]
    return token

def tokens(text: str) -> list[str]:
    parts = re.findall(r'[a-z0-9]+', norm(text))
    out = []
    for part in parts:
        part = stem(part)
        if len(part) < 3 or part in STOPWORDS or part.isdigit():
            continue
        out.append(part)
    return out

def canonical_type(value: str) -> str:
    value = norm(value).replace('_', '-')
    value = JSON_TYPE_ALIASES.get(value, value)
    return BASE_TYPE_ALIASES.get(value, value)

def canonical_family(value: str) -> str:
    value = norm(value).replace('_', '-')
    return FAMILY_ALIASES.get(value, value)

def load_articles(root: Path) -> dict[int, dict]:
    by_number: dict[int, dict] = {}
    for lang in ('es', 'en'):
        for path in sorted((root / lang).glob('*.json')):
            try:
                data = json.loads(path.read_text(encoding='utf-8'))
            except Exception:
                continue
            number = data.get('article_number')
            if not isinstance(number, int) or number < 1:
                continue
            row = by_number.setdefault(number, {'number': number, 'es': None, 'en': None})
            row[lang] = data
    if not by_number:
        raise SystemExit('No article JSON files found')
    numbers = sorted(by_number)
    expected = list(range(1, max(numbers) + 1))
    if numbers != expected:
        missing = sorted(set(expected) - set(numbers))
        raise SystemExit(f'Article number gaps: {missing}')
    for number, row in by_number.items():
        if not row.get('es') or not row.get('en'):
            raise SystemExit(f'Article {number} is missing one language version')
    return by_number

def article_features(article: dict) -> dict:
    es = article['es']
    en = article['en']
    family_es = str((es.get('taxonomy') or {}).get('food_family') or '')
    family_en = str((en.get('taxonomy') or {}).get('food_family') or '')
    family = family_es or family_en
    types = set()
    primary = set()
    for data in (es, en):
        tax = data.get('taxonomy') or {}
        for item in tax.get('article_types') or []:
            types.add(canonical_type(str(item)))
        if tax.get('primary_article_type'):
            primary.add(canonical_type(str(tax['primary_article_type'])))
    title_text = ' '.join([
        str(es.get('title') or ''), str(en.get('title') or ''),
        str((es.get('seo') or {}).get('title') or ''),
        str((en.get('seo') or {}).get('title') or ''),
    ])
    intent_text = ' '.join([
        str((es.get('seo') or {}).get('search_intent') or ''),
        str((en.get('seo') or {}).get('search_intent') or ''),
    ])
    excerpt_text = ' '.join([str(es.get('excerpt') or ''), str(en.get('excerpt') or '')])
    title_tokens = tokens(title_text)
    intent_tokens = tokens(intent_text)
    excerpt_tokens = tokens(excerpt_text)
    tf = Counter()
    for token in title_tokens:
        tf[token] += 3.0
    for token in intent_tokens:
        tf[token] += 2.0
    for token in excerpt_tokens:
        tf[token] += 0.45
    return {
        'family': canonical_family(family),
        'types': types,
        'primary': primary,
        'tf': tf,
        'title_tokens': set(title_tokens),
        'intent_tokens': set(intent_tokens),
    }

def build_vectors(features: dict[int, dict]) -> dict[int, dict[str, float]]:
    df = Counter()
    for feature in features.values():
        for token in feature['tf']:
            df[token] += 1
    n_docs = len(features)
    idf = {token: math.log((n_docs + 1) / (count + 1)) + 1.0 for token, count in df.items()}
    vectors = {}
    for number, feature in features.items():
        vectors[number] = {token: weight * idf[token] for token, weight in feature['tf'].items()}
    return vectors

def cosine(a: dict[str, float], b: dict[str, float]) -> float:
    if not a or not b:
        return 0.0
    shared = set(a) & set(b)
    dot = sum(a[t] * b[t] for t in shared)
    if dot <= 0:
        return 0.0
    norm_a = math.sqrt(sum(v * v for v in a.values()))
    norm_b = math.sqrt(sum(v * v for v in b.values()))
    return dot / (norm_a * norm_b) if norm_a and norm_b else 0.0

def pair_score(a_num: int, b_num: int, features, vectors):
    a = features[a_num]
    b = features[b_num]
    family_match = bool(a['family'] and a['family'] == b['family'])
    type_overlap = len(a['types'] & b['types'])
    primary_match = bool(a['primary'] & b['primary'])
    text_similarity = cosine(vectors[a_num], vectors[b_num])
    title_overlap = len(a['title_tokens'] & b['title_tokens'])
    intent_overlap = len(a['intent_tokens'] & b['intent_tokens'])
    score = text_similarity * 16.0
    if family_match:
        score += 1.75
    score += min(2.0, type_overlap * 0.65)
    if primary_match:
        score += 0.75
    score += min(3.0, title_overlap * 1.25)
    score += min(1.5, intent_overlap * 0.30)
    if abs(a_num - b_num) <= 12 and (text_similarity >= 0.08 or title_overlap >= 1):
        score += 0.05
    evidence = {
        'family_match': family_match,
        'type_overlap': type_overlap,
        'primary_match': primary_match,
        'text_similarity': text_similarity,
        'title_overlap': title_overlap,
        'intent_overlap': intent_overlap,
    }
    return score, evidence

def eligible(evidence: dict) -> bool:
    semantic = (
        evidence['text_similarity'] >= 0.075
        or evidence['title_overlap'] >= 1
        or evidence['intent_overlap'] >= 2
    )
    structural = (
        evidence['family_match']
        or evidence['type_overlap'] >= 1
        or evidence['text_similarity'] >= 0.14
        or evidence['title_overlap'] >= 2
    )
    return semantic and structural

def relaxed_eligible(evidence: dict) -> bool:
    return (
        (evidence['text_similarity'] >= 0.055 and (
            evidence['family_match'] or evidence['primary_match'] or evidence['type_overlap'] >= 1
        ))
        or (evidence['title_overlap'] >= 1 and (
            evidence['family_match'] or evidence['type_overlap'] >= 1
        ))
    )

def rank_candidates(number: int, numbers: list[int], features, vectors):
    strict = []
    relaxed = []
    for other in numbers:
        if other == number or other in CONSOLIDATIONS:
            continue
        score, evidence = pair_score(number, other, features, vectors)
        row = (other, score, evidence)
        if eligible(evidence):
            strict.append(row)
        elif relaxed_eligible(evidence):
            relaxed.append(row)

    key = lambda row: (-row[1], -row[2]['text_similarity'], -row[2]['title_overlap'], abs(number - row[0]), row[0])
    strict.sort(key=key)
    relaxed.sort(key=key)

    if len(strict) < 6:
        seen = {row[0] for row in strict}
        strict.extend(row for row in relaxed if row[0] not in seen)
    return strict

def select_links(number: int, ranked, features) -> list[int]:
    chosen = []
    for other, score, evidence in ranked:
        if other in chosen:
            continue
        chosen.append(other)
        if len(chosen) >= TARGET_LINKS:
            break
    return chosen

def generate_map(root: Path, existing_map: Path):
    articles = load_articles(root)
    features = {number: article_features(article) for number, article in articles.items()}
    vectors = build_vectors(features)
    numbers = sorted(articles)
    active_numbers = [number for number in numbers if number not in CONSOLIDATIONS]
    max_number = max(numbers)
    current = json.loads(existing_map.read_text(encoding='utf-8')) if existing_map.exists() else {}
    result = {}
    for number in range(1, min(LEGACY_CUTOFF, max_number) + 1):
        values = current.get(str(number), [])
        if isinstance(values, list):
            result[str(number)] = [int(v) for v in values if isinstance(v, int)]
    for number in range(LEGACY_CUTOFF + 1, max_number + 1):
        if number in CONSOLIDATIONS:
            continue
        ranked = rank_candidates(number, active_numbers, features, vectors)
        links = select_links(number, ranked, features)
        if len(links) < 3:
            raise SystemExit(f'Article {number} has too few safe internal-link candidates: {links}')
        result[str(number)] = links
    old_to_new = 0
    new_numbers = [n for n in active_numbers if n > LEGACY_CUTOFF]
    for number in range(1, min(LEGACY_CUTOFF, max_number) + 1):
        links = result.get(str(number), [])
        if len(links) >= 4:
            continue
        candidates = []
        for other in new_numbers:
            score, evidence = pair_score(number, other, features, vectors)
            if not evidence['family_match']:
                continue
            if evidence['type_overlap'] < 1 and not evidence['primary_match']:
                continue
            if evidence['text_similarity'] < 0.16:
                continue
            if evidence['title_overlap'] < 1 and evidence['intent_overlap'] < 3:
                continue
            if score < 4.8:
                continue
            candidates.append((other, score, evidence))
        candidates.sort(key=lambda row: (-row[1], -row[2]['text_similarity'], row[0]))
        if candidates:
            links = [*links, candidates[0][0]]
            result[str(number)] = list(dict.fromkeys(links))[:MAX_LINKS]
            old_to_new += 1
    # Rewrite any historical targets that now consolidate elsewhere, then
    # supplement a source if deduplication leaves it with fewer than three links.
    for source in active_numbers:
        key = str(source)
        links = result.get(key, [])
        normalized = []
        for target in links:
            target = canonical_number(target)
            if target == source or target in normalized or target not in articles or target in CONSOLIDATIONS:
                continue
            normalized.append(target)

        if len(normalized) < 3:
            ranked = rank_candidates(source, active_numbers, features, vectors)
            for target, score, evidence in ranked:
                target = canonical_number(target)
                if target == source or target in normalized or target in CONSOLIDATIONS:
                    continue
                normalized.append(target)
                if len(normalized) >= 3:
                    break
        result[key] = normalized[:MAX_LINKS]

    # Consolidated source keys remain in the map for structural continuity, but
    # mirror their canonical target. Runtime requests redirect before rendering.
    for source, target in CONSOLIDATIONS.items():
        canonical = canonical_number(target)
        if str(canonical) in result:
            result[str(source)] = list(result[str(canonical)])

    inbound = Counter()
    for source in new_numbers:
        for target in result.get(str(source), []):
            if target > LEGACY_CUTOFF:
                inbound[target] += 1
    repaired = 0
    for target in new_numbers:
        if inbound[target] > 0:
            continue

        # Reciprocal repair only: the target already selected these pages among
        # its four closest semantic neighbours, so adding the reverse edge does
        # not invent a new topical relationship.
        outgoing = result.get(str(target), [])
        sources = [source for source in outgoing if source > LEGACY_CUTOFF]
        sources += [source for source in outgoing if source <= LEGACY_CUTOFF]

        for source in sources:
            links = result.get(str(source), [])
            if target in links:
                inbound[target] += 1
                break
            if len(links) >= MAX_LINKS:
                continue
            links.append(target)
            result[str(source)] = links
            inbound[target] += 1
            repaired += 1
            break

    zero_inbound = [target for target in new_numbers if inbound[target] == 0]
    invalid = []
    for number in numbers:
        links = result.get(str(number), [])
        if len(links) < 3 or len(links) > MAX_LINKS:
            invalid.append(f'{number}: link count {len(links)}')
        seen = set()
        for target in links:
            if target == number or target not in articles or target in seen:
                invalid.append(f'{number}: invalid target {target}')
            seen.add(target)
    if invalid:
        raise SystemExit('Invalid internal-link map:\n' + '\n'.join(invalid[:50]))
    stats = {
        'articles': len(numbers),
        'legacy_preserved': min(LEGACY_CUTOFF, max_number),
        'new_generated': max(0, max_number - LEGACY_CUTOFF),
        'old_to_new_added': old_to_new,
        'inbound_repairs': repaired,
        'new_inbound_zero': len(zero_inbound),
        'new_inbound_min': min((inbound[n] for n in new_numbers), default=0),
        'new_inbound_avg': sum(inbound[n] for n in new_numbers) / len(new_numbers) if new_numbers else 0.0,
    }
    return result, stats

def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument('--check', action='store_true')
    parser.add_argument('--root', default=str(ROOT))
    parser.add_argument('--map', dest='map_path', default=str(MAP_PATH))
    args = parser.parse_args()
    root = Path(args.root)
    map_path = Path(args.map_path)
    if args.check:
        generated, stats = generate_map(root, map_path)
        payload = json.dumps(generated, ensure_ascii=False, indent=2) + '\n'
        print('INTERNAL_LINK_MAP_AUDIT')
        for key, value in stats.items():
            print(f'{key.upper()}={value:.2f}' if isinstance(value, float) else f'{key.upper()}={value}')
        current = map_path.read_text(encoding='utf-8') if map_path.exists() else ''
        if current != payload:
            print('INTERNAL_LINK_MAP=STALE')
            return 1
        print('INTERNAL_LINK_MAP=PASS')
        return 0

    # The legacy portion intentionally uses the previous curated map as input.
    # Iterate until that stateful seed reaches a fixed point so a subsequent
    # --check is guaranteed to reproduce the exact same graph.
    max_passes = 6
    stats = {}
    for convergence_pass in range(1, max_passes + 1):
        generated, stats = generate_map(root, map_path)
        payload = json.dumps(generated, ensure_ascii=False, indent=2) + '\n'
        current = map_path.read_text(encoding='utf-8') if map_path.exists() else ''
        if current == payload:
            break
        map_path.write_text(payload, encoding='utf-8')
    else:
        raise SystemExit(f'Internal-link map did not converge after {max_passes} passes')

    print('INTERNAL_LINK_MAP_AUDIT')
    for key, value in stats.items():
        print(f'{key.upper()}={value:.2f}' if isinstance(value, float) else f'{key.upper()}={value}')
    print(f'CONVERGENCE_PASSES={convergence_pass}')
    print(f'WROTE={map_path}')
    return 0

if __name__ == '__main__':
    raise SystemExit(main())
