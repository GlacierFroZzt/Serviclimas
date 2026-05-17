FROM php:8.2-apache

# 1. Instalar las extensiones de PDO y MySQL
RUN docker-php-ext-install pdo pdo_mysql

# 2. Habilitar el módulo de reescritura de Apache
RUN a2enmod rewrite

# 3. Copiar todo el proyecto al contenedor PRIMERO
COPY . /var/www/html/

# 4. Asegurar que la carpeta exista y darle permisos chilos de Apache
RUN mkdir -p /var/www/html/raiz_publica && chown -R www-data:www-data /var/www/html/

# 5. Configurar el DocumentRoot directo en las entrañas de Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/raiz_publica
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Exponer el puerto estándar web
EXPOSE 80
