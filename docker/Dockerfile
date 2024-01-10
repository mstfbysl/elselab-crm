# We are using the webdevops/php-nginx based Docker image
FROM webdevops/php-nginx:8.2

# Web Document Root
ENV WEB_DOCUMENT_ROOT=/app/public

# PHP Extensions
ENV PHP_DISMOD=calendar,exiif,ffi,ldap,mysqli,imap,sockets,sysvmsg,sysvsm,sysvshm,shmop,zip,gd,apcu,vips,imagick,amqp

WORKDIR /app/

RUN apt-get update && apt-get install -y --no-install-recommends

# Copy other files
COPY . /app/.

# Create Nginx log and cache directories
RUN mkdir -p /var/log/nginx && mkdir -p /var/cache/nginx

# Install dependencies by running Composer
RUN composer install --optimize-autoloader --ignore-platform-reqs

# Change ownership to 'application' user
RUN chown -R application:application .

# Specify the required port
EXPOSE 80

# Start Supervisor when the container runs
CMD ["supervisord", "--nodaemon", "--configuration", "/etc/supervisor/supervisord.conf"]
