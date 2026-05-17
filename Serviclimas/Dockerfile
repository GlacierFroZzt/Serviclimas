FROM php:8.2-apache

# 1. Habilitar el módulo de reescritura de Apache por si acaso
RUN a2enmod rewrite

# 2. Copiar todo el proyecto a la ruta por defecto
COPY . /var/www/html/

# 3. ¡La clave, viejón! Le cambiamos las variables internas a Apache directo
ENV APACHE_DOCUMENT_ROOT=/var/www/html/raiz_publica
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 4. Exponer el puerto estándar web
EXPOSE 80
