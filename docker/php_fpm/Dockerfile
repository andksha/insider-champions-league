FROM phpdockerio/php74-fpm
WORKDIR "/application"

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install libffi-dev imagemagick php7.4-imagick php7.4-cli php7.4-pgsql php7.4-sqlite nano php7.4-redis php7.4-bcmath php7.4-zmq \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

COPY ./docker/php_fpm/php-ini-overrides.ini /etc/php/7.3/fpm/conf.d/99-overrides.ini
COPY ./ /application

RUN rm -rf /application/docker && chgrp -R www-data storage bootstrap/cache && chmod -R ug+rwx storage bootstrap/cache

#RUN php artisan queue:work