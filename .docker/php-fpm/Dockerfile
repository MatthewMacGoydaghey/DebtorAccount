FROM php:8.2-fpm-bookworm


RUN rm /etc/apt/preferences.d/no-debian-php

RUN apt-get update && \
    apt-get install -y software-properties-common && \
    rm -rf /var/lib/apt/lists/*


RUN apt-get update && apt-get install nano wget unzip openssl libzip-dev libssl-dev libboost-dev libxml2-dev php-dev libccid pcscd libmotif-common -y \
    && docker-php-ext-install zip pdo pdo_mysql \
    && curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

## Install GOST-engine for OpenSSL
## Instruction: https://github.com/gost-engine/engine/blob/openssl_1_1_1/INSTALL.md
#ARG ENGINES_DIR="/usr/lib/x86_64-linux-gnu/engines-1.1"
#ARG OPENSSL_LIB_DIR="/usr/lib/ssl"
## Commit May 20, 2022
## URL: https://github.com/gost-engine/engine/archive/739f957615eb33a33a6485ae7cf29c7c679fd59a.zip
#ARG GOST_ENGINE_VERSION=739f957615eb33a33a6485ae7cf29c7c679fd59a
#ARG GOST_ENGINE_SHA256="99e047a239b374b62edd5e543cd76ac15f85b58adadc18f59f962e65008d126d"
#RUN apt-get install cmake -y \
#  && cd /usr/local/src \
#  && wget "https://github.com/gost-engine/engine/archive/${GOST_ENGINE_VERSION}.zip" -O gost-engine.zip \
#  && echo "$GOST_ENGINE_SHA256" gost-engine.zip | sha256sum -c - \
#  && unzip gost-engine.zip -d ./ \
#  && cd "engine-${GOST_ENGINE_VERSION}" \
#  && mkdir build \
#  && cd build \
#  && cmake -DCMAKE_BUILD_TYPE=Release \
#    -DOPENSSL_ROOT_DIR=${OPENSSL_LIB_DIR} \
#    -DOPENSSL_LIBRARIES=${OPENSSL_LIB_DIR} -DOPENSSL_ENGINES_DIR=${ENGINES_DIR} .. \
#  && cmake --build . --config Release \
#  && make CFLAGS="-Wno-error=deprecated-declarations" \
#  && make install \
#  && rm -rf "/usr/local/src/*"
#
#COPY ./gost-engine.cnf /usr/local/src/gost-engine.cnf
#
## Edit openssl.conf to enable GOST engine
#ARG OPENSSL_CONFIG_PATH="${OPENSSL_LIB_DIR}/openssl.cnf"
#RUN sed -i 's/openssl_conf = default_conf/openssl_conf = openssl_def/g' $OPENSSL_CONFIG_PATH \
#  && cat /usr/local/src/gost-engine.cnf >> $OPENSSL_CONFIG_PATH

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

ADD .docker/php-fpm/csp /tmp/src
ADD .docker/php-fpm/patch /tmp/src
ADD .docker/php-fpm/cades /tmp/src/cades

RUN cd /tmp/src/linux-amd64_deb && \
    ./install.sh lsb-cprocsp-devel cprocsp-pki-cades cprocsp-pki-phpcades


#Пакеты для Linux и macOS теперь входят в состав дистрибутива КриптоПро CSP 5.0 R3, их больше не надо скачивать и ставить отдельно.
#RUN cd /tmp/src && \
#    alien -kci cprocsp-pki-phpcades-2.0.14892-1.noarch.rpm && \
#    alien -kci cprocsp-pki-cades-64-2.0.14892-1.amd64.rpm && \
#    alien -kci lsb-cprocsp-devel-5.0.11863-5.noarch.rpm

COPY .docker/php-fpm/cades /opt/cprocsp/src/phpcades/
RUN cd /bin && \
    ln -s /opt/cprocsp/bin/amd64/certmgr && \
    ln -s /opt/cprocsp/bin/amd64/cpverify && \
    ln -s /opt/cprocsp/bin/amd64/cryptcp && \
    ln -s /opt/cprocsp/bin/amd64/csptest && \
    ln -s /opt/cprocsp/bin/amd64/csptestf && \
    ln -s /opt/cprocsp/bin/amd64/der2xer && \
    ln -s /opt/cprocsp/bin/amd64/inittst && \
    ln -s /opt/cprocsp/bin/amd64/wipefile && \
    ln -s /opt/cprocsp/sbin/amd64/cpconfig && \
    sed -i "s#PHPDIR=/php#PHPDIR=/usr/include/php/20220829#g" /opt/cprocsp/src/phpcades/Makefile.unix && \
    cd /opt/cprocsp/src/phpcades && \
#    patch < /tmp/src/php8_support.patch && \
    eval `/opt/cprocsp/src/doxygen/CSP/../setenv.sh --64`; make -f Makefile.unix && \
    mv libphpcades.so /usr/local/lib/php/extensions/no-debug-non-zts-20220829 &&\
    echo "extension=libphpcades.so" > /usr/local/etc/php/php.ini &&\
    rm -rf /tmp/src


WORKDIR /app
