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
const { L, NoAttr } = require('@lpdfio/lpdf');

(async () => {
  // Arg contract:
  //   node demo.js                      → L builder → ../examples/output.pdf
  //   node demo.js output.pdf            → L builder → output.pdf
  //   node demo.js input.xml output.pdf  → render XML → output.pdf
  const args = process.argv.slice(2);
  const outputPath = args[1] ? resolve(args[1]) : (args[0] ? resolve(args[0]) : resolve(__dirname, '..', 'examples', 'output.pdf'));

  // no key → free tier (watermark)
  const engine = L.engine();

  let bytes;

  if (args[1]) {
    // Render caller-supplied XML file.
    const xml = readFileSync(resolve(args[0]), 'utf8');
    bytes = await engine.render(xml);
  } else {
    // No XML supplied — build a sample document with the L builder.
    const now = new Date().toISOString().slice(0, 16).replace('T', ' ');
    const doc = L.document({ size: 'letter' }, [
      L.section(NoAttr, [
        L.layout({ margin: '40pt' }, [
          L.column({ gap: '16pt' }, [
            L.text({ fontSize: '28pt', bold: 'true', color: '#1a1a2e' }, ['lpdf Node.js demo']),

            L.divider(),

            L.text(NoAttr, ['This PDF was generated programmatically using the L builder API — no XML file required.']),

            L.column({ background: '#f0f4ff', padding: '16pt', gap: '6pt' }, [
              L.text({ bold: 'true' }, ['Key facts']),
              L.text(NoAttr, ['• Engine: @lpdfio/lpdf (WASM / Node.js)']),
              L.text(NoAttr, ['• License: free tier — watermark visible']),
              L.text(NoAttr, [`• Generated: ${now} UTC`]),
            ]),

            L.text(NoAttr, [
              'Pass ',
              L.span({ bold: 'true' }, ['path/to/doc.xml']),
              ' as the first argument to render your own XML document.',
            ]),
          ]),
        ]),
      ]),
    ]);

    bytes = await engine.render(doc);
  }

  writeFileSync(outputPath, bytes);
  console.log(`${outputPath} written (${bytes.length.toLocaleString()} bytes)`);
})();
