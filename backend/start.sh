#!/bin/sh

ARTISAN=/var/www/html/api

# Check to ensure we have a role
if [ -z "$1" ] && [ -z "$APP_ROLE" ]; then
    echo "Undefined application role. Provide the env var APP_ROLE or specify the role as an argument";
    exit 1
fi

# Cache it for later
if [ -z "$1" ]; then
    MODE=$APP_ROLE;
else
    MODE=$1;
fi

case $MODE in
    app)
        echo "Service Lumen application";
        php-fpm;
        ;;
    worker)
        echo "Starting Lumen worker daemon";
        php $ARTISAN queue:work
        ;;
    scheduler)
        echo "Starting as Lumen scheduler";
        while true
        do
          php /var/www/html/api/artisan schedule:run --verbose --no-interaction &
          sleep 120
        done
        ;;
    *)
        echo "Unknown role '$MODE'. Exiting";
        exit 1;
        ;;
esac
