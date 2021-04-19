#!/bin/sh

echo "Starting the scheduler"

while true
do
  php /var/www/html/api/artisan schedule:run --verbose --no-interaction &
  sleep 120
done
