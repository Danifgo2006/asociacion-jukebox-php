FROM php:8.5-apache-bookworm

# Instalar dependencias necesarias
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libpq-dev \
        default-libmysqlclient-dev \
    && docker-php-ext-install \
        pdo_pgsql \
        pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# Copiar configuración de Apache
COPY docker/apache/jukebox.conf /etc/apache2/sites-available/jukebox.conf

# Activar el nuevo sitio y desactivar el predeterminado
RUN a2dissite 000-default.conf \
    && a2ensite jukebox.conf