FROM php:8.4-fpm

# Устанавливаем зависимости и расширения для PostgreSQL
RUN apt-get update && apt-get install -y \
    git curl unzip libpq-dev libonig-dev libpng-dev libxml2-dev zip \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring bcmath gd

RUN mkdir -p /var/www/storage /var/www/bootstrap/cache \
    && chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache


RUN apt update && apt install -y postgresql-client

# Устанавливаем Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Добавляем Composer global bin в PATH
ENV PATH="$PATH:/root/.composer/vendor/bin"

WORKDIR /var/www

ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]
