#!/usr/bin/env bash

if [[ ! -d /var/lib/mysql/mysql ]]; then
    if [[ -d /var/www/html/.docker/mariadb ]]; then
        bash /var/www/html/.docker/mariadb.sh
    fi
else
    echo "Staring mariadb server..."
    /etc/init.d/mysql restart
fi

echo "Staring redis..."
/etc/init.d/redis-server restart

echo "Starting php-fpm..."
/etc/init.d/php7.3-fpm restart

echo "Starting nginx..."
/etc/init.d/nginx restart

exec "$@"