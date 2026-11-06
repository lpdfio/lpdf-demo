# lpdf Java demo

Minimal demo for the [io.lpdf:lpdf](https://central.sonatype.com/artifact/io.lpdf/lpdf) adapter — renders an XML document to a PDF file using the lpdf engine.

## Requirements

- JDK 17 or later
- Maven

> The Docker path below handles all dependencies automatically.

## Run locally

```bash
cd java
mvn package                                                           # builds target/lpdf-java-demo.jar, with the SDK in it
java -jar target/lpdf-java-demo.jar                                   # builds a sample document with the L builder → ../examples/output.pdf

java -jar target/lpdf-java-demo.jar ../examples/example1.xml ../examples/example1.pdf
java -jar target/lpdf-java-demo.jar ../examples/example2.xml ../examples/example2.pdf
java -jar target/lpdf-java-demo.jar ../examples/example3.xml ../examples/example3.pdf

java -jar target/lpdf-java-demo.jar path/to/document.xml  path/to/result.pdf   # custom XML
```

## Run with Docker

No local Java or Maven installation required. Run these commands from the repo root: the image copies the shared `examples/` folder.

```bash
# build the image
docker build -f java/Dockerfile -t lpdf-java-demo .

# open an interactive terminal inside the container
docker run --rm -it --entrypoint /bin/sh lpdf-java-demo

# run with the L builder — PDF lands in /examples/output.pdf inside the container
docker run --rm lpdf-java-demo

# run a built-in example — PDF is written inside the container and lost on exit
docker run --rm lpdf-java-demo /examples/example1.xml /examples/example1.pdf
docker run --rm lpdf-java-demo /examples/example2.xml /examples/example2.pdf
docker run --rm lpdf-java-demo /examples/example3.xml /examples/example3.pdf

# save the PDF to your host — mount the examples folder as a volume
docker run --rm -v "./examples:/examples" lpdf-java-demo /examples/example1.xml /examples/example1.pdf
docker run --rm -v "./examples:/examples" lpdf-java-demo /examples/example2.xml /examples/example2.pdf
docker run --rm -v "./examples:/examples" lpdf-java-demo /examples/example3.xml /examples/example3.pdf

```

## What it does

1. Describes the document structure programmatically with the `L` builder, or using XML
2. Passes the document structure to `PdfEngine.render()`
3. Writes the resulting PDF bytes to the output path

The engine is compiled to JVM classes inside the SDK's jar and runs on [Endive](https://github.com/bytecodealliance/endive), a WebAssembly runtime written in Java: no native code and nothing else to install.

## License key

No license key is set, which enables the free tier (output includes a watermark). Pass a valid key to `setLicenseKey()` to remove it.

## Learn more

- [lpdf documentation](https://lpdf.io/docs)
- [XML schema reference](https://lpdf.io/schema/lpdf.xsd)
- [io.lpdf:lpdf on Maven Central](https://central.sonatype.com/artifact/io.lpdf/lpdf)
