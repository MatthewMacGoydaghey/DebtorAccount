FROM php:8.2-cli

RUN apt-get update && apt-get install -y supervisor

RUN docker-php-ext-install pdo pdo_mysql

#RUN mkdir -p /etc/supervisor/logs/supervisord.log

COPY .docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

CMD ["/usr/bin/supervisord"]