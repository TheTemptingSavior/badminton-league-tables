#!/bin/sh

ARTISAN=/var/www/html/api/artisan

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

# Regardless of the role we use a connection to the database is still required
php $ARTISAN manual:check-database --attempts=5 --timeout=3
retVal=$?
if [ $retVal -ne 0 ]; then
    echo "Could not create a connection to the database. Is it up?";
    exit 1;
else
    echo "Database connection successful"
fi

# Ensure the database is setup and check for the default user
echo "Setting up the database"
php $ARTISAN migrate --no-interaction --force
echo "Creating the default admin user"
php $ARTISAN user:make-default


case $MODE in
    app)
        echo "Starting main Lumen application";
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
          php $ARTISAN schedule:run --verbose --no-interaction &
          sleep 120
        done
        ;;
    *)
        echo "Unknown role '$MODE'. Exiting";
        exit 1;
        ;;
esac