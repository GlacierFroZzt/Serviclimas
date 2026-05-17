FROM php:8.2-apache

# Habilitar el módulo de reescritura de Apache
RUN a2enmod rewrite

# Copiar todo el proyecto al servidor
COPY . /var/www/html/

# ¡ESTA LÍNEA ES PARA VER QUÉ ESTÁ PASANDO, VIEJÓN!
# Nos va a decir en los logs qué carpetas existen de verdad en la raíz
RUN ls -la /var/www/html/

# Configurar el DocumentRoot a mano limpiecita sin variables fallas
RUN sed -ri -e 's!/var/www/html!/var/www/html/raiz_publica!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!/var/www/html/raiz_publica!g' /etc/apache2/apache2.conf

# Exponer el puerto estándar web
EXPOSE 80
