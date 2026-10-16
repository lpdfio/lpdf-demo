FROM node:22-slim

WORKDIR /demo

COPY node/package.json ./
RUN npm install

COPY node/demo.js ./
COPY examples/ /examples/

# Usage (from the repo root):
#   docker build -f node/Dockerfile -t lpdf-node-demo .
#   docker run --rm lpdf-node-demo


ENTRYPOINT ["node", "demo.js"]
