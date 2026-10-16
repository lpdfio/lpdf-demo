FROM python:3.12-slim

# Install wasmtime — required by the lpdfio-lpdf package to run the WASI binary
RUN apt-get update && apt-get install -y --no-install-recommends curl xz-utils \
    && curl -sSf https://wasmtime.dev/install.sh | bash \
    && mv /root/.wasmtime/bin/wasmtime /usr/local/bin/wasmtime \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /demo

COPY python/requirements.txt ./
RUN pip install --no-cache-dir -r requirements.txt

COPY python/demo.py ./
COPY examples/ /examples/

# Usage (from the repo root):
#   docker build -f python/Dockerfile -t lpdf-python-demo .
#   docker run --rm lpdf-python-demo


ENTRYPOINT ["python", "demo.py"]
