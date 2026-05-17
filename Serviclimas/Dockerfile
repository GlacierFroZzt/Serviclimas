FROM php:8.2-apache

# Habilitar el módulo de reescritura de Apache
RUN a2enmod rewrite

# Copiar todo el proyecto al servidor
COPY . /var/www/html/

# ¡Aquí está el truco perrón, viejón! Le damos la ruta real con la carpeta Serviclimas
RUN sed -ri -e 's!/var/www/html!/var/www/html/Serviclimas/raiz_publica!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!/var/www/html/Serviclimas/raiz_publica!g' /etc/apache2/apache2.conf

# Exponer el puerto estándar web
EXPOSE 80
