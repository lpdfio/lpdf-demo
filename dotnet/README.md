# lpdf .NET demo

Minimal demo for the [Lpdfio.Lpdf](https://www.nuget.org/packages/Lpdfio.Lpdf) adapter — renders an XML document to a PDF file using the lpdf engine.

## Requirements

- .NET 8 SDK

## Run locally

```bash
cd dotnet
dotnet run                                                            # builds a sample document with the Kit API → ../examples/output.pdf

dotnet run -- ../examples/example1.xml ../examples/example1.pdf
dotnet run -- ../examples/example2.xml ../examples/example2.pdf
dotnet run -- ../examples/example3.xml ../examples/example3.pdf

dotnet run -- path/to/document.xml  path/to/result.pdf                # custom XML
```

## Run with Docker

No local .NET installation required. Run these commands from the repo root: the image copies the shared `examples/` folder.

```bash
# build the image
docker build -f dotnet/Dockerfile -t lpdf-dotnet-demo .

# open an interactive terminal inside the container
docker run --rm -it lpdf-dotnet-demo /bin/sh

# run with the Kit API — PDF lands in /examples/output.pdf inside the container
docker run --rm lpdf-dotnet-demo

# run a built-in example — PDF is written inside the container and lost on exit
docker run --rm lpdf-dotnet-demo /examples/example1.xml /examples/example1.pdf
docker run --rm lpdf-dotnet-demo /examples/example2.xml /examples/example2.pdf
docker run --rm lpdf-dotnet-demo /examples/example3.xml /examples/example3.pdf

# save the PDF to your host — mount the examples folder as a volume
docker run --rm -v "./examples:/examples" lpdf-dotnet-demo /examples/example1.xml /examples/example1.pdf
docker run --rm -v "./examples:/examples" lpdf-dotnet-demo /examples/example2.xml /examples/example2.pdf
docker run --rm -v "./examples:/examples" lpdf-dotnet-demo /examples/example3.xml /examples/example3.pdf

```

## What it does

1. Describes the document structure programmatically using the Kit, or using XML
2. Passes the document structure to `LpdfEngine.RenderPdf()`
3. Writes the resulting PDF bytes to the output path

The engine uses an embedded WASI binary via the [Wasmtime](https://www.nuget.org/packages/Wasmtime) NuGet package — no native tooling required.

## License key

An empty string `""` is used as the license key, which enables the free tier (output includes a watermark). Pass a valid key to remove it.

## Learn more

- [lpdf documentation](https://lpdf.io/docs)
- [XML schema reference](https://lpdf.io/schema/lpdf.xsd)
- [Lpdfio.Lpdf on NuGet](https://www.nuget.org/packages/Lpdfio.Lpdf)
