FROM php:5.6-apache
 
RUN apt-get update && apt-get install -y \
    git \
    zlib1g-dev \
    nano \
    sendmail-bin \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    gnupg 

RUN apt-get install -y \ 
    build-essential \ 
    libssl-dev \ 
    libjpeg-dev \ 
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libzip-dev \
    zip \
    libmcrypt-dev

RUN docker-php-ext-install \
    pdo_mysql \
    mysqli \
    gd \
    mbstring 
    
RUN docker-php-ext-install -j$(nproc) iconv mcrypt

RUN docker-php-ext-configure gd --with-freetype --with-jpeg=/usr/include/ --enable-gd
RUN docker-php-ext-install -j$(nproc) gd

RUN a2enmod rewrite
 
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/bin/composer

RUN usermod -u 1000 www-data

RUN a2enmod headers

RUN apt-get clean -y

WORKDIR /var/www/html
