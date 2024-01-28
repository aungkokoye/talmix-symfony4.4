FROM php:7.1-apache

RUN apt-get update && apt-get install vim libzip-dev libpng-dev libonig-dev zlib1g-dev -y

RUN pecl install xdebug-2.9.8

RUN echo "zend_extension=xdebug.so" >> /usr/local/etc/php/conf.d/xdebug.ini  \
     && echo "xdebug.mode=develop,coverage,debug" >> /usr/local/etc/php/conf.d/xdebug.ini \
     && echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/xdebug.ini \
     && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/xdebug.ini \
     && echo "xdebug.log=/dev/stdout" >> /usr/local/etc/php/conf.d/xdebug.ini \
     && echo "xdebug.log_level=0" >> /usr/local/etc/php/conf.d/xdebug.ini \
     && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/xdebug.ini \
     && echo "xdebug.client_host=host.docker.internal " >> /usr/local/etc/php/conf.d/xdebug.ini

RUN docker-php-ext-install mbstring zip gd pdo_mysql mysqli

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN a2enmod rewrite