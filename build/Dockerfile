# Установка образа ubuntu
FROM ubuntu:latest

# Тег pocketmine
ARG PMMP_TAG="5.5.0-BETA1"

# Флаги компиляции php https://github.com/pmmp/PHP-Binaries/tree/php-8.2-latest
ARG COMPILE_SH_ARGS="-f -g"
ARG PMMP_REPO=pmmp/PocketMine-MP

RUN test ! -z ${PMMP_TAG} || (echo "Missing build-arg PMMP_TAG" && false)

# Обновляем пакеты
RUN apt-get update && apt-get install --no-install-recommends -y curl ca-certificates build-essential m4 gzip bzip2 bison git cmake autoconf automake pkg-config libtool libtool-bin re2c

RUN mkdir /build

# Клонируем pocketmine
WORKDIR /build
RUN git clone https://github.com/pmmp/PocketMine-MP.git -b ${PMMP_TAG} --recursive .

# Клонируем скрипт компиляции php
WORKDIR /build/build/
RUN rm -rf php
RUN git clone https://github.com/pmmp/PHP-Binaries.git -b php-8.2-latest --recursive php

# Запускаем скрипт компиляции php
WORKDIR /build/build/php
RUN ./compile.sh -t linux64 -j ${THREADS:-$(grep -E ^processor /proc/cpuinfo | wc -l)} ${COMPILE_SH_ARGS} -P $(echo ${PMMP_TAG} | cut -d "." -f1)

# Линкуем скомпилированный php
RUN ln -s /build/build/php/bin/php7/bin/php /usr/bin/php

# Устанавливаем composer
WORKDIR /build
RUN curl -L https://getcomposer.org/installer | php
RUN mv composer.phar /usr/bin/composer

# Устанавливаем зависимости
RUN composer install --classmap-authoritative --no-dev --prefer-source

# Запускаем тестовые скрипты
RUN if test -d build/preprocessor; then php build/preprocessor/PreProcessor.php --path=src --multisize; else echo "Skipping preprocessor step (preprocessor not found)"; fi
RUN php $(test -d vendor/pocketmine/bedrock-data && echo -n vendor/pocketmine/bedrock-data || echo -n src/pocketmine/resources/vanilla)/.minify_json.php

# Создаем phar
RUN php -dphar.readonly=0 build/server-phar.php --git $(git rev-parse HEAD)

# Just to make sure DevTools didn't false-positive-exit
RUN test -f /build/PocketMine-MP.phar

FROM ubuntu:latest
LABEL maintainer="PMMP Team <team@pmmp.io>"

# Обновляем пакеты
RUN apt-get update && apt-get install --no-install-recommends -y ca-certificates wget

# Создаем пользователя pocketmine
RUN groupadd -g 1000 pocketmine
RUN useradd -r -d /pocketmine -p "" -u 1000 -m -g pocketmine pocketmine

WORKDIR /pocketmine
COPY --from=0 /build/build/php/bin/php7 /usr/php

# Фиксим opcache
RUN grep -q '^extension_dir' /usr/php/bin/php.ini && \
	sed -ibak "s{^extension_dir=.*{extension_dir=\"$(find /usr/php -name *debug-zts*)\"{" /usr/php/bin/php.ini || echo "extension_dir=\"$(find /usr/php -name *debug-zts*)\"" >> /usr/php/bin/php.ini
RUN ln -s /usr/php/bin/php /usr/bin/php
COPY --from=0 /build/PocketMine-MP.phar PocketMine-MP.phar

# Создаем папки для плагинов и данных
RUN mkdir /plugins /data
RUN chown 1000:1000 /plugins /data . -R
RUN chmod o+x /usr/bin/php

USER pocketmine

ENV TERM=xterm

# Открываем порты
EXPOSE 19132/tcp
EXPOSE 19132/udp

# Создаем вольюмы
VOLUME ["/data", "/plugins"]

# Запускаем pocketmine
CMD ["cd", "/pocketmine"]
CMD ["php", "PocketMine-MP.phar", "--no-wizard", "--disable-ansi", "--disable-readline", "--enable-ansi", "--enable-readline", "--data=/data", "--plugins=/plugins"]