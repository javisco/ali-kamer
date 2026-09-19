#!/usr/bin/env python3
"""Replace legacy Ali-Kamer colors with the official teal/orange/slate palette."""

from __future__ import annotations

import re
from pathlib import Path

ROOT = Path("/home/javisco-jj/ali-kamer")
TARGETS = [
    ROOT / "resources" / "views",
    ROOT / "resources" / "js",
    ROOT / "app",
]
EXTENSIONS = {".php", ".css", ".js", ".blade.php"}

# Cameroon flag — keep as-is
FLAG_HEX = {"00843d", "fcd116", "ce1126"}

# Arbitrary-value hex → Tailwind token
HEX_TO_TOKEN = {
    "016837": "primary-600",
    "01522b": "primary-700",
    "013d20": "primary-800",
    "0a542d": "primary-700",
    "014d2c": "primary-700",
    "006837": "primary-600",
    "0a1b12": "slate-900",
    "f9a01b": "accent-500",
    "e30613": "danger",
    "f7f7f2": "slate-50",
    "fafaf7": "slate-50",
    "faf9f6": "slate-50",
    "b87500": "accent-600",
    "9a6400": "accent-600",
    "8a5800": "accent-600",
    "946000": "accent-600",
}

# Remaining raw hex (SVG fills, inline CSS)
HEX_TO_HEX = {
    "016837": "0D9488",
    "01522b": "0F766E",
    "013d20": "115E59",
    "0a542d": "0F766E",
    "014d2c": "0F766E",
    "006837": "0D9488",
    "0a1b12": "0F172A",
    "f9a01b": "F97316",
    "e30613": "EF4444",
    "f7f7f2": "F8FAFC",
    "fafaf7": "F8FAFC",
    "faf9f6": "F8FAFC",
    "b87500": "EA580C",
    "9a6400": "EA580C",
    "8a5800": "EA580C",
    "946000": "EA580C",
}

# Longest first to avoid partial collisions
SCALE_MAPS: list[tuple[str, list[tuple[str, str]]]] = [
    (
        "emerald",
        [
            ("emerald-950", "success-800"),
            ("emerald-900", "success-800"),
            ("emerald-800", "success-800"),
            ("emerald-700", "success-700"),
            ("emerald-600", "success"),
            ("emerald-500", "success"),
            ("emerald-400", "success"),
            ("emerald-300", "success-200"),
            ("emerald-200", "success-200"),
            ("emerald-100", "success-100"),
            ("emerald-50", "success-50"),
        ],
    ),
    (
        "green",
        [
            ("green-900", "success-800"),
            ("green-800", "success-800"),
            ("green-700", "success-700"),
            ("green-600", "success"),
            ("green-500", "success"),
            ("green-400", "success"),
            ("green-300", "success-200"),
            ("green-200", "success-200"),
            ("green-100", "success-100"),
            ("green-50", "success-50"),
        ],
    ),
    (
        "red",
        [
            ("red-950", "danger-800"),
            ("red-900", "danger-800"),
            ("red-800", "danger-800"),
            ("red-700", "danger-700"),
            ("red-600", "danger"),
            ("red-500", "danger"),
            ("red-400", "danger"),
            ("red-300", "danger-200"),
            ("red-200", "danger-200"),
            ("red-100", "danger-100"),
            ("red-50", "danger-50"),
        ],
    ),
    (
        "amber",
        [
            ("amber-900", "warning-800"),
            ("amber-800", "warning-800"),
            ("amber-700", "warning-700"),
            ("amber-600", "warning-600"),
            ("amber-500", "warning"),
            ("amber-400", "warning"),
            ("amber-300", "warning-200"),
            ("amber-200", "warning-200"),
            ("amber-100", "warning-100"),
            ("amber-50", "warning-50"),
        ],
    ),
    (
        "yellow",
        [
            ("yellow-900", "warning-800"),
            ("yellow-800", "warning-800"),
            ("yellow-700", "warning-700"),
            ("yellow-600", "warning"),
            ("yellow-500", "warning"),
            ("yellow-400", "warning"),
            ("yellow-300", "warning"),
            ("yellow-200", "warning-200"),
            ("yellow-100", "warning-100"),
            ("yellow-50", "warning-50"),
        ],
    ),
    (
        "orange",
        [
            ("orange-900", "accent-700"),
            ("orange-800", "accent-700"),
            ("orange-700", "accent-700"),
            ("orange-600", "accent-600"),
            ("orange-500", "accent-500"),
            ("orange-400", "accent-500"),
            ("orange-300", "accent-200"),
            ("orange-200", "accent-200"),
            ("orange-100", "accent-100"),
            ("orange-50", "accent-50"),
        ],
    ),
    (
        "pink",
        [
            ("pink-900", "accent-700"),
            ("pink-800", "accent-700"),
            ("pink-700", "accent-700"),
            ("pink-600", "accent-600"),
            ("pink-500", "accent-500"),
            ("pink-400", "accent-500"),
            ("pink-300", "accent-200"),
            ("pink-200", "accent-200"),
            ("pink-100", "accent-100"),
            ("pink-50", "accent-50"),
        ],
    ),
    (
        "fuchsia",
        [
            ("fuchsia-900", "accent-700"),
            ("fuchsia-800", "accent-700"),
            ("fuchsia-700", "accent-700"),
            ("fuchsia-600", "accent-600"),
            ("fuchsia-500", "accent-500"),
            ("fuchsia-400", "accent-500"),
            ("fuchsia-300", "accent-200"),
            ("fuchsia-200", "accent-200"),
            ("fuchsia-100", "accent-100"),
            ("fuchsia-50", "accent-50"),
        ],
    ),
]

PREFIX_REPLACEMENTS = [
    ("gray-", "slate-"),
    ("neutral-", "slate-"),
    ("zinc-", "slate-"),
    ("blue-", "primary-"),
    ("indigo-", "primary-"),
    ("sky-", "primary-"),
    ("cyan-", "primary-"),
    ("violet-", "primary-"),
    ("purple-", "primary-"),
    ("lime-", "success-"),
    ("teal-", "primary-"),
]


def iter_files() -> list[Path]:
    files: list[Path] = []
    for base in TARGETS:
        if not base.exists():
            continue
        for path in base.rglob("*"):
            if not path.is_file():
                continue
            if path.suffix not in {".php", ".css", ".js"} and not path.name.endswith(".blade.php"):
                continue
            if "migrate_colors.py" in str(path):
                continue
            files.append(path)
    return files


def replace_arbitrary_hex(content: str) -> str:
    def repl(match: re.Match[str]) -> str:
        hexv = match.group(1).lower()
        if hexv in FLAG_HEX:
            return match.group(0)
        token = HEX_TO_TOKEN.get(hexv)
        return token if token else match.group(0)

    return re.sub(r"\[#([0-9A-Fa-f]{6})\]", repl, content)


def replace_raw_hex(content: str) -> str:
    def repl(match: re.Match[str]) -> str:
        hexv = match.group(1).lower()
        if hexv in FLAG_HEX:
            return match.group(0)
        new = HEX_TO_HEX.get(hexv)
        return f"#{new}" if new else match.group(0)

    return re.sub(r"#([0-9A-Fa-f]{6})\b", repl, content)


def replace_scales(content: str) -> str:
    for _family, pairs in SCALE_MAPS:
        for old, new in pairs:
            content = content.replace(old, new)
    return content


def replace_prefixes(content: str) -> str:
    for old, new in PREFIX_REPLACEMENTS:
        content = content.replace(old, new)
    return content


def polish(content: str) -> str:
    # Inputs: ring should be primary-500
    content = content.replace("focus:ring-primary-600", "focus:ring-primary-500")
    content = content.replace("focus:ring-primary-700", "focus:ring-primary-500")
    content = content.replace("focus:border-primary-500", "focus:border-primary-500")
    content = content.replace("focus:ring-4 focus:ring-primary-100", "focus:ring-4 focus:ring-primary-500/20")
    # Avoid double tokens like success-600 if we mapped emerald-600 to success then prefix
    content = content.replace("text-success-600", "text-success")
    content = content.replace("bg-success-600", "bg-success")
    content = content.replace("hover:bg-success-600", "hover:bg-success")
    content = content.replace("text-danger-600", "text-danger")
    content = content.replace("bg-danger-600", "bg-danger")
    content = content.replace("hover:bg-danger-600", "hover:bg-danger")
    content = content.replace("hover:bg-danger-700", "hover:bg-danger")
    content = content.replace("text-warning-500", "text-warning")
    content = content.replace("bg-warning-500", "bg-warning")
    return content


def transform(content: str) -> str:
    content = replace_arbitrary_hex(content)
    content = replace_raw_hex(content)
    content = replace_scales(content)
    content = replace_prefixes(content)
    content = polish(content)
    return content


def main() -> None:
    changed = 0
    for path in iter_files():
        original = path.read_text(encoding="utf-8")
        updated = transform(original)
        if updated != original:
            path.write_text(updated, encoding="utf-8")
            changed += 1
            print(f"updated {path.relative_to(ROOT)}")
    print(f"\n{changed} files updated")


if __name__ == "__main__":
    main()
