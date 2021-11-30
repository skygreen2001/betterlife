# Docker 安装 betterlife

## 运行须知

  - Docker容器主要配置是Lnmp: Linux(Ubuntu) + nginx + mysql + php
    - php安装有组件: gd, redis,  mysqli zip
    - php安装组件可在install/docker/Dockerfile 文件里进行添加会修改删除。
  - 可在本地编辑php源文件，所见即所得，会立即在容器内生效运行生成结果。
  - 配置文件在安装路径下: install/docker/docker-compose.yml
  - 在容器内运行还需修改Gc.php文件相应配置
    - 数据库配置: $database_config
      - $database_config -> host = "mysql"
        - 数据库的主机需配置为mysql，这是因为容器mysql服务器在docker-compose.yml配置中的服务名称定义就是mysql，这样php才能连上数据库。
        - 数据库其它配置也参考docker-compose.yml中mysql定义配置进行修改
    - 网站路径配置: $url_base
      - 网站路径默认是不配置的，通过算法得到，但是在docker容器内，需要手动配置
      - 生产服务器上需配置域名
      - 本地配置一般是: $url_base="http://localhost/"; 或者 $url_base="http://127.0.0.1/";

## 设置权限

  - 由于是通过volume映射到容器内，容器内文件夹的权限由宿主机文件夹的权限决定，因此需要在宿主机本地执行以下指令赋予读写权限。
  - 也可在容器运行起来后，进入容器内应用的文件位置执行以下指令赋予读写权限。
  - 根路径下运行

    ```
      sudo mkdir -p upload
      sudo chmod -R 777 upload
      sudo mkdir -p log
      sudo chmod -R 777 log
      sudo mkdir -p home/betterlife/view/bootstrap/tmp/templates_c
      sudo chmod -R 777 home/betterlife/view/bootstrap/tmp/templates_c
      sudo mkdir -p home/admin/view/default/tmp/templates_c
      sudo chmod -R 777 home/admin/view/default/tmp/templates_c
      sudo mkdir -p home/model/view/default/tmp/templates_c
      sudo chmod -R 777 home/model/view/default/tmp/templates_c
      sudo mkdir -p home/report/view/default/tmp/templates_c
      sudo chmod -R 777 home/report/view/default/tmp/templates_c
    ```

## 在根路径下运行更便捷

  - 为了避免不使用Docker Compose的用户在根路径下看到docker的配置文件，将配置文件放在了install/docker目录下。
  - 对于经常使用Docker的用户，可以将配置文件放置到根路径下，这样就不需要每次启动关闭都需要带上配置文件的参数了。
  - 需要注意的是，需修改配置文件中的相关路径(配置文件中有注释说明，按要求调整即可)。
  - 修改后就可以简化启动和关闭指令了。

  - 根路径下运行以下指令执行操作
  - 创建运行: docker-compose up -d
  - 运行应用: docker-compose start
  - 停止应用: docker-compose stop
  - 进入应用: docker exec -it bb /bin/bash

  - 删除所有的容器: docker-compose down
  - 删除生成的镜像: docker rmi bb bb_nginx mysql:5.7

  - 复制容器文件到本地: 
    - 复制安装的composer包文件到本地: docker cp bb:/var/www/html/betterlife/install/vendor/ $(pwd)/install/
    - 复制安装好的UEditor包文件到本地: docker cp bb:/var/www/html/betterlife/misc/js/onlineditor/ueditor/ $(pwd)/misc/js/onlineditor/

## 其它

- 从Docker Hub拉取betterlife镜像并运行
  - Docker Hub上查询镜像: https://hub.docker.com  -> 搜索:  skygreen2021/betterlife
  - 执行以下命令即可
  - [以下说明](LEARN.md#Apache)
  
  ```
    mkdir betterlife && cd betterlife
    mkdir -p install/db/mysql && cd install/db/mysql
    curl -O https://raw.githubusercontent.com/skygreen2001/betterlife/master/install/db/mysql/db_betterlife.sql
    cd ../../../
    docker network create betterlife
    docker run -itd --name mysql -p 3306:3306 -v `pwd`/install/db/mysql:/docker-entrypoint-initdb.d/ -v mysql-data:/var/lib/mysql -e MYSQL_ALLOW_EMPTY_PASSWORD=root -e MYSQL_ROOT_PASSWORD= -e MYSQL_DATABASE=betterlife --network=betterlife mysql
    docker run -dit --name=betterlife -p 80:80 --network=betterlife skygreen2021/betterlife
  ```

  - 停止应用     : docker stop betterlife mysql
  - 删除所有的容器: docker rm betterlife mysql
  - 删除生成的镜像: docker rmi skygreen2021/betterlife mysql
  

- [学习使用Docker实战betterlife](LEARN.md)
  - 这一部分主要用于学习使用Docker实战betterlife，有空的时间可以看看，这一部分主要包括:
    - Docker 多阶段构建betterlife
    - 上传到Docker Hub分享给大家使用betterlife框架

