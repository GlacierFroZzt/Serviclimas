FROM php:8.2-apache

# 1. Instalar las extensiones de PDO y MySQL
RUN docker-php-ext-install pdo pdo_mysql

# 2. Habilitar el módulo de reescritura de Apache
RUN a2enmod rewrite

# 3. Definir la ruta del DocumentRoot real como variable
ENV APACHE_DOCUMENT_ROOT /var/www/html/raiz_publica

# 4. Cambiar las rutas en la configuración de Apache apuntando a la raíz pública real
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 5. Copiar todo el proyecto al contenedor
COPY . /var/www/html/

# Exponer el puerto estándar web
EXPOSE 80
