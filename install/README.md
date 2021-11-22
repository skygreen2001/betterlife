# 安装说明

## 安装目录

  - 安装目录: 以下安装都在根路径/install 目录下

## 推荐方式

- 主要目标是安装 UEditor。
- UEditor源码需用到Github库
  - [Github国内镜像网站](https://zhuanlan.zhihu.com/p/360677731)
  - Github源库: https://github.com/fex-team/ueditor.git
  - 镜像一    : https://github.com.cnpmjs.org/fex-team/ueditor.git
  - 镜像二    : https://hub.fastgit.org/fex-team/ueditor.git

- 命令行下运行:

    ```
    > git clone https://github.com.cnpmjs.org/fex-team/ueditor.git -b dev-1.4.3.3
    > npm config set registry https://registry.npm.taobao.org 
    > cd ueditor && npm install  && npm install -g grunt && npm install -g grunt-cli && grunt default \
    > cd ../../misc/js/onlineditor && mkdir ueditor \
    > cp -rf ../../../install/ueditor/dist/utf8-php/* ueditor/ \
    > cp -rf ueditor_bak/* ueditor/
    > cd ../../../install/ && rm -rf ueditor
    ```

## 第二种方式

- 以下步骤主要目标是安装 UEditor。
- 该方式主要是通过打包工具安装UEditor。
- 使用Node, gulp, npm, bower 等工具。

* **安装NodeJs**

  下载地址: https://nodejs.org/en/download/

* **安装Gulp**

  ```
  > npm install -g gulp
  ```

* **安装目录下运行**

  安装目录是根目录下的install目录, 即本说明文件目录下

  - 运行npm

    ```
    > npm install
    ```
    [Mac电脑用户]: sudo npm install

  - 运行gulp

    ```
    > sudo gulp
    ```
    - 说明: 因为ueditor打包需要安装grunt的原因，该命令行需执行两次。

  - 安装ueditor

    ```
    > sudo gulp ueditor_cp && sudo gulp ueditor
    ```

## 手动方式

* **安装Smarty | PHPExcel | UEditor**

  - 下载Github库
    - Smarty        : https://github.com/smarty-php/smarty
    - PhpSpreadsheet: https://github.com/PHPOffice/PhpSpreadsheet

  - 放置 Smarty 库, PhpExcel库
    - 放置 Smarty 库 -> install/vendor/smarty/smarty/ 目录下
    - 放置 PhpSpreadsheet 库 -> install/vendor/phpoffice/phpspreadsheet/ 目录下

  - 下载 在线编辑器: [UEditor](http://ueditor.baidu.com/website/download.html)
    - 下载 在线编辑器: [UEditor] [1.4.3.3 PHP 版本] UTF-8版
    - 解压 下载文件 到目录 utf8-php
    - 先备份misc/js/onlineditor/ueditor 目录下的文件 到 misc/js/onlineditor/ueditor_bak 下
    - 复制粘贴 utf8-php/ -> misc/js/onlineditor/ueditor 目录下
    - 复制粘贴 misc/js/onlineditor/ueditor_bak -> misc/js/onlineditor/ueditor 即可.

    [说明]
    > UEditor下载版本为:[1.4.3.3 PHP 版本] UTF-8版

## FAQ

### PHP 7 需知

  - Since PHP 7 is not in the official Ubuntu PPAs,使用Composer install 会提示错误: Call to undefined function: simplexml_load_string(),解决办法在服务器上执行以下指令

    ```
    > sudo apt-get install php7.0-xml
    > sudo service php7.0-fpm restart
    ```

  - Composer require phpoffice/phpspreadsheet doesn't work

    ```
    > sudo apt install php-xml
    > sudo apt install php7.0-gd
    > sudo apt install php7.0-mbstring
    > sudo apt install php-zip
    ```

### Mac电脑运行gulp提示: ReferenceError: primordials is not defined

  - 安装nvm[Mac OS 下 NVM 的安装与使用]: https://www.jianshu.com/p/622ad36ee020
    - curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.35.1/install.sh | bash
    - export NVM_DIR="$([ -z "${XDG_CONFIG_HOME-}" ] && printf %s "${HOME}/.nvm" || printf %s "${XDG_CONFIG_HOME}/nvm")"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh" # This loads nvm

  - nvm install 10.15.3

## 参考资料

* **下载Smarty**
  > https://github.com/smarty-php/smarty

* **下载PhpSpreadsheet**
  > https://github.com/PHPOffice/PhpSpreadsheet

* **安装UEditor**
  > http://ueditor.baidu.com/website/download.html

* **安装NodeJs**
  > [Installing Node.js via package manager](https://nodejs.org/en/download/package-manager/)

* **安装Gulp**
  > 中文说明: http://www.gulpjs.com.cn/docs/getting-started/
  > 英文说明: http://www.gulpjs.com
