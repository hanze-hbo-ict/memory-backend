FROM php:8.2-cli
# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ENV COMPOSER_ALLOW_SUPERUSER 1

# Create volume for the database
VOLUME [ "/usr/src/memory-backend/var" ]

# Copy the application
COPY . /usr/src/memory-backend
WORKDIR /usr/src/memory-backend

# Install dependencies
RUN composer install

# mkdir config/jwt/
RUN ["mkdir", "config/jwt/"]
# openssl genrsa -out config/jwt/private.pem -aes256 4096 (without password)
RUN ["openssl", "genrsa", "-out", "config/jwt/private.pem", "4096"]
# openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
RUN ["openssl", "rsa", "-pubout", "-in", "config/jwt/private.pem", "-out", "config/jwt/public.pem"]
# Create the database (shouldn't really be used in production environments)
RUN ["php", "bin/console", "doctrine:schema:update", "--force", "--complete"]

# Run the application
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]

EXPOSE 8000
