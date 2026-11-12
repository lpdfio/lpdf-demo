# Lpdf demos

One small program per Lpdf SDK that renders an XML document, or a document built in code, to a PDF file.

| Language | Package | Demo |
|---|---|---|
| JavaScript | [@lpdfio/lpdf](https://www.npmjs.com/package/@lpdfio/lpdf) | [node/](node/) |
| Python | [lpdfio-lpdf](https://pypi.org/project/lpdfio-lpdf/) | [python/](python/) |
| Java | [io.lpdf:lpdf](https://central.sonatype.com/artifact/io.lpdf/lpdf) | [java/](java/) |
| C# | [Lpdfio.Lpdf](https://www.nuget.org/packages/Lpdfio.Lpdf) | [dotnet/](dotnet/) |
| PHP | [lpdfio/lpdf](https://packagist.org/packages/lpdfio/lpdf) | [php/](php/) |

Each demo's README shows how to run it on your machine or in Docker.

## Examples

[`examples/`](examples/) holds three XML documents and the PDFs they render to. Every demo renders them, and writes the document it builds in code to `examples/output.pdf` when run without arguments.

Each demo is pinned to the SDK version it was written for, in its `package.json`, `requirements.txt`, `pom.xml`, `.csproj` or `composer.json`.

## Docker

The images copy `examples/`, so build them from the repo root:

```bash
docker build -f node/Dockerfile -t lpdf-node-demo .
docker run --rm -v "./examples:/examples" lpdf-node-demo /examples/example1.xml /examples/example1.pdf
```

## Docs

- [lpdf.io/docs](https://lpdf.io/docs/)
- [XML schema](https://lpdf.io/schema/lpdf.xsd)
