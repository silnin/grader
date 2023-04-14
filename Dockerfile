FROM php:8.1

USER root
WORKDIR /app

RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    git \
    libpng-dev \
    && docker-php-ext-install zip gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# create user app with uid and guid 1000
RUN useradd -u 1000 -ms /bin/bash app

# switch to user app
USER app

RUN git config --global user.email "peter@silnin.nl"
RUN git config --global user.name "Peter Keizer"

# install symfony cli
RUN curl -sS https://get.symfony.com/cli/installer | bash

COPY --chown=app:app ./src /app

RUN composer install

