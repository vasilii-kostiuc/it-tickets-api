#!/bin/bash
set -e

echo "Проверка папок Laravel..."
mkdir -p storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache


if [ ! -f vendor/autoload.php ]; then
    echo "Устанавливаю зависимости Composer..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

echo "Ожидание готовности PostgreSQL ($PGSQL_DB_HOST:$PGSQL_DB_PORT)..."
until PGPASSWORD="$PGSQL_DB_PASSWORD" psql -h "$PGSQL_DB_HOST" -U "$PGSQL_DB_USERNAME" -p "$PGSQL_DB_PORT" -d "$PGSQL_DB_DATABASE" -c '\q' 2>/dev/null; do
  echo "PostgreSQL еще не готов, жду..."
  sleep 2
done
echo "PostgreSQL доступен!"


echo "Запускаю миграции..."
php artisan migrate --force

echo "Запуск основного процесса..."
exec php-fpm -F
