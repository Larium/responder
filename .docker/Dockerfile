FROM php:8.1-cli
WORKDIR "/opt/php"

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update && \
    apt-get install -y zip libzip-dev && \
    pecl channel-update pecl.php.net && \
    pecl install xdebug && \
    docker-php-ext-install bcmath zip && \
    docker-php-source delete && \
    rm -r /tmp/* /var/cache/*

COPY ./.docker/php-ini-overrides.ini /usr/local/etc/php/conf.d/99-overrides.ini
COPY ./.docker/xdebug.ini /usr/local/etc/php/conf.d/15-xdebug.ini

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
     && php composer-setup.php \
     && php -r "unlink('composer-setup.php');" \
     && chmod +x composer.phar \
     && mv composer.phar /usr/local/bin/composer

RUN curl -L https://cs.symfony.com/download/php-cs-fixer-v3.phar -o php-cs-fixer \
    && chmod a+x php-cs-fixer \
    && mv php-cs-fixer /usr/bin/php-cs-fixer
