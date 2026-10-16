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
    Pdf,
    NoAttr,
    DocumentAttr,
    SectionAttr,
    StackAttr,
    TextAttr,
    SpanAttr,
)

# Arg contract:
#   python demo.py                      → Kit API → ../examples/output.pdf
#   python demo.py output.pdf            → Kit API → output.pdf
#   python demo.py input.xml output.pdf  → render XML → output.pdf

if len(sys.argv) > 2:
    output_path = Path(sys.argv[2])
elif len(sys.argv) > 1:
    output_path = Path(sys.argv[1])
else:
    output_path = Path(__file__).resolve().parent.parent / "examples" / "output.pdf"

# no key → free tier (watermark)
engine = Pdf.engine()

if len(sys.argv) > 2:
    # Render caller-supplied XML file.
    pdf = engine.render(Path(sys.argv[1]).read_text(encoding="utf-8"))
else:
    # No XML supplied — build a sample document with the Kit API.
    generated = datetime.now(timezone.utc).strftime("%Y-%m-%d %H:%M") + " UTC"

    doc = Pdf.document(
        NoAttr,
        sections=[
            Pdf.section(
                SectionAttr(size="letter", margin="40pt"),
                nodes=[
                    Pdf.layout(NoAttr, [
                        Pdf.stack(StackAttr(gap="16pt"), [
                            Pdf.text(
                                ["lpdf Python demo"],
                                TextAttr(font_size="28pt", bold="true", color="#1a1a2e"),
                            ),

                            Pdf.divider(),

                            Pdf.text(["This PDF was generated programmatically using the Pdf builder API — no XML file required."]),

                            Pdf.stack(
                                StackAttr(background="#f0f4ff", padding="16pt", gap="6pt"),
                                [
                                    Pdf.text(["Key facts"], TextAttr(bold="true")),
                                    Pdf.text(["• Engine: lpdfio-lpdf (WASI / wasmtime)"]),
                                    Pdf.text(["• License: free tier — watermark visible"]),
                                    Pdf.text([f"• Generated: {generated}"]),
                                ],
                            ),

                            Pdf.text([
                                "Pass ",
                                Pdf.span(["path/to/doc.xml"], SpanAttr(bold="true")),
                                " as the first argument to render your own XML document.",
                            ]),
                        ]),
                    ]),
                ],
            ),
        ],
    )

    pdf = engine.render(doc)

output_path.write_bytes(pdf)
print(f"{output_path} written ({len(pdf):,} bytes)")
