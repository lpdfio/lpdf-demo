# Lpdf .NET demo

A .NET program that renders an XML document, or a document built in code, to a PDF file with [`Lpdfio.Lpdf`](https://www.nuget.org/packages/Lpdfio.Lpdf).

## Requirements

- .NET 8 SDK

## Run locally

```bash
cd dotnet
dotnet run                                                            # builds a sample document with the L builder → ../examples/output.pdf

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
docker run --rm -it --entrypoint /bin/sh lpdf-dotnet-demo

# run with the L builder — PDF lands in /examples/output.pdf inside the container
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

1. Builds the document in code with `L`, or reads it from an XML file
2. Renders it with `engine.Render()`
3. Writes the PDF to the output path

## Docs

- [lpdf.io/docs](https://lpdf.io/docs/)
- [XML schema](https://lpdf.io/schema/lpdf.xsd)
- [Lpdfio.Lpdf on NuGet](https://www.nuget.org/packages/Lpdfio.Lpdf)
