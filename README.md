# lpdf demos

Minimal demos for the lpdf adapters — each renders an XML document, or a document built with the `L` builder, to a PDF file using the lpdf engine.

| Language | Package | Demo |
|---|---|---|
| .NET | [Lpdfio.Lpdf](https://www.nuget.org/packages/Lpdfio.Lpdf) | [dotnet/](dotnet/) |
| Node.js | [@lpdfio/lpdf](https://www.npmjs.com/package/@lpdfio/lpdf) | [node/](node/) |
| PHP | [lpdfio/lpdf](https://packagist.org/packages/lpdfio/lpdf) | [php/](php/) |
| Python | [lpdfio-lpdf](https://pypi.org/project/lpdfio-lpdf/) | [python/](python/) |

Each folder's README shows how to run that demo locally or with Docker.

## Examples

[`examples/`](examples/) holds three XML documents and the PDFs they render to. Every demo uses them, and writes the builder's sample to `examples/output.pdf` when run without arguments.

## SDK versions

Each demo is pinned to the one SDK version it was written for, in its `package.json`, `composer.json`, `requirements.txt` or `.csproj`. A later release can change the API, so a pin moves when the demo has been checked against the new version: Dependabot opens a pull request for a demo when its package has a new version, and CI renders the demo with it.

## Docker

The images copy `examples/`, so build them from the repo root:

```bash
docker build -f node/Dockerfile -t lpdf-node-demo .
docker run --rm -v "./examples:/examples" lpdf-node-demo /examples/example1.xml /examples/example1.pdf
```

CI builds each image and renders the examples with it on every pull request. `.github/render-demo.sh <node|php|python|dotnet>` does the same locally, from the repo root.

## License key

No demo sets a license key, which enables the free tier (output includes a watermark). Set a valid key on the engine to remove it.

## Learn more

- [lpdf documentation](https://lpdf.io/docs)
- [XML schema reference](https://lpdf.io/schema/lpdf.xsd)
