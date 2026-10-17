"""
lpdf-python-demo

Install and run:
    pip install -r requirements.txt
    python demo.py [input.xml] [output.pdf]

Defaults: built-in sample document → ../examples/output.pdf
    Pass an XML path to render your own document instead.

Docker (from the repo root):
    docker build -f python/Dockerfile -t lpdf-python-demo .
    docker run --rm lpdf-python-demo
    docker run --rm -v ./my.xml:/data/input.xml:ro lpdf-python-demo /data/input.xml /examples/result.pdf
"""

import sys
from datetime import datetime, timezone
from pathlib import Path

from lpdf import (
    L,
    NoAttr,
    DocumentAttr,
    LayoutAttr,
    ColumnAttr,
    TextAttr,
    SpanAttr,
)

# Arg contract:
#   python demo.py                      → L builder → ../examples/output.pdf
#   python demo.py output.pdf            → L builder → output.pdf
#   python demo.py input.xml output.pdf  → render XML → output.pdf

if len(sys.argv) > 2:
    output_path = Path(sys.argv[2])
elif len(sys.argv) > 1:
    output_path = Path(sys.argv[1])
else:
    output_path = Path(__file__).resolve().parent.parent / "examples" / "output.pdf"

# no key → free tier (watermark)
engine = L.engine()

if len(sys.argv) > 2:
    # Render caller-supplied XML file.
    pdf = engine.render(Path(sys.argv[1]).read_text(encoding="utf-8"))
else:
    # No XML supplied — build a sample document with the L builder.
    generated = datetime.now(timezone.utc).strftime("%Y-%m-%d %H:%M") + " UTC"

    doc = L.document(DocumentAttr(size="letter"), [
        L.section(NoAttr, [
            L.layout(LayoutAttr(margin="40pt"), [
                L.column(ColumnAttr(gap="16pt"), [
                    L.text(
                        TextAttr(font_size="28pt", bold="true", color="#1a1a2e"),
                        ["lpdf Python demo"],
                    ),

                    L.divider(),

                    L.text(NoAttr, ["This PDF was generated programmatically using the L builder API — no XML file required."]),

                    L.column(
                        ColumnAttr(background="#f0f4ff", padding="16pt", gap="6pt"),
                        [
                            L.text(TextAttr(bold="true"), ["Key facts"]),
                            L.text(NoAttr, ["• Engine: lpdfio-lpdf (WASI / wasmtime)"]),
                            L.text(NoAttr, ["• License: free tier — watermark visible"]),
                            L.text(NoAttr, [f"• Generated: {generated}"]),
                        ],
                    ),

                    L.text(NoAttr, [
                        "Pass ",
                        L.span(SpanAttr(bold="true"), ["path/to/doc.xml"]),
                        " as the first argument to render your own XML document.",
                    ]),
                ]),
            ]),
        ]),
    ])

    pdf = engine.render(doc)

output_path.write_bytes(pdf)
print(f"{output_path} written ({len(pdf):,} bytes)")
