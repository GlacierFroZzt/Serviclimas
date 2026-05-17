FROM php:8.2-apache

# 1. Instalar las extensiones de PDO y MySQL
RUN docker-php-ext-install pdo pdo_mysql

# 2. Habilitar el módulo de reescritura de Apache
RUN a2enmod rewrite

# 3. Copiar todo el proyecto al contenedor
COPY . /var/www/html/

# !!! LÍNEA DE PRUEBA: Nos va a mostrar en el log qué carpetas existen de veras !!!
RUN ls -la /var/www/html

# 4. Cambiar las rutas del Apache
RUN sed -ri -e "s!/var/www/html!/var/www/html/raiz_publica!g" /etc/apache2/sites-available/*.conf
RUN sed -ri -e "s!/var/www/html!/var/www/html/raiz_publica!g" /etc/apache2/apache2.conf

EXPOSE 80
