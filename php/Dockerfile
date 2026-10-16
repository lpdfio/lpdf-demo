FROM php:8.3-cli

# Install wasmtime — required by the lpdfio/lpdf package to run the WASI binary
RUN apt-get update && apt-get install -y --no-install-recommends curl xz-utils unzip \
    && curl -sSf https://wasmtime.dev/install.sh | bash \
    && mv /root/.wasmtime/bin/wasmtime /usr/local/bin/wasmtime \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /demo

COPY php/composer.json ./
RUN composer install --no-interaction

COPY php/demo.php ./
COPY examples/ /examples/

# Usage (from the repo root):
#   docker build -f php/Dockerfile -t lpdf-php-demo .
#   docker run --rm lpdf-php-demo


ENTRYPOINT ["php", "demo.php"]
