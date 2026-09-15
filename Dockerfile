# Used only by docker-compose.yml, for running the site without installing
# XAMPP. Deploying to XAMPP does not need this file.
FROM php:8.2-apache

RUN docker-php-ext-install pdo_mysql && a2enmod rewrite

RUN { \
      echo "display_errors=On"; \
      echo "error_reporting=E_ALL"; \
      echo "upload_max_filesize=8M"; \
      echo "post_max_size=8M"; \
    } > /usr/local/etc/php/conf.d/duetcs.ini
