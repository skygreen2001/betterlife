# Betterlife.Core

Betterlife Core with minimize project size from betterlife cms framework.

## 安装说明

### 推荐方式

* **通过Github官网下载**

    官网地址: https://github.com/skygreen2001/betterlife.core

    ```bash
    > git clone https://github.com/skygreen2001/betterlife.core.git
    > git clone git@github.com:skygreen2001/betterlife.core.git
    ```
* **安装NodeJs**

  下载地址: https://nodejs.org/en/download/

* **安装目录下运行**

  ```
  > npm install
  ```
  [说明]
  > 安装目录是根目录下的install目录, 即本说明文件目录下

* **安装目录下运行**
  - [安装composer](http://docs.phpcomposer.com/00-intro.html)

  - 安装运行gulp
  ```
  > npm install -g gulp
  > gulp
  ```

## 框架目录定义

    - core   : 框架核心支持文件
    - taglib : 自定义标签，您也可以在自己的应用中定义自定义标签
    - install: 安装目录
    - misc   : 引用第三方Js、Css、Image、Fonts资源目录
    - tools  : 开发项目中通常用到的工具，包括项目重用工具、代码生成工具、消除整站文件BOM头工具、压力测试工具等
    - home   : 应用目录，Web应用层所有代码都放在这里，每一个Web应用是一个子目录，每个应用目录名称需在Gc.php里的变量:$module_name里注册
    - api    : ajax请求服务端服务支持[手机或Web前端ajax请求返回json数据]
    - app    : 自适应html5Web网页[内嵌在手机App里]
    - log    : 日志目录，每天一个调试测试日志文件放在这里
    - upload : 后台上传文件(如图片、pdf)放置目录    
    - dos    : 框架帮助说明文档
      - core/config      : 配置文件[各个功能模块]
      - install/db       : 框架数据库测试数据
      - api/mobile       : 手机端ajax请求服务端返回json数据
      - api/web          : Pc端ajax请求服务端返回json数据
      - upload/images    : 上传图片放置路径
      - upload/attachment: 批量导入/导出数据文件(如excel)放置目录

## 参考资料

* **安装NodeJs**
  > https://nodejs.org/en/download/
* **安装Gulp**
  > 中文说明: http://www.gulpjs.com.cn/docs/getting-started/
  > 英文说明: http://www.gulpjs.com
* **安装Composer**
  > http://www.phpcomposer.com/
* **下载Smarty**
  > https://github.com/smarty-php/smarty
* **下载PHPExcel**
  > https://github.com/PHPOffice/PHPExcel
* **安装UEditor**
  > http://ueditor.baidu.com/website/download.html
