#!/usr/bin/env python3
from __future__ import annotations

import argparse
import concurrent.futures
import json
import socket
import ssl
import time
import urllib.error
import urllib.parse
import urllib.request
from collections import defaultdict
from pathlib import Path

ROOT = Path("content/articles")
OUT_JSON = Path("content/audits/SOURCE-LINK-AUDIT.json")
OUT_MD = Path("content/audits/SOURCE-LINK-AUDIT.md")
USER_AGENT = "Quinnoa source-link audit/1.0 (+https://www.quinnoa.com/)"

def collect_sources(root: Path):
    urls = defaultdict(lambda: {"articles": set(), "names": set()})
    invalid = []
    for lang in ("es", "en"):
        for path in sorted((root / lang).glob("*.json")):
            try:
                data = json.loads(path.read_text(encoding="utf-8"))
            except Exception:
                continue
            number = data.get("article_number")
            for source in data.get("sources") or []:
                if not isinstance(source, dict):
                    continue
                raw = str(source.get("url") or "").strip()
                name = str(source.get("name") or "").strip()
                if not raw:
                    invalid.append({"article": number, "language": lang, "reason": "missing_url", "name": name})
                    continue
                parsed = urllib.parse.urlsplit(raw)
                if parsed.scheme not in {"http", "https"} or not parsed.netloc or any(ch.isspace() for ch in raw):
                    invalid.append({"article": number, "language": lang, "reason": "invalid_url", "url": raw, "name": name})
                    continue
                key = urllib.parse.urlunsplit((parsed.scheme.lower(), parsed.netloc.lower(), parsed.path or "/", parsed.query, ""))
                urls[key]["articles"].add(f"{number}:{lang}")
                if name:
                    urls[key]["names"].add(name)
    return urls, invalid

def request_once(url: str, method: str, timeout: int):
    request = urllib.request.Request(
        url,
        method=method,
        headers={
            "User-Agent": USER_AGENT,
            "Accept": "text/html,application/xhtml+xml,application/pdf,*/*;q=0.5",
        },
    )
    with urllib.request.urlopen(request, timeout=timeout) as response:
        return int(getattr(response, "status", 200)), response.geturl()

def check_url(url: str, timeout: int):
    started = time.monotonic()
    try:
        try:
            status, final_url = request_once(url, "HEAD", timeout)
        except urllib.error.HTTPError as exc:
            # Many legitimate publishers reject HEAD. Retry with a tiny GET
            # unless the response is already a definitive missing-page signal.
            if exc.code in {404, 410}:
                status, final_url = exc.code, exc.geturl()
            else:
                request = urllib.request.Request(
                    url,
                    method="GET",
                    headers={
                        "User-Agent": USER_AGENT,
                        "Range": "bytes=0-2047",
                        "Accept": "text/html,application/xhtml+xml,application/pdf,*/*;q=0.5",
                    },
                )
                try:
                    with urllib.request.urlopen(request, timeout=timeout) as response:
                        status, final_url = int(getattr(response, "status", 200)), response.geturl()
                except urllib.error.HTTPError as get_exc:
                    status, final_url = get_exc.code, get_exc.geturl()
        category = "ok"
        if status in {404, 410}:
            category = "broken"
        elif status >= 500:
            category = "server_error"
        elif status in {401, 403, 405, 429}:
            category = "blocked"
        elif status >= 400:
            category = "http_error"
        return {
            "url": url,
            "status": status,
            "category": category,
            "final_url": final_url,
            "elapsed_ms": round((time.monotonic() - started) * 1000),
        }
    except (urllib.error.URLError, TimeoutError, socket.timeout, ssl.SSLError, OSError) as exc:
        return {
            "url": url,
            "status": None,
            "category": "network_error",
            "error": str(exc)[:300],
            "elapsed_ms": round((time.monotonic() - started) * 1000),
        }

def main():
    parser = argparse.ArgumentParser()
    parser.add_argument("--root", default=str(ROOT))
    parser.add_argument("--live", action="store_true")
    parser.add_argument("--workers", type=int, default=12)
    parser.add_argument("--timeout", type=int, default=12)
    parser.add_argument("--json-out", default=str(OUT_JSON))
    parser.add_argument("--md-out", default=str(OUT_MD))
    args = parser.parse_args()

    urls, invalid = collect_sources(Path(args.root))
    checks = []
    if args.live:
        with concurrent.futures.ThreadPoolExecutor(max_workers=max(1, min(args.workers, 20))) as pool:
            futures = [pool.submit(check_url, url, args.timeout) for url in sorted(urls)]
            for future in concurrent.futures.as_completed(futures):
                checks.append(future.result())
        checks.sort(key=lambda row: row["url"])

    for row in checks:
        meta = urls.get(row["url"], {})
        row["articles"] = sorted(meta.get("articles", []))
        row["names"] = sorted(meta.get("names", []))

    counts = defaultdict(int)
    for row in checks:
        counts[row["category"]] += 1

    report = {
        "unique_urls": len(urls),
        "invalid_source_entries": invalid,
        "live_checked": bool(args.live),
        "status_counts": dict(sorted(counts.items())),
        "checks": checks,
    }
    out_json = Path(args.json_out)
    out_md = Path(args.md_out)
    out_json.parent.mkdir(parents=True, exist_ok=True)
    out_json.write_text(json.dumps(report, ensure_ascii=False, indent=2) + "\n", encoding="utf-8")

    lines = [
        "# Quinnoa source-link audit",
        "",
        f"- Unique source URLs: **{len(urls)}**",
        f"- Invalid source entries: **{len(invalid)}**",
        f"- Live HTTP check: **{'yes' if args.live else 'no'}**",
    ]
    if args.live:
        lines.append("- HTTP status summary: " + ", ".join(f"**{k}={v}**" for k, v in sorted(counts.items())))
        broken = [row for row in checks if row["category"] in {"broken", "http_error", "server_error", "network_error"}]
        lines += ["", "## URLs to review", ""]
        if not broken:
            lines.append("No broken or unreachable source URLs were detected.")
        else:
            lines += ["| Status | URL | Articles |", "| --- | --- | --- |"]
            for row in broken[:250]:
                status = row.get("status") or row.get("category")
                articles = ", ".join(row.get("articles") or [])
                lines.append(f"| {status} | {row['url'].replace('|', '%7C')} | {articles} |")
    out_md.write_text("\n".join(lines) + "\n", encoding="utf-8")

    print("SOURCE_LINK_AUDIT")
    print(f"UNIQUE_URLS={len(urls)}")
    print(f"INVALID_SOURCE_ENTRIES={len(invalid)}")
    if args.live:
        for key, value in sorted(counts.items()):
            print(f"{key.upper()}={value}")
    print(f"JSON_REPORT={out_json}")
    print(f"MD_REPORT={out_md}")

    # Syntax/data errors are deterministic and should fail CI. Network failures
    # are review signals only because publishers can rate-limit GitHub runners.
    return 1 if invalid else 0

if __name__ == "__main__":
    raise SystemExit(main())
