#!/usr/bin/env python3
import html
import json
import re
import sys
from pathlib import Path

ROOT = Path(sys.argv[1] if len(sys.argv) > 1 else 'content/articles')

COMPARATIVE_RE = re.compile(
    r'\b('
    r'm[aá]s|menos|mayor|menor|superior|inferior|doble|mitad|similar(?:es)?|'
    r'much[oa]s?\s+m[aá]s|much[oa]s?\s+menos|'
    r'more|less|higher|lower|greater|smaller|twice|double|half|similar|'
    r'much\s+more|much\s+less|far\s+more|far\s+less'
    r')\b', re.I
)
MEASURABLE_RE = re.compile(
    r'\b('
    r'prote[ií]na|protein|fibra|fiber|grasa|fat|calor[ií]as?|kcal|energ[ií]a|energy|'
    r'carbohidratos?|carbohydrates?|az[uú]car|sugar|sodio|sodium|sal|salt|hierro|iron|'
    r'calcio|calcium|potasio|potassium|omega-?3|colesterol|cholesterol|'
    r'tiempo|time|d[ií]as?|days?|horas?|hours?|temperatura|temperature|°c|°f|'
    r'por\s+100\s*g|per\s+100\s*g|raci[oó]n|serving|porci[oó]n|portion|'
    r'cal[oó]ric[oa]|caloric|magro|lean|concentrad[oa]|concentrated|dense|densidad|density'
    r')\b', re.I
)
NUMBER_RE = re.compile(r'(?<![A-Za-z])\d+(?:[.,]\d+)?\s*(?:%|g|mg|µg|mcg|kcal|cal|°\s*[CF]|d[ií]as?|days?|h|horas?|hours?)?', re.I)
TAG_RE = re.compile(r'<[^>]+>')
BLOCK_RE = re.compile(r'<(?:p|li|td|h2|h3)\b[^>]*>(.*?)</(?:p|li|td|h2|h3)>', re.I | re.S)

# Phrases that make the published article sound like an author explaining the
# drafting process rather than answering the reader.
PROCESS_RE = re.compile(
    r'('
    r'\b(?:hemos|se ha|se han|hemos decidido|decidimos)\s+(?:elegido|escogido|usado|utilizado|redondeado|excluido|ordenado)\b|'
    r'\b(?:deliberadamente|intencionadamente)\b|'
    r'\b(?:merece|merecer[ií]a)\s+(?:un|otro)\s+art[ií]culo\b|'
    r'\b(?:lo veremos|se ver[aá]|se tratar[aá]|se desarrolla|se desarrollar[aá])\s+(?:en|mejor en)\s+(?:otro|un)\s+art[ií]culo\b|'
    r'\b(?:esta|este)\s+(?:tabla|ranking|art[ií]culo)\s+(?:no pretende|pretende)\b|'
    r'\b(?:we|this article|this ranking|this table)\s+(?:chose|use|used|exclude|excluded|round|rounded|do not intend|does not intend)\b|'
    r'\b(?:deliberately|intentionally)\s+(?:exclude|excluded|round|rounded|use|used)\b|'
    r'\b(?:deserves|would deserve)\s+(?:a|its own)\s+(?:separate\s+)?article\b|'
    r'\b(?:we will|we’ll|will be)\s+(?:cover|covered|discuss|discussed)\s+(?:elsewhere|in another article)\b'
    r')', re.I
)

# Database-style preparation labels that are usually too technical for display.
TECHNICAL_PREP_RE = re.compile(
    r'\b(cocinado con calor seco|cooked with dry heat|cooked, dry heat|heat-treated by dry heat)\b', re.I
)


def text_blocks(content_html):
    for block in BLOCK_RE.findall(content_html):
        text = html.unescape(TAG_RE.sub(' ', block))
        text = re.sub(r'\s+', ' ', text).strip()
        if text:
            yield text


def looks_like_comparison(text):
    if not COMPARATIVE_RE.search(text) or not MEASURABLE_RE.search(text):
        return False
    nums = NUMBER_RE.findall(text)
    if len(nums) >= 2:
        return False
    if len(text) < 90 and re.search(r'^(ranking|los |las |the |¿|what |which )', text, re.I):
        return False
    return True

comparison_issues = []
process_issues = []
prep_issues = []

for path in sorted(ROOT.glob('*/*.json')):
    data = json.loads(path.read_text(encoding='utf-8'))
    content = data.get('content_html', '')
    for text in text_blocks(content):
        if looks_like_comparison(text):
            comparison_issues.append((str(path), text))
        if PROCESS_RE.search(text):
            process_issues.append((str(path), text))
        if TECHNICAL_PREP_RE.search(text):
            prep_issues.append((str(path), text))

print(f'COMPARISON_AUDIT_WARNINGS={len(comparison_issues)}')
for path, text in comparison_issues:
    print(f'[comparison][{path}] {text}')

print(f'EDITORIAL_PROCESS_WARNINGS={len(process_issues)}')
for path, text in process_issues:
    print(f'[process][{path}] {text}')

print(f'TECHNICAL_PREPARATION_WARNINGS={len(prep_issues)}')
for path, text in prep_issues:
    print(f'[preparation][{path}] {text}')

# Warning-only: a human/editorial pass decides whether each flagged sentence
# genuinely needs a numerical comparison or wording change.
