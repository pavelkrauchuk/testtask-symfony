FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    wget \
    zlib1g-dev \
    libmcrypt-dev \
    libzip-dev \
    libicu-dev \
    git

RUN docker-php-ext-install pdo pdo_mysql zip intl opcache pdo_pgsql

RUN wget https://getcomposer.org/installer -O - -q | php -- --install-dir=/bin --filename=composer --quiet

RUN wget https://get.symfony.com/cli/installer -O - | bash

RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/conf.d/php.ini && \
    sed -i -e "s/memory_limit =.*/memory_limit = 1G/" /usr/local/etc/php/conf.d/php.ini

#RUN useradd -ms /bin/bash newuser
#
#USER newuser

WORKDIR /symfony
