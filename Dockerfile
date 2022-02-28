FROM php:7.4-fpm
COPY ./app /srv/app
COPY ./docker/php/memory_limit.ini /usr/local/etc/php/conf.d
WORKDIR /srv/app

RUN set -eux; \
    echo "\n -- Installing packages -- \n"; \
    apt-get update; \
    apt-get install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip; \
    docker-php-ext-install intl opcache pdo pdo_mysql; \
    pecl install apcu; \
    docker-php-ext-enable apcu; \
    docker-php-ext-configure zip; \
    docker-php-ext-install zip; \
    \
    echo "\n -- Installing composer -- \n"; \
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" ; \
    php composer-setup.php --install-dir=/usr/bin --filename=composer; \
    php -r "unlink('composer-setup.php');"; \
    \
    echo "\n -- Installing application dependencies -- \n"; \
    composer install --prefer-dist --no-progress --no-interaction;