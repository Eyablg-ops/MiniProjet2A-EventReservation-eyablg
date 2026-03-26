# Dockerfile
FROM php:8.4-fpm
 
# Installer les outils systeme necessaires
RUN apt-get update && apt-get install -y \
    git curl libpq-dev libonig-dev libzip-dev libicu-dev zip unzip default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql opcache mbstring zip intl
 
# Installer Composer directement depuis son image officielle
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
 
# Configurer le repertoire de travail
WORKDIR /var/www
 
# Copier les fichiers du projet
COPY . .

# Installer les dependances PHP
RUN composer install --optimize-autoloader

CMD ["php-fpm"]
