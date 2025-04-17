# Étape 1 : Utiliser l'image PHP avec Apache
FROM php:8.3.0-apache

# Étape 2 : Installer les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip \
    && docker-php-ext-install pdo pdo_mysql

# Installer Composer (gestionnaire de dépendances PHP)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Activer mod_rewrite pour Apache (utile pour les routes)
RUN a2enmod rewrite

# Copier le code source de l'application
COPY ./public /var/www/html
COPY ./App /var/www/html/App
COPY ./Core /var/www/html/Core
COPY ./composer.json /var/www/html/composer.json

# Définir le répertoire de travail
WORKDIR /var/www/html

# Installer les dépendances via Composer
RUN composer install --no-dev --no-interaction

# Copier le fichier de configuration Apache (si nécessaire)
COPY vhost.conf /etc/apache2/sites-available/000-default.conf

# Définir les bonnes permissions pour Apache
RUN chown -R www-data:www-data /var/www/html

# Exposer le port 80 pour Apache
EXPOSE 80

# Démarrer Apache en mode premier plan
CMD ["apache2-foreground"]
