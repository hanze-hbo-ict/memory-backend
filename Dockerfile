FROM php:8.2-cli
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

# Create the database (shouldn't really be used in production environments)
RUN ["php", "bin/console", "doctrine:schema:update", "--force", "--complete"]

# Run the application
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]

EXPOSE 8000
