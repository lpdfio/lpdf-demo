# Lpdf Node.js demo

A Node.js program that renders an XML document, or a document built in code, to a PDF file with [`@lpdfio/lpdf`](https://www.npmjs.com/package/@lpdfio/lpdf).

## Requirements

- Node.js 20 or later

## Run locally

```bash
cd node
npm install
node demo.js                                                          # builds a sample document with the L builder → ../examples/output.pdf

node demo.js ../examples/example1.xml ../examples/example1.pdf
node demo.js ../examples/example2.xml ../examples/example2.pdf
node demo.js ../examples/example3.xml ../examples/example3.pdf

node demo.js path/to/document.xml  path/to/result.pdf                 # custom XML
```

## Run with Docker

No local Node.js installation required. Run these commands from the repo root: the image copies the shared `examples/` folder.

```bash
# build the image
docker build -f node/Dockerfile -t lpdf-node-demo .

# open an interactive terminal inside the container
docker run --rm -it --entrypoint /bin/sh lpdf-node-demo

# run with the L builder — PDF lands in /examples/output.pdf inside the container
docker run --rm lpdf-node-demo

# run a built-in example — PDF is written inside the container and lost on exit
docker run --rm lpdf-node-demo /examples/example1.xml /examples/example1.pdf
docker run --rm lpdf-node-demo /examples/example2.xml /examples/example2.pdf
docker run --rm lpdf-node-demo /examples/example3.xml /examples/example3.pdf

# save the PDF to your host — mount the examples folder as a volume
docker run --rm -v "./examples:/examples" lpdf-node-demo /examples/example1.xml /examples/example1.pdf
docker run --rm -v "./examples:/examples" lpdf-node-demo /examples/example2.xml /examples/example2.pdf
docker run --rm -v "./examples:/examples" lpdf-node-demo /examples/example3.xml /examples/example3.pdf

```

## What it does

1. Builds the document in code with `L`, or reads it from an XML file
2. Renders it with `engine.render()`
3. Writes the PDF to the output path

## Docs

- [lpdf.io/docs](https://lpdf.io/docs/)
- [XML schema](https://lpdf.io/schema/lpdf.xsd)
- [@lpdfio/lpdf on npm](https://www.npmjs.com/package/@lpdfio/lpdf)
