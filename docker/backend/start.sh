#!/usr/bin/env sh

set -e

role=${CONTAINER_ROLE:-app}


if [ "$role" = "app" ]; then
  echo "Running PHP application";
  exec apache2-foreground

elif [ "$role" = "queue" ]; then
  echo "Running the queue"
  php artisan queue:listen --no-interaction --verbose --tries=3 --timeout=90

elif [ "$role" = "scheduler" ]; then
  echo "Running as a scheduler"
  while [ true ]
  do
    php artisan schedule:run --verbose --no-interaction &
    sleep 60
  done

else
  echo "Could not find container role";
  exit 1
fi