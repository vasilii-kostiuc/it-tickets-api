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

echo "Ожидание готовности PostgreSQL ($DB_HOST:$DB_PORT)..."
until PGPASSWORD="$DB_PASSWORD" psql -h "$DB_HOST" -U "$DB_USERNAME" -p "$DB_PORT" -d "$DB_DATABASE" -c '\q' 2>/dev/null; do
  echo "PostgreSQL еще не готов, жду..."
  sleep 2
done
echo "PostgreSQL доступен!"


echo "Запускаю миграции..."
php artisan migrate

echo "Запуск основного процесса..."
exec "$@"
