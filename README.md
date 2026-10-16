# lpdf demos

Minimal demos for the lpdf adapters — each renders an XML document, or a document built with the Kit API, to a PDF file using the lpdf engine.

| Language | Package | Demo |
|---|---|---|
| .NET | [Lpdfio.Lpdf](https://www.nuget.org/packages/Lpdfio.Lpdf) | [dotnet/](dotnet/) |
| Node.js | [@lpdfio/lpdf](https://www.npmjs.com/package/@lpdfio/lpdf) | [node/](node/) |
| PHP | [lpdfio/lpdf](https://packagist.org/packages/lpdfio/lpdf) | [php/](php/) |
| Python | [lpdfio-lpdf](https://pypi.org/project/lpdfio-lpdf/) | [python/](python/) |

Each folder's README shows how to run that demo locally or with Docker.

## Examples

[`examples/`](examples/) holds three XML documents and the PDFs they render to. Every demo uses them, and writes the Kit API's sample to `examples/output.pdf` when run without arguments.

## Docker

The images copy `examples/`, so build them from the repo root:

```bash
docker build -f node/Dockerfile -t lpdf-node-demo .
docker run --rm -v "./examples:/examples" lpdf-node-demo /examples/example1.xml /examples/example1.pdf
```

## License key

Every demo passes an empty license key, which enables the free tier (output includes a watermark). Pass a valid key to remove it.

## Learn more

- [lpdf documentation](https://lpdf.io/docs)
- [XML schema reference](https://lpdf.io/schema/lpdf.xsd)
