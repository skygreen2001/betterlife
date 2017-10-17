# 安装说明

## [推荐方式](../README.md)

## 手动方式

* **安装NodeJs**

  下载地址: https://nodejs.org/en/download/

* **安装Gulp**

  ```
  > npm install -g gulp
  ```

* **安装 composer**

  - Linux | Mac 操作系统下

  参考: [Download Composer](https://getcomposer.org/download/)

  ```bash
  > sudo php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
  > sudo php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt,应该到 https://getcomposer.org/installer 按新的运行这行命令行'; unlink('composer-setup.php'); } echo PHP_EOL;"
  > sudo php composer-setup.php && sudo php -r "unlink('composer-setup.php');"
  > sudo mv composer.phar /usr/local/bin/composer
  > composer config -g repo.packagist composer https://packagist.phpcomposer.com
  ```

  - Windows操作系统下

    - 推荐安装Cmder[http://cmder.net/]
    - 设置系统环境变量Path：添加php.exe所在的路径
    - 安装composer:php -r "readfile('https://getcomposer.org/installer');" | php
    - 运行: php composer.phar install

* **安装目录下运行**

  安装目录是根目录下的install目录, 即本说明文件目录下

  - 运行npm

    ```
    > sudo npm install
    ```
    [说明]

  - 运行gulp

    ```
    > sudo composer install
    ```

  - 运行gulp

    ```
    > sudo gulp
    ```

* **安装Smarty | PHPExcel | UEditor**

  - 下载Github库
    - Smarty  : https://github.com/smarty-php/smarty
    - PHPExcel: https://github.com/PHPOffice/PHPExcel

  - 放置 Smarty 库, PhpExcel库
    - 放置 Smarty 库 -> install/vendor/smarty/smarty/ 目录下
    - 放置 PHPExcel 库 -> install/vendor/phpoffice/phpexcel/ 目录下

  - 下载 在线编辑器: [UEditor](http://ueditor.baidu.com/website/download.html)
    - 下载 在线编辑器: [UEditor] [1.4.3.3 PHP 版本] UTF-8版
    - 解压 下载文件 到目录 utf8-php
    - 先备份misc/js/onlineditor/ueditor 目录下的文件 到 misc/js/onlineditor/ueditor_bak 下
    - 复制粘贴 utf8-php/ -> misc/js/onlineditor/ueditor 目录下
    - 复制粘贴 misc/js/onlineditor/ueditor_bak -> misc/js/onlineditor/ueditor 即可.

    [说明]
    > UEditor下载版本为:[1.4.3.3 PHP 版本] UTF-8版
