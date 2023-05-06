FROM php:7.2-fpm

RUN \
  apt update \
  && apt install -y bash git zip\
  && docker-php-ext-install pdo_mysql \
  && pecl install redis-4.3.0 \
  && docker-php-ext-enable redis \
  # Cleanup
  && rm -rf /tmp/pear/* /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

ADD https://raw.githubusercontent.com/vishnubob/wait-for-it/9995b721327eac7a88f0dce314ea074d5169634f/wait-for-it.sh /usr/bin/wait-for-it.sh
RUN chmod +x /usr/bin/wait-for-it.sh

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
