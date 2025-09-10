FROM php:8.2-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libgd-dev \
    zip \
    unzip \
    git \
    curl \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo pdo_sqlite bcmath zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN chown -R www-data:www-data /var/www

COPY ./docker/entrypoint.sh /usr/local/bin/entrypoint.sh
# 2. Make the script executable
RUN chmod +x /usr/local/bin/entrypoint.sh
# 3. Set the entrypoint
ENTRYPOINT ["entrypoint.sh"]
# 4. Set the default command that the entrypoint will execute
CMD ["php-fpm"]
