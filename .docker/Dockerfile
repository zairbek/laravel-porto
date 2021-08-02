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
