#!/usr/bin/env python3
"""Second pass: leftover Cameroon-green / gold hex → official tokens."""

from __future__ import annotations

import re
from pathlib import Path

ROOT = Path("/home/javisco-jj/ali-kamer")
TARGETS = [ROOT / "resources" / "views", ROOT / "app"]

HEX_TO_TOKEN = {
    "004d28": "primary-800",
    "004d2a": "primary-800",
    "00522b": "primary-700",
    "006b32": "primary-700",
    "064323": "primary-800",
    "0a9b4e": "primary-500",
    "00843d": "primary-600",
    "e08e14": "accent-600",
    "e2bc13": "warning",
    "ffc20e": "warning",
    "f3fbf6": "success-50",
    "9a6500": "accent-600",
    "a66d00": "accent-600",
    "ea2328": "danger",
    "8b0000": "danger-800",
    "780f12": "danger-800",
    "b90510": "danger",
    "ff7900": "accent-500",
}

# Keep official Cameroon flag trio on dedicated patriotic strips only if
# they appear as from/via/to together — handled by skipping FCD116/CE1126.
SKIP = {"fcd116", "ce1126", "1877f2"}


def iter_files():
    for base in TARGETS:
        for path in base.rglob("*"):
            if path.is_file() and path.suffix == ".php":
                yield path


def transform(content: str) -> str:
    def arb(match: re.Match[str]) -> str:
        hexv = match.group(1).lower()
        if hexv in SKIP:
            return match.group(0)
        token = HEX_TO_TOKEN.get(hexv)
        return token if token else match.group(0)

    content = re.sub(r"\[#([0-9A-Fa-f]{6})\]", arb, content)

    def raw(match: re.Match[str]) -> str:
        hexv = match.group(1).lower()
        if hexv in SKIP:
            return match.group(0)
        token = HEX_TO_TOKEN.get(hexv)
        if not token:
            return match.group(0)
        # JS color strings like aliYellow: '#FFC20E'
        token_hex = {
            "primary-800": "115E59",
            "primary-700": "0F766E",
            "primary-600": "0D9488",
            "primary-500": "14B8A6",
            "accent-600": "EA580C",
            "accent-500": "F97316",
            "warning": "F59E0B",
            "success-50": "ECFDF5",
            "danger": "EF4444",
            "danger-800": "991B1B",
        }[token]
        return f"#{token_hex}"

    content = re.sub(r"#([0-9A-Fa-f]{6})\b", raw, content)
    return content


def main() -> None:
    n = 0
    for path in iter_files():
        original = path.read_text(encoding="utf-8")
        updated = transform(original)
        if updated != original:
            path.write_text(updated, encoding="utf-8")
            n += 1
            print(f"updated {path.relative_to(ROOT)}")
    print(f"\n{n} files updated")


if __name__ == "__main__":
    main()
