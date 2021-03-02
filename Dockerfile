FROM php:8-alpine
WORKDIR /app

RUN set -eux; \
    apk add --no-cache \
    openssl \
    bash \
    autoconf \
    make \
    g++ \
    zlib-dev \
    postgresql-dev

RUN pecl install xdebug \
    && docker-php-ext-install pdo pdo_pgsql intl \
    && docker-php-ext-enable xdebug

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /tmp

RUN wget https://get.symfony.com/cli/installer -O - | bash \
    && mv /root/.symfony/bin/symfony /usr/local/bin/symfony

