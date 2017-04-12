

# 推荐方式

## 安装步骤

* **安装NodeJs**

  下载地址: https://nodejs.org/en/download/

* **安装目录下运行**

  ```
  > npm install
  ```
  [说明]
  > 安装目录是根目录下的install目录, 即本说明文件目录下
* **安装目录下运行**

  ```
  > npm install -g gulp
  > gulp
  ```

  - Linux | Mac 操作系统下
    - 安装 composer

    ```bash
    > php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    > php -r "if (hash_file('SHA384', 'composer-setup.php') === '669656bab3166a7aff8a7506b8cb2d1c292f042046c5a994c43155c0be6190fa0355160742ab2e1c88d40d5be660b410') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
    > php composer-setup.php
    > php -r "unlink('composer-setup.php');"
    > sudo mv composer.phar /usr/local/bin/composer
    > composer config -g repo.packagist composer https://packagist.phpcomposer.com
    ```
    - 运行gulp

  - Windows操作系统下
    - 推荐安装Cmder[http://cmder.net/]
    - 设置系统环境变量Path：添加php.exe所在的路径
    - 安装composer:php -r "readfile('https://getcomposer.org/installer');" | php
    - 运行: php composer.phar install
    - 运行gulp

# 手动方式

* 下载Github库

* 放置 Smarty 库, PhpExcel库
  - 放置 Smarty 库 -> install/vendor/smarty/smarty/ 目录下
  - 放置 PHPExcel 库 -> install/vendor/phpoffice/phpexcel/ 目录下autoload

* 放置 在线编辑器: ueditor -> misc/js/onlineditor 目录下

  先备份该目录下的文件, 等放置好下载的ueditor,再覆盖备份的文件即可.

  [说明]
  > UEditor下载版本为:[1.4.3.3 PHP 版本] UTF-8版
