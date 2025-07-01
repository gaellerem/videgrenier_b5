# Étape 1 : Utiliser l'image PHP avec Apache
FROM php:8.3.0-apache

ARG APACHE_CONF
ARG APP_ENV
# Étape 2 : Installer les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip pdo pdo_mysql

# Installer Composer (gestionnaire de dépendances PHP)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Activer mod_rewrite pour Apache (utile pour les routes)
RUN a2enmod rewrite
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
COPY apache/${APACHE_CONF} /etc/apache2/sites-available/000-default.conf

# COPY docker/${APP_ENV}/.env /var/www/html/.env
COPY . /var/www/html

# Définir le répertoire de travail
WORKDIR /var/www/html

# Installer les dépendances via Composer
RUN composer install --no-dev --no-interaction

# Définir les bonnes permissions pour Apache
RUN chown -R www-data:www-data /var/www/html

# Exposer le port 80 pour Apache
EXPOSE 80

# Démarrer Apache en mode premier plan
CMD ["apache2-foreground"]
