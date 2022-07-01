FROM php:7.2-fpm

# apt install
RUN apt-get update && \
    apt-get -y install \
        gnupg2 && \
    apt-key update && \
    apt-get update && \
    apt-get -y install \
            wget \
            zip \
            git \
            g++ \
            curl \
            imagemagick \
            libfreetype6-dev \
            libcurl3-dev \
            libicu-dev \
            libfreetype6-dev \
            libjpeg-dev \
            libjpeg62-turbo-dev \
            libmagickwand-dev \
            libpq-dev \
            libpng-dev \
            libxml2-dev \
            zlib1g-dev \
            mysql-client \
            postgresql-server-dev-all \
            openssh-client \
            nano \
            unzip \
        --no-install-recommends && \
        apt-get clean && \
        rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# docker php install
RUN docker-php-ext-install \
                        zip \
                        intl \
                        mbstring \
                        pdo_mysql \
                        exif \
                        pdo_pgsql \
                        pdo \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd

# xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

#composer
ENV COMPOSER_HOME /composer
ENV PATH /usr/local/bin:$PATH
ENV COMPOSER_ALLOW_SUPERUSER 1

COPY ./composer.sh /
COPY ./php.ini /usr/local/etc/php/
COPY ./www.conf /usr/local/etc/php/

WORKDIR /app

RUN sh /composer.sh && rm /composer.sh
RUN usermod -u 1000 www-data

VOLUME /root/.composer

WORKDIR /app
EXPOSE 9000
EXPOSE 9005
CMD ["php-fpm"]
