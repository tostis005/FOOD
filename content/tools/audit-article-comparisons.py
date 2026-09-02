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


def text_blocks(content_html):
    for block in BLOCK_RE.findall(content_html):
        text = html.unescape(TAG_RE.sub(' ', block))
        text = re.sub(r'\s+', ' ', text).strip()
        if text:
            yield text


def looks_like_comparison(text):
    if not COMPARATIVE_RE.search(text) or not MEASURABLE_RE.search(text):
        return False
    # Two explicit numeric values normally mean both sides are quantified.
    nums = NUMBER_RE.findall(text)
    if len(nums) >= 2:
        return False
    # Rankings/headings can state a comparative concept without making a binary claim.
    if len(text) < 90 and re.search(r'^(ranking|los |las |the |¿|what |which )', text, re.I):
        return False
    return True


issues = []
for path in sorted(ROOT.glob('*/*.json')):
    data = json.loads(path.read_text(encoding='utf-8'))
    content = data.get('content_html', '')
    for text in text_blocks(content):
        if looks_like_comparison(text):
            issues.append((str(path), text))

if issues:
    print(f'COMPARISON_AUDIT_WARNINGS={len(issues)}')
    for path, text in issues:
        print(f'[{path}] {text}')
    # Warning-only by design: human review decides whether a numerical comparison is meaningful.
else:
    print('COMPARISON_AUDIT_WARNINGS=0')
