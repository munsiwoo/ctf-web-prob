FROM php:8.2-apache

RUN apt-get update && \
    apt-get install -y libsqlite3-dev zlib1g-dev libzip-dev && \
    docker-php-ext-install pdo_sqlite pdo && \
    rm -rf /var/lib/apt/lists/*

RUN mkdir -p /db && \
    chown -R www-data:www-data /db && \
    chmod -R 775 /db

WORKDIR /var/www/html
EXPOSE 80

CMD ["apache2-foreground"]