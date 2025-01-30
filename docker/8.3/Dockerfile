FROM ubuntu:22.04 AS base_installer
LABEL maintainer="Taylor Otwell"

ARG WWWGROUP
ARG NODE_VERSION=20
ARG POSTGRES_VERSION=17
ARG CONTAINER_APP_PORT
ARG CONTAINER_GRPC_PORT
ARG CONTAINER_RPC_PORT
ARG CONTAINER_VITE_PORT
ARG PC_REL="https://github.com/protocolbuffers/protobuf/releases"
ARG PC_VER="v25.1"
ARG PC_FILE="protoc-25.1-linux-x86_64.zip"

WORKDIR /var/www/html

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=UTC
ENV SUPERVISOR_PHP_USER="sail"

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN echo "Acquire::http::Pipeline-Depth 0;" > /etc/apt/apt.conf.d/99custom && \
    echo "Acquire::http::No-Cache true;" >> /etc/apt/apt.conf.d/99custom && \
    echo "Acquire::BrokenProxy    true;" >> /etc/apt/apt.conf.d/99custom

FROM base_installer AS base_installer_php
RUN apt-get update && apt-get upgrade -y \
    && mkdir -p /etc/apt/keyrings \
    && apt-get install -y gnupg gosu curl ca-certificates zip unzip git supervisor libcap2-bin dnsutils fswatch vim  \
    && curl -sS 'https://keyserver.ubuntu.com/pks/lookup?op=get&search=0x14aa40ec0831756756d7f66c4f4ea0aae5267a6c' | gpg --dearmor | tee /etc/apt/keyrings/ppa_ondrej_php.gpg > /dev/null \
    && echo "deb [signed-by=/etc/apt/keyrings/ppa_ondrej_php.gpg] https://ppa.launchpadcontent.net/ondrej/php/ubuntu jammy main" > /etc/apt/sources.list.d/ppa_ondrej_php.list \
    && apt-key adv --keyserver keyserver.ubuntu.com --recv-keys E5267A6C \
    && apt-get update \
    && apt-get install -y \
       php8.3-cli php8.3-dev \
       php8.3-pgsql php8.3-sqlite3 php8.3-gd \
       php8.3-curl php8.3-zip php8.3-mbstring \
       php8.3-intl php8.3-readline \
       php8.3-igbinary php8.3-redis \
       php8.3-pcov php8.3-xml php8.3-pgsql \
       php8.3-grpc php-pear libtool \
       php8.3-http php8.3-raphf php8.3-protobuf \
       && curl -sS -LO "$PC_REL/download/$PC_VER/$PC_FILE" \
       && unzip $PC_FILE -d $HOME/.local \
       && rm $PC_FILE \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

FROM base_installer AS protoc_plugins_compile
RUN apt-get update \
    && apt-get install -y \
       zip unzip git wget make gcc g++ cmake \
    && cd /tmp \
    && git config --global http.lowSpeedLimit 0 \
    && git config --global http.lowSpeedTime 999999 \
    && git config --global http.postBuffer 52428800 \
    && git clone https://github.com/grpc/grpc \
    && cd grpc \
    && git submodule update --init \
    && mkdir -p cmake/build \
    && cd cmake/build \
    && cmake ../.. \
    && make protoc grpc_php_plugin \
    && cp ./grpc_php_plugin /usr/local/bin/grpc_php_plugin \
  && apt-get -y autoremove \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

FROM base_installer_php AS base_installer_postgres
RUN curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer \
        && curl -sS https://www.postgresql.org/media/keys/ACCC4CF8.asc | gpg --dearmor | tee /etc/apt/keyrings/pgdg.gpg >/dev/null \
        && echo "deb [signed-by=/etc/apt/keyrings/pgdg.gpg] http://apt.postgresql.org/pub/repos/apt jammy-pgdg main" > /etc/apt/sources.list.d/pgdg.list \
        && apt-get update \
        && apt-get install -y postgresql-client-$POSTGRES_VERSION \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

FROM base_installer_postgres AS base_installer_npm
RUN curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg \
        && echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_$NODE_VERSION.x nodistro main" > /etc/apt/sources.list.d/nodesource.list \
        && apt-get update \
        && apt-get install -y nodejs \
        && npm install -g npm \
        && npm install -g pnpm \
        && npm install -g bun \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

FROM base_installer_npm AS final_build

RUN setcap "cap_net_bind_service=+ep" /usr/bin/php8.3

RUN groupadd --force -g $WWWGROUP sail
RUN useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u 1337 sail

COPY start-container /usr/local/bin/start-container
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY php.ini /etc/php/8.3/cli/conf.d/99-sail.ini
COPY --from=protoc_plugins_compile /usr/local/bin/grpc_php_plugin /usr/local/bin/grpc_php_plugin

RUN chmod +x /usr/local/bin/start-container
RUN chmod +x /usr/local/bin/grpc_php_plugin
RUN git config --global --add safe.directory /var/www/html

EXPOSE $CONTAINER_APP_PORT $CONTAINER_GRPC_PORT $CONTAINER_RPC_PORT $CONTAINER_VITE_PORT

ENTRYPOINT ["./entrypoint.sh"]
