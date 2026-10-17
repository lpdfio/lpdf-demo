<?php

/**
 * lpdf-php-demo
 *
 * Install and run:
 *   composer install
 *   php demo.php [input.xml] [output.pdf]
 *
 * Defaults: built-in sample document → ../examples/output.pdf
 *   Pass an XML path to render your own document instead.
 *
 * Docker (from the repo root):
 *   docker build -f php/Dockerfile -t lpdf-php-demo .
 *   docker run --rm lpdf-php-demo
 *   docker run --rm -v ./my.xml:/data/input.xml:ro lpdf-php-demo /data/input.xml /examples/result.pdf
 */

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Lpdf\L;
use Lpdf\Kit\DocumentAttr;
use Lpdf\Kit\LayoutAttr;
use Lpdf\Layout\ColumnAttr;
use Lpdf\Layout\SpanAttr;
use Lpdf\Layout\TextAttr;
use const Lpdf\NoAttr;

// Arg contract:
//   php demo.php                      → L builder → ../examples/output.pdf
//   php demo.php output.pdf            → L builder → output.pdf
//   php demo.php input.xml output.pdf  → render XML → output.pdf

if (isset($argv[2])) {
    $outputPath = $argv[2];
} else {
    $outputPath = $argv[1] ?? __DIR__ . '/../examples/output.pdf';
}

// no key → free tier (watermark)
$engine = L::engine();

if (isset($argv[2])) {
    // Render caller-supplied XML file.
    $xml = file_get_contents($argv[1]);
    $pdf = $engine->render($xml);
} else {
    // No XML supplied — build a sample document with the L builder.
    $generated = (new DateTimeImmutable('now', new DateTimeZone('UTC')))->format('Y-m-d H:i') . ' UTC';

    $doc = L::document(new DocumentAttr(size: 'letter'), [
        L::section(NoAttr, [
            L::layout(new LayoutAttr(margin: '40pt'), [
                L::column(new ColumnAttr(gap: '16pt'), [
                    L::text(new TextAttr(fontSize: '28pt', bold: 'true', color: '#1a1a2e'), ['lpdf PHP demo']),

                    L::divider(),

                    L::text(NoAttr, ['This PDF was generated programmatically using the L builder API — no XML file required.']),

                    L::column(new ColumnAttr(background: '#f0f4ff', padding: '16pt', gap: '6pt'), [
                        L::text(new TextAttr(bold: 'true'), ['Key facts']),
                        L::text(NoAttr, ['• Engine: lpdfio/lpdf (WASI / wasmtime)']),
                        L::text(NoAttr, ['• License: free tier — watermark visible']),
                        L::text(NoAttr, ["• Generated: $generated"]),
                    ]),

                    L::text(NoAttr, [
                        'Pass ',
                        L::span(new SpanAttr(bold: 'true'), ['path/to/doc.xml']),
                        ' as the first argument to render your own XML document.',
                    ]),
                ]),
            ]),
        ]),
    ]);

    $pdf = $engine->render($doc);
}

file_put_contents($outputPath, $pdf);
echo $outputPath . ' written (' . number_format(strlen($pdf)) . " bytes)\n";
