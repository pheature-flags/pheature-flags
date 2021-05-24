FROM composer:2.0 as composer

FROM php:8.0-alpine

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache $PHPIZE_DEPS git \
    && pecl install xdebug-3.0.0 \
    && docker-php-ext-enable xdebug

#Disable XDebug by default
ENV XDEBUG_MODE=off

WORKDIR /code

VOLUME /code
