FROM php:8.2-apache

# Habilitar el módulo de reescritura de Apache
RUN a2enmod rewrite

# ¡ESTA LÍNEA ES CRÍTICA! Instala las extenshiones de PDO y MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Copiar todo el proyecto al servidor
COPY . /var/www/html/

# Configurar el DocumentRoot directo a la ruta real
RUN sed -ri -e 's!/var/www/html!/var/www/html/Serviclimas/raiz_publica!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!/var/www/html/Serviclimas/raiz_publica!g' /etc/apache2/apache2.conf

# Exponer el puerto estándar web
EXPOSE 80
