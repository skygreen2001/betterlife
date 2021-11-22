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

## 在根路径下运行更便捷

  - 为了避免不使用Docker Compose的用户在根路径下看到docker的配置文件，将配置文件放在了install/docker目录下。
  - 对于经常使用Docker的用户，可以将配置文件放置到根路径下，这样就不需要每次启动关闭都需要带上配置文件的参数了。
  - 需要注意的是，需修改配置文件中的相关路径(配置文件中有注释说明，按要求调整即可)。
  - 修改后就可以简化启动和关闭指令了。
  - 根路径下运行: docker-compose up -d
  - 停止应用   : docker-compose stop

  - 删除所有的容器: docker-compose down
  - 删除生成的镜像: docker rmi bb_nginx bb_betterlife mysql:5.7

  - 复制容器文件到本地: 
    - 复制安装的composer包文件到本地: docker cp bb:/var/www/html/betterlife/install/vendor/ $(pwd)/install/

## Docker 多阶段构建betterlife

### 参考
  
  - [实战多阶段构建 Laravel 镜像](https://yeasy.gitbook.io/docker_practice/image/multistage-builds/laravel)
  - [laravel-demo/Dockerfile](https://github.com/khs1994-docker/laravel-demo/blob/master/Dockerfile)

### 具体操作

  以下操作均在根路径下命令行中执行:
  - 创建镜像和互通的网络
    - docker build -t bb/betterlife --target=betterlife -f install/docker/Dockerfile .
    - docker build -t bb/nginx --target=nginx -f install/docker/Dockerfile .
    - docker network create betterlife
  - 运行容器应用 
    - docker run -dit --name=betterlife -p 9000:9000 -v `pwd`:/var/www/html/betterlife --network=betterlife bb/betterlife
    - docker run -dit --name=betterlife_nginx -v `pwd`:/var/www/html/betterlife -p 80:80 --network=betterlife bb/nginx

    - [说明]: 
      - 本地物理机Web路径: `pwd`
      - 容器里Web放置路径: /var/www/html/betterlife
      - 因为一般会使用专门的Mysql数据库，提供域名或者外网地址；只需修改Gc.php里相应的数据库配置即可
      - 
  - 停止应用     : docker stop betterlife betterlife_nginx
  - 删除所有的容器: docker rm betterlife betterlife_nginx
  - 删除生成的镜像: docker rmi bb/nginx bb/betterlife
  