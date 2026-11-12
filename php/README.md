# Lpdf PHP demo

A PHP program that renders an XML document, or a document built in code, to a PDF file with [`lpdfio/lpdf`](https://packagist.org/packages/lpdfio/lpdf).

## Requirements

- PHP 8.2 or later, with the DOM extension
- Composer
- One of the two ways the SDK runs the engine: its native library through PHP's FFI extension, on Linux with glibc 2.17 or newer on x86_64 or arm64, or [`wasmtime`](https://wasmtime.dev) on `PATH` ([requirements of `lpdfio/lpdf`](https://github.com/lpdfio/lpdf-php#requirements)). The Docker image uses `wasmtime`.

> The Docker path below handles all dependencies automatically.

## Run locally

```bash
cd php
composer install
php demo.php                                                          # builds a sample document with the L builder → ../examples/output.pdf

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
docker run --rm -it --entrypoint /bin/sh lpdf-php-demo

# run with the L builder — PDF lands in /examples/output.pdf inside the container
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

1. Builds the document in code with `L`, or reads it from an XML file
2. Renders it with `$engine->render()`
3. Writes the PDF to the output path

## Docs

- [lpdf.io/docs](https://lpdf.io/docs/)
- [XML schema](https://lpdf.io/schema/lpdf.xsd)
- [lpdfio/lpdf on Packagist](https://packagist.org/packages/lpdfio/lpdf)
