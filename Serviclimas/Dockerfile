FROM php:8.2-apache

# Habilitar el módulo de reescritura de Apache por si acaso
RUN a2enmod rewrite

# Copiar todo el proyecto al servidor
COPY . /var/www/html/

# Definimos la variable de entorno
ENV APACHE_DOCUMENT_ROOT /var/www/html/raiz_publica

# ¡Con comillas dobles para que jale la variable bien machín!
RUN sed -ri -e 's!/var/www/html!/var/www/html/raiz_publica!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!/var/www/html/raiz_publica!g' /etc/apache2/apache2.conf

# Exponer el puerto estándar web
EXPOSE 80
