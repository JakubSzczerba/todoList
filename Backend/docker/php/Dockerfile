FROM php:8.2-fpm

RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash -

RUN apt-get update  \
    && apt-get install -y libpq-dev libpng-dev libzip-dev zip nodejs mariadb-client \
    && docker-php-ext-install zip pdo_mysql sysvsem gd bcmath \
    && apt-get clean \
    && curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

WORKDIR /var/www/ToDoList/Backend
