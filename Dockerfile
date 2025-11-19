FROM php:8.2-cli

# Instalar extensiones de PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pgsql pdo_pgsql

WORKDIR /app
COPY . /app

# Puerto que usa PHP built-in server
EXPOSE 10000

# Comando para iniciar servidor PHP
CMD ["php", "-S", "0.0.0.0:10000"]
