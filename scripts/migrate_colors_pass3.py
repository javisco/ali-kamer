#!/usr/bin/env python3
from pathlib import Path
import re

ROOT = Path("/home/javisco-jj/ali-kamer/resources/views")

HEX_TO_TOKEN = {
    "9a5d00": "accent-600",
    "7a5200": "accent-700",
    "8a6500": "accent-600",
    "e99a0a": "accent-500",
    "01582f": "primary-700",
    "015a30": "primary-700",
    "014d2b": "primary-800",
    "006f34": "primary-700",
    "f7f9f7": "slate-50",
    "f7f8fa": "slate-50",
    "f0fdf4": "primary-50",
    "c90511": "danger",
    "a96d00": "accent-600",
    "8a7000": "warning-800",
    "8a6900": "warning-800",
    "9a7600": "warning-800",
}

SKIP = {"fcd116", "ce1126", "1877f2", "0d9488", "0f766e", "134e4a", "ea580c", "f97316",
        "f8fafc", "e2e8f0", "0f172a", "94a3b8", "f1f5f9", "99f6e4", "f0fdfa", "ccfbf1",
        "ef4444", "f59e0b", "10b981", "3b82f6", "14b8a6", "115e59"}


def transform(content: str) -> str:
    def arb(m):
        h = m.group(1).lower()
        if h in SKIP:
            return m.group(0)
        t = HEX_TO_TOKEN.get(h)
        return t if t else m.group(0)

    content = re.sub(r"\[#([0-9A-Fa-f]{6})\]", arb, content)

    def raw(m):
        h = m.group(1).lower()
        if h in SKIP:
            return m.group(0)
        t = HEX_TO_TOKEN.get(h)
        if not t:
            return m.group(0)
        mapping = {
            "accent-600": "EA580C",
            "accent-700": "C2410C",
            "accent-500": "F97316",
            "primary-700": "0F766E",
            "primary-800": "115E59",
            "slate-50": "F8FAFC",
            "primary-50": "F0FDFA",
            "danger": "EF4444",
            "warning-800": "92400E",
        }
        return f"#{mapping[t]}"

    return re.sub(r"#([0-9A-Fa-f]{6})\b", raw, content)


n = 0
for path in ROOT.rglob("*.php"):
    original = path.read_text(encoding="utf-8")
    updated = transform(original)
    if updated != original:
        path.write_text(updated, encoding="utf-8")
        n += 1
        print(path)
print(n, "files")
