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
using Lpdf.Kit;
using Lpdf.Layout;

var projectDir = AppContext.BaseDirectory;
// When running via `dotnet run`, climb out of bin/Debug/net8.0/ to the project root.
while (!File.Exists(Path.Combine(projectDir, "LpdfDemo.csproj")) && Directory.GetParent(projectDir) is { } parent)
    projectDir = parent.FullName;

var outputPath = args.Length > 1 ? args[1] : Path.GetFullPath(Path.Combine(projectDir, "..", "examples", "output.pdf"));

// empty key → free tier (watermark)
var engine = Pdf.Engine();

byte[] bytes;

if (args.Length > 0)
{
    // Render caller-supplied XML file.
    var xml = await File.ReadAllTextAsync(args[0]);
    bytes = await engine.Render(xml);
}
else
{
    // No XML supplied — build a sample document with the Kit API.
    var doc = Pdf.Document(
        new DocumentAttr(Size: "letter", Margin: "40pt"),
        [
            Pdf.Section(nodes: [
                Pdf.Layout(null, [
                    Pdf.Stack(new StackAttr(Gap: "16pt"), [
                        Pdf.Text(new TextAttr(FontSize: "28pt", Bold: "true", Color: "#1a1a2e"), [Pdf.Raw("lpdf .NET demo")]),

                        Pdf.Divider(),

                        Pdf.Text(null, [Pdf.Raw("This PDF was generated programmatically using the LpdfKit builder API — no XML file required.")]),

                        Pdf.Stack(new StackAttr(Background: "#f0f4ff", Padding: "16pt", Gap: "6pt"), [
                            Pdf.Text(new TextAttr(Bold: "true"), [Pdf.Raw("Key facts")]),
                            Pdf.Text(null, [Pdf.Raw("• Engine: Lpdfio.Lpdf (WASI / Wasmtime)")]),
                            Pdf.Text(null, [Pdf.Raw("• License: free tier — watermark visible")]),
                            Pdf.Text(null, [Pdf.Raw($"• Generated: {DateTime.UtcNow:yyyy-MM-dd HH:mm} UTC")]),
                        ]),

                        Pdf.Text(null, [
                            Pdf.Raw("Pass "),
                            Pdf.Span(new SpanAttr(Bold: "true"), ["path/to/doc.xml"]),
                            Pdf.Raw(" as the first argument to render your own XML document."),
                        ]),
                    ])
                ])
            ])
        ]);

    bytes = await engine.Render(doc);
}

await File.WriteAllBytesAsync(outputPath, bytes);
Console.WriteLine($"{outputPath} written ({bytes.Length:N0} bytes)");
