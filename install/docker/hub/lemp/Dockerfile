FROM composer as composer
COPY install/composer.json /app/
# COPY install/composer.json install/composer.lock /app/
RUN set -x ; cd /app && \
    composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/ && \
    composer install \
                --ignore-platform-reqs \
                --no-interaction \
                --no-plugins \
                --no-scripts \
                --prefer-dist


FROM node:10.15.3-alpine as ueditor

# docker Alpine Linux 国内镜像
# https://furthergazer.top/article/2020/3/9/7.html

RUN sed -i 's/dl-cdn.alpinelinux.org/mirrors.aliyun.com/g' /etc/apk/repositories && \
    apk update && apk add git

WORKDIR /app
# COPY install/bower.json install/gulpfile.js install/package.json ./install/
COPY misc/js/onlineditor/ueditor_bak/ ./misc/js/onlineditor/ueditor_bak/

# RUN git clone https://github.com/fex-team/ueditor.git -b dev-1.4.3.3 \
# 解决Github访问问题，使用Github国内镜像
# [Github国内镜像网站](https://zhuanlan.zhihu.com/p/360677731)
RUN git clone https://github.com.cnpmjs.org/fex-team/ueditor.git -b dev-1.4.3.3 && \
# RUN git clone https://hub.fastgit.org/fex-team/ueditor.git -b dev-1.4.3.3 && \
# 设置npm国内镜像源库
    npm config set registry https://registry.npm.taobao.org && \
    # npm config set registry http://registry.cnpmjs.org && \
    cd ueditor && npm install && npm install -g grunt && npm install -g grunt-cli && grunt default && \
    cd ../misc/js/onlineditor && mkdir ueditor && \
    cp -rf ../../../ueditor/dist/utf8-php/* ueditor/ && \
    cp -rf ueditor_bak/* ueditor/

FROM novice/lemp as betterlife

LABEL maintainer="skygreen2001" \
      email="skygreen2001@gmail.com"\
      version="1.0.0" \
      description="a framework designed to easy read and rapid develop for chinese delevoper"

ARG BB_PATH=/var/www/

COPY . ${BB_PATH}
COPY --from=composer /app/vendor/ ${BB_PATH}/install/vendor/
COPY --from=ueditor /app/misc/js/onlineditor/ueditor ${BB_PATH}/misc/js/onlineditor/ueditor

RUN cd ${BB_PATH} && \
    mkdir -p upload && \
    chmod -R 777 upload && \
    mkdir -p log && \
    chmod -R 777 log && \
    mkdir -p home/betterlife/view/bootstrap/tmp/templates_c && \
    chmod -R 777 home/betterlife/view/bootstrap/tmp/templates_c && \
    mkdir -p home/admin/view/default/tmp/templates_c && \
    chmod -R 777 home/admin/view/default/tmp/templates_c && \
    mkdir -p home/model/view/default/tmp/templates_c && \
    chmod -R 777 home/model/view/default/tmp/templates_c && \
    mkdir -p home/report/view/default/tmp/templates_c && \
    chmod -R 777 home/report/view/default/tmp/templates_c

#设置alpine系统时区
ENV TZ=Asia/Shanghai
RUN ln -snf /usr/share/zoneinfo/${TZ} /etc/localtime && echo ${TZ} > /etc/timezone

COPY install/docker/hub/lemp/init.sh /

WORKDIR ${BB_PATH}

EXPOSE 80 3306 33060
