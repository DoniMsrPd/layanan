FROM ubuntu:22.04

LABEL maintainer="Taylor Otwell"

ARG WWWGROUP
ARG NODE_VERSION=18
ARG POSTGRES_VERSION=15

WORKDIR /var/www/html

ENV DEBIAN_FRONTEND noninteractive
ENV TZ=UTC

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update \
    && apt-get install -y gnupg gosu curl ca-certificates zip unzip git supervisor sqlite3 libcap2-bin libpng-dev python2 dnsutils librsvg2-bin \

    && curl -sS 'https://keyserver.ubuntu.com/pks/lookup?op=get&search=0x14aa40ec0831756756d7f66c4f4ea0aae5267a6c' | gpg --dearmor | tee /etc/apt/keyrings/ppa_ondrej_php.gpg > /dev/null \
    && echo "deb [signed-by=/etc/apt/keyrings/ppa_ondrej_php.gpg] https://ppa.launchpadcontent.net/ondrej/php/ubuntu jammy main" > /etc/apt/sources.list.d/ppa_ondrej_php.list \
    && curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
    && curl https://packages.microsoft.com/config/ubuntu/22.04/prod.list > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update
RUN mkdir -p ~/.gnupg \
    && chmod 600 ~/.gnupg \
    && echo "disable-ipv6" >> ~/.gnupg/dirmngr.conf
RUN ACCEPT_EULA=Y apt-get install -y msodbcsql18 unixodbc-dev php8.2-common php8.2-dev php8.2-cli php8.2-zip php8.2-curl php8.2-xml php8.2-fpm net-tools php8.2-cli \
        php8.2-gd php8.2-curl php8.2-redis php8.2-mbstring php8.2-xml php8.2-zip php8.2-bcmath php8.2-opcache php8.2-xdebug php8.2-excimer php8.2-xhprof graphviz \
        php8.2-intl php8.2-msgpack php8.2-igbinary php8.2-mysql php8.2-ldap libaio1
RUN pear config-set php_ini /etc/php/8.2/cli/conf.d/99-sail.ini \
    && pecl install sqlsrv-5.11.0 \
    && pecl install pdo_sqlsrv-5.11.0 \
    && apt-get -y remove php-pear php8.2-dev unixodbc-dev \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/* /tmp/* /var/cache/apt/* /var/tmp/* /usr/include



RUN setcap "cap_net_bind_service=+ep" /usr/bin/php8.2

RUN groupadd --force -g $WWWGROUP sail
RUN useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u 1337 sail

COPY start-container /usr/local/bin/start-container
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY php.ini /etc/php/8.2/cli/conf.d/99-sail.ini
COPY sqlsrv.so /usr/lib/php/20220829/sqlsrv.so
COPY pdo_sqlsrv.so /usr/lib/php/20220829/pdo_sqlsrv.so
RUN chmod +x /usr/local/bin/start-container
#PHP Install Composer
RUN curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

EXPOSE 8000

ENTRYPOINT ["start-container"]
