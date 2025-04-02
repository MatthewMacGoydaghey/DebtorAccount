FROM php:8.3.19-fpm-bookworm

RUN apt-get update && apt-get install nano wget unzip cron curl openssl libzip-dev libccid pcscd iputils-ping -y \
    && docker-php-ext-install zip pdo pdo_mysql \
    && curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy cronjob setup fro laravel scheduler
COPY .docker/php-fpm/cron/cron.txt /etc/docker/cron/cron.txt

RUN mkdir -p /var/www/html/storage/framework/cache/data \
    && /usr/bin/crontab -u www-data /etc/docker/cron/cron.txt \
    && chown -R www-data:www-data /var/www/html/

ADD .docker/php-fpm/csp /tmp/src

RUN unzip /tmp/src/linux-amd64_deb.zip -d /tmp/src/linux-amd64_deb && \
    cd /tmp/src/linux-amd64_deb && \
    chmod 775 install.sh && \
    ./install.sh lsb-cprocsp-devel cprocsp-pki-cades cprocsp-pki-phpcades

#COPY .docker/php/php.ini /usr/local/etc/php/php.ini
COPY ./app /var/www/html
COPY ./app/storage /var/www/html/storage
COPY .env /var/www/html/.env

RUN chown -R www-data:www-data /var/www/
RUN chmod -R 755 /var/www/

WORKDIR /var/www/html
