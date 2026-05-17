FROM php:8.2-apache

# Instalar las extensiones de PDO y MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Habilitar el módulo de reescritura de Apache
RUN a2enmod rewrite

# Copiar todo el proyecto al servidor
COPY . /var/www/html/

# Ponemos la ruta fija en el sed para que no haya fallas de variables
RUN sed -ri -e 's!/var/www/html!/var/www/html/raiz_publica!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!/var/www/html/raiz_publica!g' /etc/apache2/apache2.conf

# Exponer el puerto estándar web
EXPOSE 80
