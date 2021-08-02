FROM debian:buster-slim

RUN apt-get update && apt-get -y install \
    vim \
    wget \
    procps \
    iputils-ping \
    screen \
    aptitude \
    rsync \
    nginx \
    default-mysql-server \
    redis

RUN apt-get update && apt-get -y install \
    php7.3 \
    php7.3-cli \
    php7.3-curl \
    php7.3-fpm \
    php7.3-gd \
    php7.3-json \
    php7.3-mbstring \
    php7.3-mysql \
    php7.3-simplexml \
    php7.3-xml \
    php7.3-memcached \
    php7.3-readline \
    php7.3-opcache \
    php7.3-xdebug \
    php7.3-soap \
    php-redis \
    php-imagick \
    composer

RUN mkdir /run/php
RUN mkdir -p /mariadb/databases
RUN mkdir -p /mariadb/dumps

COPY ./mariadb/databases /mariadb/databases
COPY ./mariadb/dumps /mariadb/dumps
COPY ./mariadb.sh /mariadb.sh

COPY ./mariadb/server.conf /etc/mysql/mariadb.conf.d/50-server.cnf
COPY ./mariadb/debian.conf /etc/mysql/debian.cnf
RUN chmod 0644 /etc/mysql/mariadb.conf.d/50-server.cnf && \
    chmod 0644 /etc/mysql/debian.cnf

RUN /bin/bash /mariadb.sh
RUN rm -rf /mariadb.sh

COPY ./nginx/default.conf /etc/nginx/sites-available/default
COPY ./php/php.ini /etc/php/7.3/cli/php.ini
COPY ./php/php.ini /etc/php/7.3/fpm/php.ini
COPY ./php/pool.conf /etc/php/7.3/fpm/pool.d/www.conf

COPY ./entrypoint.sh /

VOLUME ["/var/www/html", "/var/lib/mysql"]

ENTRYPOINT ["/bin/bash", "/entrypoint.sh"]

EXPOSE 80 443 3306 6379

CMD ["/bin/bash"]

