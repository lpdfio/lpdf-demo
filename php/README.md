# lpdf PHP demo

Minimal demo for the [lpdfio/lpdf](https://packagist.org/packages/lpdfio/lpdf) adapter — renders an XML document to a PDF file using the lpdf engine.

## Requirements

- PHP 8.2+
- Composer
- [wasmtime](https://wasmtime.dev) on `$PATH`

> The Docker path below handles all dependencies automatically.

## Run locally

```bash
cd php
composer install
php demo.php                                                          # builds a sample document with the Kit API → ../examples/output.pdf

php demo.php ../examples/example1.xml ../examples/example1.pdf
php demo.php ../examples/example2.xml ../examples/example2.pdf
php demo.php ../examples/example3.xml ../examples/example3.pdf

php demo.php path/to/document.xml  path/to/result.pdf                 # custom XML
```

## Run with Docker

No local PHP, Composer, or wasmtime installation required. Run these commands from the repo root: the image copies the shared `examples/` folder.

```bash
# build the image
docker build -f php/Dockerfile -t lpdf-php-demo .

# open an interactive terminal inside the container
docker run --rm -it lpdf-php-demo /bin/sh

# run with the Kit API — PDF lands in /examples/output.pdf inside the container
docker run --rm lpdf-php-demo

# run a built-in example — PDF is written inside the container and lost on exit
docker run --rm lpdf-php-demo /examples/example1.xml /examples/example1.pdf
docker run --rm lpdf-php-demo /examples/example2.xml /examples/example2.pdf
docker run --rm lpdf-php-demo /examples/example3.xml /examples/example3.pdf

# save the PDF to your host — mount the examples folder as a volume
docker run --rm -v "./examples:/examples" lpdf-php-demo /examples/example1.xml /examples/example1.pdf
docker run --rm -v "./examples:/examples" lpdf-php-demo /examples/example2.xml /examples/example2.pdf
docker run --rm -v "./examples:/examples" lpdf-php-demo /examples/example3.xml /examples/example3.pdf

```

## What it does

1. Describes the document structure programmatically using the Kit, or using XML
2. Passes the document structure to `LpdfEngine->renderPdf()`
3. Writes the resulting PDF bytes to the output path

The engine shells out to a wasmtime process running the lpdf WASI binary — no PHP extensions required.

## License key

An empty string `''` is used as the license key, which enables the free tier (output includes a watermark). Pass a valid key to remove it.

## Learn more

- [lpdf documentation](https://lpdf.io/docs)
- [XML schema reference](https://lpdf.io/schema/lpdf.xsd)
- [lpdfio/lpdf on Packagist](https://packagist.org/packages/lpdfio/lpdf)
