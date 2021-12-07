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

## 复制容器文件到本地

  - 该操作为可选项，根据自己的需求决定: 
    - 复制安装的composer包文件到本地: docker cp bb:/var/www/html/betterlife/install/vendor/ $(pwd)/install/
    - 复制安装好的UEditor包文件到本地: docker cp bb:/var/www/html/betterlife/misc/js/onlineditor/ueditor/ $(pwd)/misc/js/onlineditor/

## 其它

- 从Docker Hub拉取betterlife镜像并运行
  - Docker Hub上查询镜像: https://hub.docker.com  -> 搜索: skygreen2021/betterlife
  - 执行以下命令即可
  - [以下说明](LEARN.md#Lemp)
  
  ```
    docker run -dp 80:80 --name betterlife -t skygreen2021/betterlife
    docker exec -it betterlife bash -c 'mysql betterlife < /var/www/install/db/mysql/db_betterlife.sql'
  ```

  - 停止应用     : docker stop betterlife
  - 删除所有的容器: docker rm betterlife
  - 删除生成的镜像: docker rmi skygreen2021/betterlife
  

- [学习使用Docker实战betterlife](LEARN.md)
  - 这一部分主要用于学习使用Docker实战betterlife，有空的时间可以看看，这一部分主要包括:
    - Docker 多阶段构建betterlife
    - 上传到Docker Hub分享给大家使用betterlife框架

