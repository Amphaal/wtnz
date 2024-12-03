FROM phpswoole/swoole:latest

RUN apt-get -y update \
&& apt-get install -y libicu-dev inotify-tools \
&& docker-php-ext-configure intl \
&& docker-php-ext-install intl
