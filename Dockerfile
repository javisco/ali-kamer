# Utiliser une image officielle PHP avec Apache
FROM php:8.2-apache

# Installer les dépendances système nécessaires pour Laravel
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip

# Nettoyer le cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Installer les extensions PHP requises par Laravel (notamment pdo_pgsql pour Supabase)
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd

# Installer Composer (le gestionnaire de dépendances PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail dans le conteneur
WORKDIR /var/www/html

# Copier tous les fichiers du projet dans le conteneur
COPY . /var/www/html

# Configurer Apache pour pointer vers le dossier "public" de Laravel
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/conf-available/*.conf

# Activer le module de réécriture d'URL d'Apache (nécessaire pour les routes Laravel)
RUN a2enmod rewrite

# Installer les dépendances PHP via Composer en mode production
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Donner les bonnes permissions aux dossiers de stockage et de cache de Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Exposer le port 80 pour le serveur web
EXPOSE 80