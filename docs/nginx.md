# PHP with Nginx

## 安装Nginx

  - sudo apt-get update
  - sudo apt-get install nginx
  - config file: /etc/nginx/nginx.conf
  - sudo service nginx stop
  - sudo service nginx start
  - sudo service nginx restart
  - sudo update-rc.d nginx defaults  [we can make sure that our web server will restart automatically when the server is rebooted by typing]
  - /usr/sbin/nginx -t   [判断Nginx配置是否正确]
  - /usr/sbin/nginx -s reload

## 安装 Php with Nginx

### 安装php-fpm
  - apt-get update
  - sudo apt-get install php-fpm
    - sudo apt-get install php5-fpm  （ubuntu 14.04）
  - vi /etc/php/7.0/fpm/php.ini
    - vi /etc/php5/fpm/php.ini （ubuntu 14.04）
      - cgi.fix_pathinfo=0
  - service php7.0-fpm restart
    - service php5-fpm restart  （ubuntu 14.04）
  - systemctl restart php7.0-fpm.service
  - systemctl restart php7.0-fpm


### 配置PHP网站

  - 新建文件放置在/etc/nginx/目录下: fastcgi_cache   [类似文件: fastcgi_params]

    ```
    fastcgi_cache phpcache;
    fastcgi_cache_key "$scheme$host$request_uri"; # $request_uri includes the request arguments (such as /page.php?arg=value)
    fastcgi_cache_min_uses 2; # after 2 hits, a request receives a cached response
    fastcgi_cache_use_stale updating timeout;
    fastcgi_cache_valid 404 1m;
    fastcgi_cache_valid 500 502 504 5m;
    ```

  - 修改 /etc/nginx/sites-enabled/www.conf

    - https 配置

        ```
        fastcgi_cache_path /tmp/cache levels=1:2 keys_zone=phpcache:10m inactive=30m max_size=500M;
        server {
            listen       443 ssl spdy;
            server_name  www.bb.com;
            server_name  bb.com;
            ssl          on;
            root /var/www/html/bb/;
            index index.php index.html index.htm index.nginx-debian.html;
            location ~* \.php$ {
                #include snippets/fastcgi-php.conf;
                try_files $uri =404;
                fastcgi_split_path_info ^(.+\.php)(/.+)$;
                fastcgi_pass unix:/run/php/php7.0-fpm.sock;
                # fastcgi_pass unix:/var/run/php5-fpm.sock; （ubuntu 14.04 php5.x）
                fastcgi_index index.php;
                include fastcgi_params;
                include fastcgi_cache;
            }

            ssl_certificate   /etc/nginx/ssl/1522722468431.pem;
            ssl_certificate_key  /etc/nginx/ssl/1522722468431.key;
            ssl_session_timeout 5m;
            ssl_protocols TLSv1 TLSv1.1 TLSv1.2;
            ssl_ciphers AESGCM:ALL:!DH:!EXPORT:!RC4:+HIGH:!MEDIUM:!LOW:!aNULL:!eNULL;
            ssl_prefer_server_ciphers on;
        }
        ```

    - http 配置

        ```
        fastcgi_cache_path /tmp/cache levels=1:2 keys_zone=phpcache:10m inactive=30m max_size=500M;
        server{
            listen 80;
            server_name www.bb.com;
            server_name bb.com;
            root /var/www/html/bb/;
            index index.php index.html index.htm index.nginx-debian.html;
            location ~* \.php$ {
                #include snippets/fastcgi-php.conf;
                try_files $uri =404;
                fastcgi_split_path_info ^(.+\.php)(/.+)$;
                fastcgi_pass unix:/run/php/php7.0-fpm.sock;
                # fastcgi_pass unix:/var/run/php5-fpm.sock; （ubuntu 14.04 php5.x）
                fastcgi_index index.php;
                include fastcgi_params;
                include fastcgi_cache;
            }

            error_page 404 https://www.itasktour.com/;
        }
        ```

## php 常用命令

  ```
  - sudo apt-get install php-gd php-curl php-mbstring
  - sudo apt-get install php-mysqli
  - sudo phpenmod mysqli
  - php -m 列表已加载的组件
  ```


## FAQ

  由于nginx与php-fpm之间的一个小bug，会导致这样的现象: 网站中的静态页面 \*.html 都能正常访问，而 \*.php 文件虽然会返回200状态码， 但实际输出给浏览器的页面内容却是空白。

  简而言之，原因是nginx无法正确的将 \*.php 文件的地址传递给php-fpm去解析， 相当于php-fpm接受到了请求，但这请求却指向一个不存在的文件，于是返回空结果。 为了解决这个问题，需要改动nginx默认的fastcgiparams配置文件:

  - vi /etc/nginx/fastcgi_params 在文件的最后增加两行:

    fastcgi_param SCRIPT_FILENAME  $document_root$fastcgi_script_name;
    fastcgi_param PATH_INFO        $fastcgi_script_name;

  - 然后重启一下服务:
    - service php7.0-fpm reload && service nginx reload

## 参考

  * [nginx install](https://www.digitalocean.com/community/tutorials/how-to-install-nginx-on-ubuntu-14-04-lts)
  * [How To Install Linux, Nginx, MySQL, PHP (LEMP stack) in Ubuntu 16.04](https://www.digitalocean.com/community/tutorials/how-to-install-linux-nginx-mysql-php-lemp-stack-in-ubuntu-16-04)
  * [How To Install Linux, Nginx, MySQL, PHP (LEMP) stack on Ubuntu 14.04](https://www.digitalocean.com/community/tutorials/how-to-install-linux-nginx-mysql-php-lemp-stack-on-ubuntu-14-04)
