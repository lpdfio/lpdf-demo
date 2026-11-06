FROM python:3.12-slim

WORKDIR /demo

COPY python/requirements.txt ./
RUN pip install --no-cache-dir -r requirements.txt

COPY python/demo.py ./
COPY examples/ /examples/

# Usage (from the repo root):
#   docker build -f python/Dockerfile -t lpdf-python-demo .
#   docker run --rm lpdf-python-demo


ENTRYPOINT ["python", "demo.py"]
