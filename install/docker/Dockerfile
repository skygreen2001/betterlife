ARG PHP_VERSION=7.4

FROM composer as composer
COPY composer.json /app/
RUN set -x ; cd /app && \
    composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/ && \
    composer install \
                --ignore-platform-reqs \
                --no-interaction \
                --no-plugins \
                --no-scripts \
                --prefer-dist


# FROM node:10.15.3-alpine as ueditor
FROM node:17-alpine as ueditor

# docker Alpine Linux 国内镜像
# https://furthergazer.top/article/2020/3/9/7.html

RUN sed -i 's/dl-cdn.alpinelinux.org/mirrors.aliyun.com/g' /etc/apk/repositories && \
    apk update && apk add git

WORKDIR /app
COPY misc/js/onlineditor/ueditor_bak/ ./misc/js/onlineditor/ueditor_bak/

# RUN git clone https://github.com/fex-team/ueditor.git -b dev-1.4.3.3 \
# 解决Github访问问题，使用Github国内镜像
# [Github国内镜像网站](https://zhuanlan.zhihu.com/p/360677731)
RUN git clone https://github.com.cnpmjs.org/fex-team/ueditor.git -b dev-1.4.3.3 && \
# RUN git clone https://hub.fastgit.org/fex-team/ueditor.git -b dev-1.4.3.3 && \
# 设置npm国内镜像源库
    npm config set registry https://registry.npm.taobao.org && \
    # npm config set registry http://registry.cnpmjs.org && \
    # cd ueditor && npm install && npm install -g grunt && npm install -g grunt-cli && grunt default && \
    cd ueditor && npm install -g npm@8.2.0 && npm install && npm install -g --force grunt && npm install -g --force grunt-cli && grunt default && \
    cd ../misc/js/onlineditor && mkdir ueditor && \
    cp -rf ../../../ueditor/dist/utf8-php/* ueditor/ && \
    cp -rf ueditor_bak/* ueditor/

FROM php:${PHP_VERSION}-fpm as betterlife
# FROM php:${PHP_VERSION}-fpm-alpine as betterlife

ARG BB_PATH=/var/www/html/betterlife/
# ARG APP_VENDOR=/var/www/html/install/vendor/

# 经确认，系统是Debian 11 “bullseye”系统
# 阿里云Debian 镜像: https://developer.aliyun.com/mirror/debian
# Debian 11 “bullseye” 配置指南: https://zhuanlan.zhihu.com/p/402267392
# 清华大学开源软件镜像站: https://mirrors.tuna.tsinghua.edu.cn/help/debian/
RUN cp /etc/apt/sources.list /etc/apt/sources.list.bak && \
    echo "deb http://mirrors.aliyun.com/debian/ bullseye main non-free contrib" > /etc/apt/sources.list && \
    echo "deb-src http://mirrors.aliyun.com/debian/ bullseye main non-free contrib" >> /etc/apt/sources.list && \
    echo "deb http://mirrors.aliyun.com/debian-security/ bullseye-security main" >> /etc/apt/sources.list && \
    echo "deb-src http://mirrors.aliyun.com/debian-security/ bullseye-security main" >> /etc/apt/sources.list && \
    echo "deb http://mirrors.aliyun.com/debian/ bullseye-updates main non-free contrib" >> /etc/apt/sources.list && \
    echo "deb-src http://mirrors.aliyun.com/debian/ bullseye-updates main non-free contrib" >> /etc/apt/sources.list && \
    echo "deb http://mirrors.aliyun.com/debian/ bullseye-backports main non-free contrib" >> /etc/apt/sources.list && \
    echo "deb-src http://mirrors.aliyun.com/debian/ bullseye-backports main non-free contrib" >> /etc/apt/sources.list && \
    echo "deb https://mirrors.tuna.tsinghua.edu.cn/debian/ bullseye main contrib non-free" >> /etc/apt/sources.list && \
    echo "deb https://mirrors.tuna.tsinghua.edu.cn/debian/ bullseye-updates main contrib non-free" >> /etc/apt/sources.list && \
    echo "deb https://mirrors.tuna.tsinghua.edu.cn/debian/ bullseye-backports main contrib non-free" >> /etc/apt/sources.list && \
    echo "deb https://mirrors.tuna.tsinghua.edu.cn/debian-security bullseye-security main contrib non-free" >> /etc/apt/sources.list

# Install dependencies for the operating system software
RUN apt-get update && apt-get install -y \
#     make gcc g++ \
#     curl \
#     autoconf \
    vim

RUN echo "deb http://deb.debian.org/debian bullseye main" >> /etc/apt/sources.list && \
    echo "deb http://security.debian.org/debian-security bullseye-security main" >> /etc/apt/sources.list && \
    echo "deb http://deb.debian.org/debian bullseye-updates main" >> /etc/apt/sources.list

RUN apt-get install -y \
    libzip-dev unzip \
    libfreetype6-dev libjpeg62-turbo-dev libpng-dev
#     以下是可选安装包
#     locales \
#     jpegoptim optipng pngquant gifsicle \
#     libonig-dev \
#     git 

# # Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions for php
RUN docker-php-ext-install mysqli zip && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd && \
    pecl install -o -f redis && rm -rf /tmp/pear && docker-php-ext-enable redis

# debug with xdebug: start
# RUN pecl install xdebug && docker-php-ext-enable xdebug
# COPY install/docker/dev/docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d/
# debug with xdebug: end

# COPY . ${BB_PATH}
COPY --from=composer /app/install/vendor/ ${BB_PATH}install/vendor/
# COPY --from=composer /app/vendor/ ${APP_VENDOR}/

COPY --from=ueditor /app/misc/js/onlineditor/ueditor ${BB_PATH}misc/js/onlineditor/ueditor

RUN set -x ; cd /usr/local/etc/php && cp php.ini-production php.ini
    # && \
    # cd ${BB_PATH} && \
    # mkdir -p upload && \
    # chmod -R 777 upload && \
    # mkdir -p log && \
    # chmod -R 777 log && \
    # mkdir -p home/betterlife/view/bootstrap/tmp/templates_c && \
    # chmod -R 777 home/betterlife/view/bootstrap/tmp/templates_c && \
    # mkdir -p home/admin/view/default/tmp/templates_c && \
    # chmod -R 777 home/admin/view/default/tmp/templates_c && \
    # mkdir -p home/model/view/default/tmp/templates_c && \
    # chmod -R 777 home/model/view/default/tmp/templates_c && \
    # mkdir -p home/report/view/default/tmp/templates_c && \
    # chmod -R 777 home/report/view/default/tmp/templates_c

#设置alpine系统时区
ENV TZ=Asia/Shanghai
RUN ln -snf /usr/share/zoneinfo/${TZ} /etc/localtime && echo ${TZ} > /etc/timezone

WORKDIR ${BB_PATH}
EXPOSE 9000

FROM nginx:1.21.6-alpine as nginx
ARG BB_PATH=/var/www/html/betterlife/
COPY install/docker/nginx/nginx.conf /etc/nginx/conf.d/
RUN rm -rf /etc/nginx/conf.d/default.conf
# COPY --from=betterlife ${BB_PATH} ${BB_PATH}
COPY install/docker/nginx/wait-for-php.sh /wait-for-php.sh

#设置alpine系统时区
ENV TZ=Asia/Shanghai
RUN ln -snf /usr/share/zoneinfo/${TZ} /etc/localtime && echo ${TZ} > /etc/timezone

WORKDIR ${BB_PATH}
EXPOSE 80

# 本脚本无法在Windows上正常运行，因此被关闭掉，Linux可打开执行该脚本
# 该脚本主要是docker容器会有日志提示连接上了php容器9000端口
# ENTRYPOINT ["sh", "/wait-for-php.sh"]