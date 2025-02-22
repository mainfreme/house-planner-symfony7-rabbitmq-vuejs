FROM php:8.4-fpm

# Instalacja zależności systemowych
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    wget \
    libzip-dev \
    libyaml-dev \
    zlib1g-dev \
    libonig-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    libssl-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install xml \
    && docker-php-ext-install pdo_mysql zip \
    && docker-php-ext-install sockets

# Konfiguracja Git (zamień na swoje dane)
RUN git config --global user.name "Jakub Białoń"
RUN git config --global user.email "bialonj@gmail.com"

# Instalacja Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalacja Symfony CLI
RUN wget https://get.symfony.com/cli/installer -O - | bash && \
    mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Ustawienie katalogu roboczego
WORKDIR /var/www/html

# Instalacja Symfony
RUN symfony new Sales --version="7.1.*"

# Instalacja rozszerzenia YAML
RUN pecl install yaml && docker-php-ext-enable yaml
