# Основа: официальный PHP 8.3 с Apache
FROM php:8.3-apache

# Устанавливаем системные зависимости и расширения
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    libonig-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install mbstring zip \
    && a2enmod rewrite

# Копируем проект в контейнер
COPY . /var/www/html/

# Даём права Apache на запись (для data.json)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
