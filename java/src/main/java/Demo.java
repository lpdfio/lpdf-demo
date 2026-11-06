// lpdf-java-demo
//
// Build and run, from java/:
//   mvn package
//   java -jar target/lpdf-java-demo.jar [input.xml] [output.pdf]
//
// Defaults: built-in sample document → ../examples/output.pdf
//   Pass an XML path to render your own document instead.
//
// Docker (from the repo root):
//   docker build -f java/Dockerfile -t lpdf-java-demo .
//   docker run --rm lpdf-java-demo
//   docker run --rm -v ./my.xml:/data/input.xml:ro lpdf-java-demo /data/input.xml /examples/result.pdf

import static io.lpdf.L.NoAttr;

import io.lpdf.ColumnAttr;
import io.lpdf.DocumentAttr;
import io.lpdf.L;
import io.lpdf.LayoutAttr;
import io.lpdf.SpanAttr;
import io.lpdf.TextAttr;
import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Path;
import java.time.ZoneOffset;
import java.time.ZonedDateTime;
import java.time.format.DateTimeFormatter;
import java.util.List;

class Demo {
    public static void main(String[] args) throws IOException {
        // Arg contract:
        //   java -jar lpdf-java-demo.jar                      → L builder → ../examples/output.pdf
        //   java -jar lpdf-java-demo.jar output.pdf            → L builder → output.pdf
        //   java -jar lpdf-java-demo.jar input.xml output.pdf  → render XML → output.pdf
        // The default is from the current folder: java/ when run locally, /demo in the image.
        Path outputPath;
        if (args.length > 1) {
            outputPath = Path.of(args[1]);
        } else if (args.length > 0) {
            outputPath = Path.of(args[0]);
        } else {
            outputPath = Path.of("..", "examples", "output.pdf");
        }
        outputPath = outputPath.toAbsolutePath().normalize();

        // no key → free tier (watermark)
        var engine = L.engine();

        byte[] pdf;
        if (args.length > 1) {
            // Render caller-supplied XML file.
            pdf = engine.render(Files.readString(Path.of(args[0])));
        } else {
            // No XML supplied — build a sample document with the L builder.
            var generated = ZonedDateTime.now(ZoneOffset.UTC).format(DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm"));

            var doc = L.document(new DocumentAttr().size("letter"), List.of(
                L.section(NoAttr, List.of(
                    L.layout(new LayoutAttr().margin("40pt"), List.of(
                        L.column(new ColumnAttr().gap("16pt"), List.of(
                            L.text(new TextAttr().fontSize("28pt").bold(true).color("#1a1a2e"), List.of("lpdf Java demo")),

                            L.divider(NoAttr),

                            L.text(NoAttr, List.of("This PDF was generated programmatically using the L builder API — no XML file required.")),

                            L.column(new ColumnAttr().background("#f0f4ff").padding("16pt").gap("6pt"), List.of(
                                L.text(new TextAttr().bold(true), List.of("Key facts")),
                                L.text(NoAttr, List.of("• Engine: io.lpdf:lpdf (WASM compiled to JVM classes / Endive)")),
                                L.text(NoAttr, List.of("• License: free tier — watermark visible")),
                                L.text(NoAttr, List.of("• Generated: " + generated + " UTC"))
                            )),

                            L.text(NoAttr, List.of(
                                "Pass ",
                                L.span(new SpanAttr().bold(true), List.of("path/to/doc.xml")),
                                " as the first argument to render your own XML document."
                            ))
                        ))
                    ))
                ))
            ));

            pdf = engine.render(doc);
        }

        Files.write(outputPath, pdf);
        System.out.printf("%s written (%,d bytes)%n", outputPath, pdf.length);
    }
}
