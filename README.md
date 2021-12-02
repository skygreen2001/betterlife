# 只为更好

符合中国开发者思维方式的快速开发的框架，设计初衷快捷、简单、实用。

它包括一套实用的通用模版、后台管理模版、手机模版，并根据项目的需要，按照数据库的设计一键生成定制化的代码框架。

它自定义了一套快速开发报表的工具、Redis管理工具、数据库运维工具以协助快速开发。

## 下载源码

下载地址: https://github.com/skygreen2001/betterlife

* **Git安装**

  - 下载Git
    - Git SCM  : https://git-scm.com/downloads
    - Bitbucket: https://www.atlassian.com/git/tutorials/install-git

  - 下载betterlife 

      ```
      > git clone https://github.com/skygreen2001/betterlife.git
      ```

* **Docker安装**

  - [下载 Docker](https://docs.docker.com/get-docker/)
  - 下载betterlife

    ```
    > docker run -ti --rm -v ${HOME}:/root -v $(pwd):/git alpine/git clone https://github.com/skygreen2001/betterlife.git
    ```

## 通常安装

### 安装运行环境

  以下工具任选一种即可

  - [ampps](http://www.ampps.com)

    可以直接在它上面下载安装(Wamp|Lamp|Mamp)

  - [Wamp](http://www.wampserver.com/en/)

    Windows下的Apache + Mysql + PHP
    [PhpStudy]: http://www.phpstudy.net/

  - [Lamp](https://lamp.sh/)

    LAMP指的Linux、Apache，MySQL和PHP的第一个字母
    [安装详细说明]: http://blog.csdn.net/skygreen_2001/article/details/19912159

  - [Mamp](http://www.mamp.info/en/)

    Mac环境下搭建 Apache/Nginx、MySQL、Perl/PHP/Python 平台。

  - [Xampp](https://www.apachefriends.org/zh_cn/index.html)

    XAMPP是完全免费且易于安装的Apache发行版，其中包含MariaDB、PHP和Perl。

  - [宝塔](https://www.bt.cn/)

    宝塔面板是一款服务器管理软件，支持windows和linux系统

  - 本地运行PHP server: php -S localhost:8000)

### 安装检查

  - [PHP安装检查](docs/deploy.md)

### 其它安装

  - [安装PHP第三方库和UEditor](install/README.md)
    - 安装PHP第三方库主要是用composer
    - 后台【admin】在线编辑器使用百度的UEditor组件，需按该文档说明进行安装。

  - [安装示例数据库]
    - 新建Mysql数据库:betterlife, 运行脚本: install/db/mysql/db_betterlife.sql

## Docker安装

如果开发者熟悉Docker或者希望尝试通过Docker搭建开发环境(无需考虑因为操作系统，无法完整搭建应用运行环境，如在Mac操作系统下，因为权限问题无法安装php的zip或者redis，Mac Monterey版本后不再默认安装PHP), 可使用Docker安装

* **安装Docker**

  - [Get Docker](https://docs.docker.com/get-docker/)

* **Docker帮助文档**

  - [帮助文档](install/docker/README.md)

* **Docker 运行应用**

  - 根路径下运行以下指令执行操作
  - 创建运行: docker-compose -f install/docker/docker-compose.yml up -d
  - 运行应用: docker-compose -f install/docker/docker-compose.yml start
  - 停止应用: docker-compose -f install/docker/docker-compose.yml stop
  - 进入应用: docker exec -it bb /bin/bash

  - 删除所有的容器: docker-compose -f install/docker/docker-compose.yml down
  - 删除生成的镜像: docker rmi bb_nginx bb mysql:5.7
  
* **安装后需知**

  - [需知说明](install/docker/SETUP.md)

* **云平台**

  - [阿里云](https://market.aliyun.com/developer)
  - [Heroku](https://devcenter.heroku.com/categories/php)
  - [vagrant](https://app.vagrantup.com/laravel/boxes/homestead-7)

## 帮助文档

* [帮助说明文档](https://skygreen2001.github.io/betterlife.gitbook/)
* [帮助文档源码](https://github.com/skygreen2001/betterlife.gitbook)
* [本地说明文档](docs/README.md)

## 框架目录定义

  - core   : 框架核心支持文件
  - taglib : 自定义标签，您也可以在自己的应用中定义自定义标签
  - install: 安装目录
  - misc   : 引用第三方Js、Css、Image、Fonts资源目录
  - tools  : 开发项目中通常用到的工具，包括项目重用工具、代码生成工具、消除整站文件BOM头工具、压力测试工具等
  - home   : 应用目录，Web应用层所有代码都放在这里，每一个Web应用是一个子目录。
             (每个应用目录名称需在Gc.php里的变量:$module_name里注册)
    - admin     : 后台管理
    - model     : 通用模版
    - report    : 报表系统
    - betterlife: 网站前台
  - api    : ajax请求服务端服务支持[手机或Web前端ajax请求返回json数据]
  - app    :
    - html5: 自适应html5Web网页[内嵌在手机App里]
    - redis: Redis系统数据监控工具
  - log    : 日志目录，每天一个调试测试日志文件放在这里
  - upload : 后台上传文件(如图片、pdf)放置目录    
  - docs   : 框架帮助说明文档

  - 重要的二级目录说明
    - core/config      : 配置文件[各个功能模块]
    - install/db       : 框架数据库备份包括测试数据
    - core/include     : 常用的函数库
    - api/mobile       : 手机端ajax请求服务端返回json数据
    - api/web          : Pc端ajax请求服务端返回json数据
    - upload/images    : 上传图片放置路径
    - upload/attachment: 批量导入/导出数据文件(如excel)放置目录

## 开发工具

* [Visual Studio Code](https://code.visualstudio.com/)
* [Atom](https://atom.io)
* [Atom IDE](https://ide.atom.io/)
* [Sublime](http://www.sublimetext.com)

## 参考资料

- [PHP The Right Way](https://laravel-china.github.io/php-the-right-way/)
- [学习Symfony](docs/learn/SYMFONY.md)
- [本框架早期帮助文档](http://skygreen2001.gitbooks.io/betterlife-cms-framework/content/index.html)
