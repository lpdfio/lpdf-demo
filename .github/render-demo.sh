#!/usr/bin/env bash
#
# Builds a demo's Docker image and renders with it what the demo's README tells a reader to render: the three
# examples, and the sample the demo builds itself. It fails when the image doesn't build, when the demo exits with an
# error, and when what it wrote is not a PDF.
#
# Run from the repo root, as the CI does:
#   .github/render-demo.sh <node|php|python|dotnet>
set -euo pipefail

demo=${1:?usage: render-demo.sh <node|php|python|dotnet>}
if [ ! -f "$demo/Dockerfile" ]; then
    echo "::error::there is no demo called '$demo'"
    exit 2
fi
image=lpdf-$demo-demo
out=$(mktemp -d)
trap 'rm -rf "$out"' EXIT

# A PDF starts with its header and ends with its trailer, and even a page with one line is over a kilobyte.
check_pdf() {
    local file=$1
    if [ ! -s "$file" ]; then
        echo "::error::the $demo demo wrote no ${file#"$out"/}"
        return 1
    fi
    if [ "$(head -c 5 "$file")" != "%PDF-" ] || ! tail -c 1024 "$file" | grep -aq '%%EOF' || [ "$(wc -c < "$file")" -lt 1000 ]; then
        echo "::error::${file#"$out"/} of the $demo demo is not a PDF"
        return 1
    fi
    echo "ok  ${file#"$out"/} ($(wc -c < "$file" | tr -d ' ') bytes)"
}

docker build -f "$demo/Dockerfile" -t "$image" .

# The examples, as the README renders them: the XML in, a PDF out.
mkdir "$out/examples"
for xml in examples/*.xml; do
    name=$(basename "$xml" .xml)
    docker run --rm -v "$PWD/examples:/examples:ro" -v "$out/examples:/out" "$image" "/examples/$name.xml" "/out/$name.pdf"
    check_pdf "$out/examples/$name.pdf"
done

# No arguments: the demo builds a document with the L builder and writes it to /examples/output.pdf.
mkdir "$out/builder"
docker run --rm -v "$out/builder:/examples" "$image"
check_pdf "$out/builder/output.pdf"
