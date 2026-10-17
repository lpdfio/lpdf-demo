// lpdf-dotnet-demo
//
// Install and run:
//   dotnet run -- [input.xml] [output.pdf]
//
// Defaults: built-in sample document → ../examples/output.pdf
//   Pass an XML path to render your own document instead.
//
// Docker (from the repo root):
//   docker build -f dotnet/Dockerfile -t lpdf-dotnet-demo .
//   docker run --rm -v ./out:/out lpdf-dotnet-demo
//   docker run --rm -v ./my.xml:/data/input.xml:ro -v ./out:/out lpdf-dotnet-demo /data/input.xml /out/result.pdf

using Lpdf;

var projectDir = AppContext.BaseDirectory;
// When running via `dotnet run`, climb out of bin/Debug/net8.0/ to the project root.
while (!File.Exists(Path.Combine(projectDir, "LpdfDemo.csproj")) && Directory.GetParent(projectDir) is { } parent)
    projectDir = parent.FullName;

var outputPath = args.Length > 1 ? args[1] : Path.GetFullPath(Path.Combine(projectDir, "..", "examples", "output.pdf"));

// no key → free tier (watermark)
var engine = L.Engine();

byte[] bytes;

if (args.Length > 0)
{
    // Render caller-supplied XML file.
    var xml = await File.ReadAllTextAsync(args[0]);
    bytes = await engine.Render(xml);
}
else
{
    // No XML supplied — build a sample document with the L builder.
    var doc = L.Document(new() { Size = "letter" }, [
        L.Section(L.NoAttr, [
            L.Layout(new() { Margin = "40pt" }, [
                L.Column(new() { Gap = "16pt" }, [
                    L.Text(new() { FontSize = "28pt", Bold = "true", Color = "#1a1a2e" }, [L.Raw("lpdf .NET demo")]),

                    L.Divider(),

                    L.Text(L.NoAttr, [L.Raw("This PDF was generated programmatically using the L builder API — no XML file required.")]),

                    L.Column(new() { Background = "#f0f4ff", Padding = "16pt", Gap = "6pt" }, [
                        L.Text(new() { Bold = "true" }, [L.Raw("Key facts")]),
                        L.Text(L.NoAttr, [L.Raw("• Engine: Lpdfio.Lpdf (WASI / Wasmtime)")]),
                        L.Text(L.NoAttr, [L.Raw("• License: free tier — watermark visible")]),
                        L.Text(L.NoAttr, [L.Raw($"• Generated: {DateTime.UtcNow:yyyy-MM-dd HH:mm} UTC")]),
                    ]),

                    L.Text(L.NoAttr, [
                        L.Raw("Pass "),
                        L.Span(new() { Bold = "true" }, ["path/to/doc.xml"]),
                        L.Raw(" as the first argument to render your own XML document."),
                    ]),
                ]),
            ]),
        ]),
    ]);

    bytes = await engine.Render(doc);
}

await File.WriteAllBytesAsync(outputPath, bytes);
Console.WriteLine($"{outputPath} written ({bytes.Length:N0} bytes)");
