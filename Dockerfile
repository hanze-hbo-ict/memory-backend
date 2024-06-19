FROM php:alpine

# Install composer and other necessary tools
RUN apk add --no-cache curl git unzip bash

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Symfony CLI
RUN wget https://get.symfony.com/cli/installer -O - | bash && \
    mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Create volume for the database
VOLUME [ "/usr/src/memory-backend/var" ]

# Copy the application
COPY . /usr/src/memory-backend
WORKDIR /usr/src/memory-backend

# Allow Composer plugins and install dependencies
RUN export COMPOSER_ALLOW_SUPERUSER=1 && composer install

# Create the database (shouldn't really be used in production environments)
RUN ["php", "bin/console", "doctrine:schema:update", "--force"]

# Run the application
WORKDIR /usr/src/memory-backend/public
CMD ["php", "-S", "0.0.0.0:8000"]

EXPOSE 8000