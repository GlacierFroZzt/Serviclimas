FROM php:8.2-apache

# 1. Instalar las extensiones de PDO y MySQL
RUN docker-php-ext-install pdo pdo_mysql

# 2. Habilitar el módulo de reescritura de Apache
RUN a2enmod rewrite

# 3. Copiar todo el proyecto al contenedor primero que nada
COPY . /var/www/html/

# 4. Asegurar que la carpeta exista físicamente y darle sus permisos bien shilos
RUN mkdir -p /var/www/html/raiz_publica && chown -R www-data:www-data /var/www/html/

# 5. Modificar la configuración con rutas directas (con comillas dobles para que Linux no se sordeé)
RUN sed -ri -e "s!/var/www/html!/var/www/html/raiz_publica!g" /etc/apache2/sites-available/*.conf
RUN sed -ri -e "s!/var/www/html!/var/www/html/raiz_publica!g" /etc/apache2/apache2.conf

# Exponer el puerto estándar web
EXPOSE 80
