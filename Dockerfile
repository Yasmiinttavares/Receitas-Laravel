FROM php:8.3-apache

# Instalar extensões necessárias
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpq-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/receitas-app/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite

# Copiar projeto
WORKDIR /var/www/html/receitas-app
COPY . /var/www/html/

# Instalar dependências PHP
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev --working-dir=/var/www/html/receitas-app

# Permissões
RUN chown -R www-data:www-data /var/www/html/receitas-app/storage /var/www/html/receitas-app/bootstrap/cache
RUN chmod -R 775 /var/www/html/receitas-app/storage /var/www/html/receitas-app/bootstrap/cache

EXPOSE 80
