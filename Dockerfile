FROM php:7.3-apache
ENV COMPOSER_ALLOW_SUPERUSER=1
# Install system dependencies
RUN apt-get update && \
    apt-get install -y --no-install-recommends git unzip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --version=1.10.22 --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"

# Install Node.js and npm
RUN curl -sL https://deb.nodesource.com/setup_14.x | bash - && \
    apt-get install -y nodejs

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy Apache vhost file
COPY vhost.conf /etc/apache2/sites-available/000-default.conf

# Set the working directory
WORKDIR /var/www/html



# Copy Composer and NPM configuration files

#COPY composer.json composer.lock package.json /var/www/html/
#RUN composer self-update

# Install PHP dependencies
#RUN composer install --no-scripts --no-autoloader

# Install Node.js dependencies
#RUN npm install

# Copy the application code to the container
COPY . /var/www/html

# Finish composer autoload optimization
#RUN composer dump-autoload --optimize --classmap-authoritative --no-dev

EXPOSE 80
CMD ["apache2-foreground"]
