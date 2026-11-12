# Lpdf Python demo

A Python program that renders an XML document, or a document built in code, to a PDF file with [`lpdfio-lpdf`](https://pypi.org/project/lpdfio-lpdf/).

## Requirements

- Python 3.11 or later, on Linux (x86_64 or aarch64, glibc or musl), macOS (x86_64 or arm64) or Windows (x64 or arm64)

> The Docker path below handles all dependencies automatically.

## Run locally

```bash
cd python
pip install -r requirements.txt
python demo.py                                                        # builds a sample document with the L builder → ../examples/output.pdf

python demo.py ../examples/example1.xml ../examples/example1.pdf
python demo.py ../examples/example2.xml ../examples/example2.pdf
python demo.py ../examples/example3.xml ../examples/example3.pdf

python demo.py path/to/document.xml  path/to/result.pdf               # custom XML
```

A virtual environment is recommended:

```bash
python -m venv .venv
source .venv/bin/activate   # Windows: .venv\Scripts\activate
pip install -r requirements.txt
python demo.py
```

## Run with Docker

No local Python or pip installation required. Run these commands from the repo root: the image copies the shared `examples/` folder.

```bash
# build the image
docker build -f python/Dockerfile -t lpdf-python-demo .

# open an interactive terminal inside the container
docker run --rm -it --entrypoint /bin/sh lpdf-python-demo

# run with the L builder — PDF lands in /examples/output.pdf inside the container
docker run --rm lpdf-python-demo

# run a built-in example — PDF is written inside the container and lost on exit
docker run --rm lpdf-python-demo /examples/example1.xml /examples/example1.pdf
docker run --rm lpdf-python-demo /examples/example2.xml /examples/example2.pdf
docker run --rm lpdf-python-demo /examples/example3.xml /examples/example3.pdf

# save the PDF to your host — mount the examples folder as a volume
docker run --rm -v "./examples:/examples" lpdf-python-demo /examples/example1.xml /examples/example1.pdf
docker run --rm -v "./examples:/examples" lpdf-python-demo /examples/example2.xml /examples/example2.pdf
docker run --rm -v "./examples:/examples" lpdf-python-demo /examples/example3.xml /examples/example3.pdf

```

## What it does

1. Builds the document in code with `L`, or reads it from an XML file
2. Renders it with `engine.render()`
3. Writes the PDF to the output path

## Docs

- [lpdf.io/docs](https://lpdf.io/docs/)
- [XML schema](https://lpdf.io/schema/lpdf.xsd)
- [lpdfio-lpdf on PyPI](https://pypi.org/project/lpdfio-lpdf/)
