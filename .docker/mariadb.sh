#!/usr/bin/env bash

function mysql_restart
{
    killall mysqld
    /etc/init.d/mysql restart
}

function install_databases
{
    for database in $1/databases/*.sql; do
        echo  "Installing database database $database ...";
        mysql -uroot -proot < ${database}
        echo "done"

        database_basename=$(basename ${database});
        database_basename=${database_basename%.*}

        path_to_database_dumps="$1/dumps/$database_basename"

        if [[ -d ${path_to_database_dumps} ]]; then
            echo "Installing database dumps ..."
            for dump in ${path_to_database_dumps}/*.sql; do
                echo "* $dump"
                mysql -uroot -proot < ${dump}
                echo "done"
            done
        fi
    done
}

if [[ ! -d /var/lib/mysql/mysql ]]; then
    mysql_install_db --defaults-file=/etc/mysql/mariadb.cnf
fi

mysql_restart

mysql -e "
UPDATE mysql.user SET password=password('root') WHERE user='root';
UPDATE mysql.user SET plugin='' WHERE user='root';
DELETE FROM mysql.user WHERE host='localhost' AND user = '';
UPDATE mysql.user SET host='%' WHERE user='root' AND host='localhost';
UPDATE mysql.user SET host='%' WHERE user='root';
";

mysql -e "FLUSH PRIVILEGES;";

if [[ -d /mariadb ]]; then
    install_databases /mariadb
else
    install_databases /var/www/html/.docker/mariadb
fi