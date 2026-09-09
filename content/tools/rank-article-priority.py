#!/usr/bin/env python3
import importlib.util
from pathlib import Path

AUDITOR = Path(__file__).with_name('audit-article-editorial.py')
spec = importlib.util.spec_from_file_location('article_editorial_audit', AUDITOR)
audit = importlib.util.module_from_spec(spec)
try:
    spec.loader.exec_module(audit)
except SystemExit:
    # The current corpus intentionally contains known numbering gaps and quality
    # failures. The auditor has already populated its metrics before exiting.
    pass

rows = []
for num in sorted(audit.all_numbers):
    versions = [audit.version_results.get((lang, num)) for lang in ('es', 'en')]
    versions = [r for r in versions if r is not None]
    if not versions:
        continue
    if all(not r['reasons'] for r in versions):
        continue
    worst_minutes = min(r['reading_minutes'] for r in versions)
    ratios = [r['words_per_h2'] for r in versions if r['words_per_h2'] is not None]
    worst_ratio = min(ratios) if ratios else float('inf')
    min_words = min(r['words'] for r in versions)
    rows.append((worst_minutes, worst_ratio, min_words, num, versions))

rows.sort(key=lambda row: (row[0], row[1], row[2], row[3]))
print('PRIORITY_RANKING_BEGIN')
for pos, (minutes, ratio, min_words, num, versions) in enumerate(rows, 1):
    metrics = []
    for r in versions:
        ratio_text = 'NA' if r['words_per_h2'] is None else f"{r['words_per_h2']:.1f}"
        codes = '+'.join(reason['code'] for reason in r['reasons']) or 'pass'
        metrics.append(
            f"{r['language']}:min={r['reading_minutes']},words={r['words']},h2={r['h2']},wph2={ratio_text},reasons={codes}"
        )
    ratio_text = 'NA' if ratio == float('inf') else f'{ratio:.1f}'
    print(f"RANK={pos};ID={num:03d};PRIORITY_MIN={minutes};WORST_WPH2={ratio_text};MIN_WORDS={min_words};" + ';'.join(metrics))
print('PRIORITY_RANKING_END')
