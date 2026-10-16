# lpdf Node.js demo

Minimal demo for the [@lpdfio/lpdf](https://www.npmjs.com/package/@lpdfio/lpdf) adapter — renders an XML document to a PDF file using the lpdf engine.

## Requirements

- Node.js 18+

## Run locally

```bash
cd node
npm install
node demo.js                                                          # builds a sample document with the Kit API → ../examples/output.pdf

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
docker run --rm -it lpdf-node-demo /bin/sh

# run with the Kit API — PDF lands in /examples/output.pdf inside the container
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

1. Describes the document structure programmatically using the Kit, or using XML
2. Passes the document structure to `LpdfEngine.renderPdf()`
3. Writes the resulting PDF bytes to the output path

The engine runs an embedded WASM binary — no native dependencies, no external services.

## License key

An empty string `''` is used as the license key, which enables the free tier (output includes a watermark). Pass a valid key to remove it.

## Learn more

- [lpdf documentation](https://lpdf.io/docs)
- [XML schema reference](https://lpdf.io/schema/lpdf.xsd)
- [@lpdfio/lpdf on npm](https://www.npmjs.com/package/@lpdfio/lpdf)
