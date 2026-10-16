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

use Lpdf\LpdfEngine;
use Lpdf\LpdfKit;
use Lpdf\LpdfLayout;
use Lpdf\Kit\DocumentOptions;
use Lpdf\Kit\SectionOptions;
use Lpdf\Layout\StackOptions;
use Lpdf\Layout\TextOptions;
use Lpdf\Layout\SpanOptions;

// Arg contract:
//   php demo.php                      → Kit API → ../examples/output.pdf
//   php demo.php output.pdf            → Kit API → output.pdf
//   php demo.php input.xml output.pdf  → render XML → output.pdf

if (isset($argv[2])) {
    $outputPath = $argv[2];
} else {
    $outputPath = $argv[1] ?? __DIR__ . '/../examples/output.pdf';
}

// empty key → free tier (watermark)
$engine = new LpdfEngine('');

if (isset($argv[2])) {
    // Render caller-supplied XML file.
    $xml = file_get_contents($argv[1]);
    $pdf = $engine->renderPdf($xml);
} else {
    // No XML supplied — build a sample document with the Kit API.
    $generated = (new DateTimeImmutable('now', new DateTimeZone('UTC')))->format('Y-m-d H:i') . ' UTC';

    $doc = LpdfKit::document(
        sections: [
            LpdfKit::section(
                nodes: [
                    LpdfKit::layout([
                        LpdfLayout::stack([
                            LpdfLayout::text(['lpdf PHP demo'],
                                new TextOptions(fontSize: '28pt', bold: 'true', color: '#1a1a2e')),

                            LpdfLayout::divider(),

                            LpdfLayout::text(['This PDF was generated programmatically using the LpdfKit builder API — no XML file required.']),

                            LpdfLayout::stack([
                                LpdfLayout::text(['Key facts'], new TextOptions(bold: 'true')),
                                LpdfLayout::text(['• Engine: lpdfio/lpdf (WASI / wasmtime)']),
                                LpdfLayout::text(['• License: free tier — watermark visible']),
                                LpdfLayout::text(["• Generated: $generated"]),
                            ], new StackOptions(background: '#f0f4ff', padding: '16pt', gap: '6pt')),

                            LpdfLayout::text([
                                'Pass ',
                                LpdfLayout::span(['path/to/doc.xml'], new SpanOptions(bold: true)),
                                ' as the first argument to render your own XML document.',
                            ]),
                        ], new StackOptions(gap: '16pt')),
                    ]),
                ],
                options: new SectionOptions(size: 'letter', margin: '40pt'),
            ),
        ],
        options: new DocumentOptions(),
    );

    $pdf = $engine->renderPdf($doc);
}

file_put_contents($outputPath, $pdf);
echo $outputPath . ' written (' . number_format(strlen($pdf)) . " bytes)\n";
