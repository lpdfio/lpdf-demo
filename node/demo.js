/**
 * lpdf-node-demo
 *
 * Install and run:
 *   npm install
 *   node demo.js [input.xml] [output.pdf]
 *
 * Defaults: built-in sample document → ../examples/output.pdf
 *   Pass an XML path to render your own document instead.
 *
 * Docker (from the repo root):
 *   docker build -f node/Dockerfile -t lpdf-node-demo .
 *   docker run --rm lpdf-node-demo
 *   docker run --rm -v ./my.xml:/data/input.xml:ro lpdf-node-demo /data/input.xml /examples/result.pdf
 */

'use strict';

const { readFileSync, writeFileSync } = require('node:fs');
const { resolve } = require('node:path');
const { LpdfEngine, LpdfKit, LpdfLayout } = require('@lpdfio/lpdf');

(async () => {
  // Arg contract:
  //   node demo.js                      → Kit API → ../examples/output.pdf
  //   node demo.js output.pdf            → Kit API → output.pdf
  //   node demo.js input.xml output.pdf  → render XML → output.pdf
  const args = process.argv.slice(2);
  const outputPath = args[1] ? resolve(args[1]) : (args[0] ? resolve(args[0]) : resolve(__dirname, '..', 'examples', 'output.pdf'));

  // empty key → free tier (watermark)
  const engine = new LpdfEngine('');

  let bytes;

  if (args[1]) {
    // Render caller-supplied XML file.
    const xml = readFileSync(resolve(args[0]), 'utf8');
    bytes = await engine.renderPdf(xml);
  } else {
    // No XML supplied — build a sample document with the Kit API.
    const now = new Date().toISOString().slice(0, 16).replace('T', ' ');
    const doc = LpdfKit.document({
      sections: [
        LpdfKit.section({
          nodes: [
            LpdfKit.layout([
              LpdfLayout.stack([
                LpdfLayout.text(['lpdf Node.js demo'],
                  { fontSize: '28pt', bold: 'true', color: '#1a1a2e' }),

                LpdfLayout.divider(),

                LpdfLayout.text(['This PDF was generated programmatically using the LpdfKit builder API — no XML file required.']),

                LpdfLayout.stack([
                  LpdfLayout.text(['Key facts'], { bold: 'true' }),
                  LpdfLayout.text(['• Engine: @lpdfio/lpdf (WASM / Node.js)']),
                  LpdfLayout.text(['• License: free tier — watermark visible']),
                  LpdfLayout.text([`• Generated: ${now} UTC`]),
                ], { background: '#f0f4ff', padding: '16pt', gap: '6pt' }),

                LpdfLayout.text([
                  'Pass ',
                  LpdfLayout.span(['path/to/doc.xml'], { bold: 'true' }),
                  ' as the first argument to render your own XML document.',
                ]),
              ], { gap: '16pt' }),
            ]),
          ],
          options: { size: 'letter', margin: '40pt' },
        }),
      ],
    });

    bytes = await engine.renderPdf(doc);
  }

  writeFileSync(outputPath, bytes);
  console.log(`${outputPath} written (${bytes.length.toLocaleString()} bytes)`);
})();
