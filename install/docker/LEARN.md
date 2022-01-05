# 学习使用Docker实战betterlife

## Docker 多阶段构建betterlife

本方式可供学习Docker多阶段构建使用。

### 参考
  
  - [实战多阶段构建 Laravel 镜像](https://yeasy.gitbook.io/docker_practice/image/multistage-builds/laravel)
  - [laravel-demo/Dockerfile](https://github.com/khs1994-docker/laravel-demo/blob/master/Dockerfile)

### 具体操作

  以下操作均在根路径下命令行中执行:
  - 创建镜像和互通的网络
    - docker build -t bb/betterlife --target=betterlife -f install/docker/Dockerfile .
    - docker build -t bb/nginx --target=nginx -f install/docker/Dockerfile .
    - docker network create betterlife
    - docker volume create betterlife_ueditor
    - docker volume create betterlife_composer
  - 运行容器应用 
    - docker run -dit --name=betterlife -p 9000:9000 -v `pwd`:/var/www/html/betterlife -v betterlife_composer:/var/www/html/betterlife/install/vendor -v betterlife_ueditor:/var/www/html/betterlife/misc/js/onlineditor/ueditor --network=betterlife bb/betterlife
    - docker run -dit --name=betterlife_nginx -v `pwd`:/var/www/html/betterlife  -v betterlife_composer:/var/www/html/betterlife/install/vendor -v betterlife_ueditor:/var/www/html/betterlife/misc/js/onlineditor/ueditor -p 80:80 --network=betterlife bb/nginx

    - [说明]: 
      - 本地物理机Web路径: `pwd`
      - 容器里Web放置路径: /var/www/html/betterlife
      - 如果使用如阿里云其他外网的Mysql数据库，提供域名或者外网地址；只需修改Gc.php里相应的数据库配置即可
      
  - 停止应用     : docker stop betterlife betterlife_nginx
  - 删除所有的容器: docker rm betterlife betterlife_nginx
  - 删除生成的镜像: docker rmi bb/nginx bb/betterlife
  
## 上传到Docker Hub

### Lemp

#### 运行镜像

  - 从Docker Hub下载运行betterlife框架。
    - Docker Hub上查询镜像: https://hub.docker.com  -> 搜索:  skygreen2021/betterlife
    - 运行betterlife框架: docker run -dp 80:80 --name betterlife -t skygreen2021/betterlife
    - 容器运行成功后安装运行示例数据库: docker exec -it betterlife bash -c 'mysql betterlife < /var/www/install/db/mysql/db_betterlife.sql'

    - 运行脚本汇总如下

    ```
      docker run -dp 80:80 --name betterlife -t skygreen2021/betterlife
      docker exec -it betterlife bash -c 'mysql betterlife < /var/www/install/db/mysql/db_betterlife.sql'
    ```

  - 开放数据库接口，本地可操作: docker run -dp 80:80 -p 3306:3306 --name betterlife -t skygreen2021/betterlife
  - 其它docker run配置参数参考: [novice/lemp](https://hub.docker.com/r/novice/lemp)
  - 体验后删除betterlife框架
    - 删除betterlife框架容器及镜像: docker stop betterlife && docker rm betterlife && docker rmi skygreen2021/betterlife

#### 创建镜像

  - 制作[ skygreen2001/betterlife ]镜像提交到Docker Hub
    - 只需要制作一个镜像，betterlife框架和lemp服务器使用一个镜像。
    - 本功能用于制作演示betterlife框架的 Docker镜像，提交到Docker Hub，公开对外的Dockerfile文件及环境搭建。
    - 创建betterlife镜像提交到Docker Hub

      ```
        docker build -t betterlife --target=betterlife -f install/docker/hub/lemp/Dockerfile .
        docker tag betterlife skygreen2021/betterlife
        docker login -u [YOUR-USER-NAME]
        docker push skygreen2021/betterlife
      ```

      - hub上查询提交的镜像: https://hub.docker.com  -> 搜索:  skygreen2021/betterlife
      - 如果本地已经存在，需要更新镜像: docker pull skygreen2021/betterlife
      - 运行推送的hub: docker run -dp 80:80 --name betterlife skygreen2021/betterlife

### Apache

#### 运行镜像

  - 从Docker Hub下载运行betterlife框架。
    - Docker Hub上查询镜像: https://hub.docker.com  -> 搜索:  skygreen2021/bb_apache
    - 在工作目录下创建betterlife目录并进入: mkdir betterlife && cd betterlife
    - 创建网络: docker network create betterlife
    - 运行betterlife框架: docker run -dit --name=bb_apache -p 80:80 --network=betterlife skygreen2021/bb_apache
    - 本地创建所需示例数据库脚本所在路径: mkdir -p install/db
    - 从容器里复制示例数据库脚本文件到本地: docker cp bb_apache:/var/www/html/install/db/mysql $(pwd)/install/db
    - 运行示例数据库: docker run -itd --name mysql -p 3306:3306 -v `pwd`/install/db/mysql:/docker-entrypoint-initdb.d/ -v mysql-data:/var/lib/mysql -e MYSQL_ALLOW_EMPTY_PASSWORD=root -e MYSQL_ROOT_PASSWORD= -e MYSQL_DATABASE=betterlife --network=betterlife mysql

    - 运行脚本汇总如下

    ```
      mkdir betterlife && cd betterlife
      docker network create betterlife
      docker run -dit --name=bb_apache -p 80:80 --network=betterlife skygreen2021/bb_apache
      mkdir -p install/db
      docker cp bb_apache:/var/www/html/install/db/mysql $(pwd)/install/db
      docker run -itd --name mysql -p 3306:3306 -v `pwd`/install/db/mysql:/docker-entrypoint-initdb.d/ -v mysql-data:/var/lib/mysql -e MYSQL_ALLOW_EMPTY_PASSWORD=root -e MYSQL_ROOT_PASSWORD= -e MYSQL_DATABASE=betterlife --network=betterlife mysql
    ```

  - 体验后删除betterlife框架
    - 删除betterlife框架容器及镜像: docker stop bb_apache && docker rm bb_apache && docker rmi skygreen2021/bb_apache
    - 删除示例数据库容器及镜像     : docker stop mysql && docker rm mysql && docker rmi mysql

#### 创建镜像

  - 制作[ skygreen2001/bb_apache ]镜像提交到Docker Hub
    - 只需要制作一个镜像，betterlife框架和apache服务器使用一个镜像。
    - 本功能用于制作演示betterlife框架的 Docker镜像，提交到Docker Hub，公开对外的Dockerfile文件及环境搭建。
    - 在容器内运行还需修改Gc.php文件相应配置
      - 数据库配置: $database_config
        - $database_config -> host = "mysql"
          - 数据库的主机需配置为mysql，这是因为容器mysql服务器的容器名称定义就是mysql，这样php才能连上数据库。
    - 创建bb_apache镜像提交到Docker Hub

      ```
        docker build -t bb_apache --target=betterlife -f install/docker/hub/apache/Dockerfile .
        docker tag bb_apache skygreen2021/bb_apache
        docker login -u [YOUR-USER-NAME]
        docker push skygreen2021/bb_apache
      ```

      - hub上查询提交的镜像: https://hub.docker.com  -> 搜索:  skygreen2021/bb_apache
      - 如果本地已经存在，需要更新镜像: docker pull skygreen2021/bb_apache
      - 运行推送的hub: docker run -dp 80:80 skygreen2021/bb_apache

### Nginx

#### 运行镜像

  - 从Docker Hub拉取bb, bb_nginx镜像并运行

    ```
      docker run -ti --rm -v ${HOME}:/root -v $(pwd):/git alpine/git clone https://github.com/skygreen2001/betterlife

      cd betterlife/

      docker network create betterlife
      
      docker run -itd --name mysql -p 3306:3306 -v `pwd`/install/db/mysql:/docker-entrypoint-initdb.d/ -v mysql-data:/var/lib/mysql -e MYSQL_ALLOW_EMPTY_PASSWORD=root -e MYSQL_ROOT_PASSWORD= -e MYSQL_DATABASE=betterlife --network=betterlife mysql

      docker run -dit --name=bb -p 9000:9000 -v `pwd`:/var/www/html/betterlife -v betterlife_composer:/var/www/html/betterlife/install/vendor -v betterlife_ueditor:/var/www/html/betterlife/misc/js/onlineditor/ueditor --network=betterlife skygreen2021/bb

      docker run -dit --name=bb_nginx -p 80:80 -v `pwd`:/var/www/html/betterlife  -v betterlife_composer:/var/www/html/betterlife/install/vendor -v betterlife_ueditor:/var/www/html/betterlife/misc/js/onlineditor/ueditor --network=betterlife skygreen2021/bb_nginx
    ```

  - 可在本地编辑php源文件，所见即所得，会立即在容器内生效运行生成结果。
  - 在容器内运行还需修改Gc.php文件相应配置
    - 数据库配置: $database_config
      - $database_config -> host = "mysql"
        - 数据库的主机需配置为mysql，这是因为容器mysql服务器的容器名称定义就是mysql，这样php才能连上数据库。
    - 网站路径配置: $url_base
      - 网站路径默认是不配置的，通过算法得到，但是在docker容器内，需要手动配置
      - 生产服务器上需配置域名
      - 本地配置一般是: $url_base="http://localhost/"; 或者 $url_base="http://127.0.0.1/";

  - 体验后删除betterlife框架
    - 停止应用     : docker stop bb bb_nginx mysql
    - 删除所有的容器: docker rm bb bb_nginx mysql
    - 删除生成的镜像: docker rmi skygreen2021/bb_nginx skygreen2021/bb mysql

#### 创建镜像

  - 制作[ skygreen2001/bb, skygreen2001/bb_nginx ]镜像提交到Docker Hub
    - 需要制作两个镜像，一个是betterlife框架本身，另一个是nginx服务器，分开运行的两个镜像。
    - 本功能用于制作演示betterlife框架的 Docker镜像，提交到Docker Hub，公开对外的Dockerfile文件及环境搭建。
    - 创建bb, bb_nginx镜像提交到Docker Hub

      ```
        docker build -t bb --target=betterlife -f install/docker/Dockerfile .
        docker build -t bb_nginx --target=nginx -f install/docker/Dockerfile .
        docker tag bb skygreen2021/bb
        docker tag bb_nginx skygreen2021/bb_nginx
        docker login -u [YOUR-USER-NAME]
        docker push skygreen2021/bb
        docker push skygreen2021/bb_nginx
      ```

      - hub上查询提交的镜像: https://hub.docker.com  -> 搜索:  skygreen2021/bb
      - 如果本地已经存在，需要更新镜像: docker pull skygreen2021/bb && docker pull skygreen2021/bb_nginx
  
## 使用Laradock

  - [Laradock](https://laradock.io/)
    - 修改/etc/hosts
      - 添加一行: 199.232.68.133 raw.githubusercontent.com
    - 下载betterlife: git clone https://github.com.cnpmjs.org/skygreen2001/betterlife 
    - 下载Laradock  : git clone https://github.com.cnpmjs.org/Laradock/laradock.git
    - 两个项目平级，如下
      - betterlife
      - laradock
    - 重命名[ .env ]: cp .env.example .env
    - 清空缓存(如果有的话): rm -rf ~/.laradock/data/mysql
    - 在laradock根路径下做以下调整 
      - 修改 .env: 
        - CHANGE_SOURCE=true
          - 说明: 使用国内镜像
        - MYSQL_DATABASE=betterlife
        - MYSQL_ALLOW_EMPTY_PASSWORD=root
        - MYSQL_ROOT_PASSWORD=
        - MYSQL_ENTRYPOINT_INITDB=../betterlife/install/db/mysql
        - WORKSPACE_INSTALL_XDEBUG=true
        - PHP_FPM_INSTALL_XDEBUG=true
      - 修改docker-compose.yml
        - 在mysql: environment: 下添加一行
          - MYSQL_ALLOW_EMPTY_PASSWORD=${MYSQL_ALLOW_EMPTY_PASSWORD}
      - 修改nginx/sites/default.conf
        - 修改前: root /var/www/public;
        - 修改后: root /var/www/betterlife;  
      - 运行容器: docker-compose up -d nginx mysql phpmyadmin redis workspace
    - 在betterlife根路径下需做以下操作
    - 在容器内运行还需修改Gc.php文件相应配置
      - 数据库配置: $database_config
        - $database_config -> host = "mysql"
    - 安装UEditor
      - [参考【安装PHP第三方库和UEditor】的安装UEditor部分](../README.md)
    - 打开浏览器运行: http://localhost