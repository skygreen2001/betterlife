*.ftp上去文件后，需要设置以下目录权限为全公开：
  - upload
  - attachment
  - log
  - home/admin/view/default/tmp/templates_c
  - home/应用名称/view/default/tmp/templates_c

  *.修改以下配置：
  http.conf
    所有的Deny from all修改成  Allow from all
    需加载模块
      LoadModule rewrite_module modules/mod_rewrite.so

  php.ini
    display_errors = Off

    需加载功能模块:
      php_curl
      php_mbstring
      php_mysqli
      php_gd2
      php_zip
      php_rar

*.运行安装须知：http://localhost/betterlife/install/
