#!/bin/bash

until timeout 30 bash -c '</dev/tcp/db/3306' 2>/dev/null
do
  echo "Esperando a que la base de datos esté disponible..."
  sleep 5
done
echo "¡Base de datos lista!"

php artisan migrate --force

exec "$@"
