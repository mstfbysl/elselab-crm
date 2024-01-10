# Use webdevops/php-nginx as the base image
FROM webdevops/php-nginx:8.2

# php-fpm.conf file
COPY /docker/application.conf /opt/docker/etc/php/fpm/pool.d/application.conf

# Web Document Root
ENV WEB_DOCUMENT_ROOT=/app/public

# PHP Extensions
ENV PHP_DISMOD=calendar,exiif,ffi,ldap,mysqli,imap,sockets,sysvmsg,sysvsm,sysvshm,shmop,zip,gd,apcu,vips,imagick,amqp

# Workdir
WORKDIR /app/

# Copy the Composer files into the Docker image
RUN apt-get update && apt-get install -y --no-install-recommends

# Copy other files
COPY . /app/.

# Create Nginx log and cache directories
RUN mkdir -p /var/log/nginx && mkdir -p /var/cache/nginx

# Install dependencies by running Composer
RUN composer install --optimize-autoloader --ignore-platform-reqs

# Install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash -
RUN apt-get update && apt-get install -y nodejs

# Install Yarn
RUN npm install -g yarn

# Run Yarn install and production build
RUN yarn install
RUN yarn production

# Change ownership to 'application' user
RUN chown -R application:application .

# Specify the required port
EXPOSE 80

# Start Supervisor when the container runs
CMD ["supervisord", "--nodaemon", "--configuration", "/etc/supervisor/supervisord.conf"]
