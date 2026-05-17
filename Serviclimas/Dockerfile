FROM php:8.2-apache

# Habilitar el módulo de reescritura de Apache por si acaso
RUN a2enmod rewrite

# Copiar todo el proyecto al servidor
COPY . /var/www/html/

# Decirle a Apache que la raíz pública del sitio es su carpeta "raiz_publica"
ENV APACHE_DOCUMENT_ROOT /var/www/html/raiz_publica
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Exponer el puerto estándar web
EXPOSE 80