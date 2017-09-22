FROM php:7.1-fpm

RUN apt-get update \
	&& apt-get install -y \
		zip \
		unzip \
		vim \
		wget \
		curl \
		git \
		mysql-client \
		moreutils \
		dnsutils \
		zlib1g-dev \
		libicu-dev \
		libmemcached-dev \
		g++ \
    && rm -rf /var/lib/apt/lists/* \
	&& docker-php-ext-configure intl \
    && docker-php-ext-install pdo pdo_mysql opcache intl mysqli

ADD . /apps/

RUN chmod 777 -Rf /apps

CMD php-fpm -F
