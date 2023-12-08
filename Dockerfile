FROM php:7.3-apache
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN apt-get update && \
    apt-get install -y --no-install-recommends git unzip

RUN docker-php-ext-install pdo_mysql

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --version=1.10.22 --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"

RUN curl -sL https://deb.nodesource.com/setup_14.x | bash - && \
    apt-get install -y nodejs

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

COPY vhost.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html


COPY composer.json composer.lock package.json /var/www/html/
RUN composer self-update
RUN composer install --no-scripts --no-autoloader
RUN npm install

COPY . /var/www/html

RUN composer dump-autoload --optimize --classmap-authoritative --no-dev

EXPOSE 80
CMD ["apache2-foreground"]
