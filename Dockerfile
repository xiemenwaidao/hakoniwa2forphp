FROM debian:stretch

# Update sources.list to use archive.debian.org
RUN echo "deb http://archive.debian.org/debian stretch main" > /etc/apt/sources.list && \
    echo "deb http://archive.debian.org/debian-security stretch/updates main" >> /etc/apt/sources.list && \
    echo 'Acquire::Check-Valid-Until "false";' > /etc/apt/apt.conf.d/10no-check-valid-until

# Install Apache and build dependencies
# 追加: Apache開発ツール
RUN apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -y \
    apache2 \
    apache2-dev \
    wget \
    build-essential \
    libxml2-dev \
    libssl-dev \
    bison \
    flex \
    default-libmysqlclient-dev \
    && rm -rf /var/lib/apt/lists/*

# Set up build environment
ENV PHP_VERSION=4.2.3

# Download and compile PHP 4.2.3
# パス修正: apxs2 → apxs
RUN wget https://museum.php.net/php4/php-${PHP_VERSION}.tar.gz && \
    tar xzf php-${PHP_VERSION}.tar.gz && \
    cd php-${PHP_VERSION} && \
    ./configure \
        --with-apxs2=/usr/bin/apxs \
        --with-mysql \
        --enable-track-vars \
        --enable-trans-sid \
        --enable-memory-limit \
        --with-config-file-path=/usr/local/lib \
    && make \
    && make install \
    && cp php.ini-dist /usr/local/lib/php.ini

# Configure Apache
RUN a2enmod rewrite && \
    echo "AddType application/x-httpd-php .php" >> /etc/apache2/apache2.conf && \
    echo "LoadModule php4_module /usr/lib/apache2/modules/libphp4.so" >> /etc/apache2/apache2.conf

WORKDIR /var/www/html

EXPOSE 80

CMD ["apache2ctl", "-D", "FOREGROUND"]
