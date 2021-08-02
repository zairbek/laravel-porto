FROM php:7.4

###########################################################################
# Base:
###########################################################################
RUN set -eux; \
    apt-get update; \
    apt-get upgrade -y;

###########################################################################
# ZIP module
###########################################################################
RUN apt-get install -yqq \
        zip \
        unzip \
        libzip-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

###########################################################################
# Opcache:
###########################################################################

ARG INSTALL_OPCACHE=true

RUN if [ ${INSTALL_OPCACHE} = true ]; then \
    docker-php-ext-install opcache \
;fi
# Copy opcache configration
COPY .docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini


###########################################################################
# Composer:
###########################################################################
ARG INSTALL_COMPOSER=true

RUN if [ ${INSTALL_COMPOSER} = true ]; then \
    curl -sS https://getcomposer.org/installer | php -- \
            --install-dir=/usr/local/bin \
            --filename=composer \
       && chmod +x /usr/local/bin/composer \
;fi



WORKDIR /var/www

CMD [ "php", "/var/www/artisan", "serve", "--host", "0.0.0.0"]
