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

## nginx 常用配置

  - Nginx 的 Location 配置指令块:
    - Location 配置项在http > server 里面
    - 基本语法形式是: location [=|~|~*|^~|@] pattern { … }
    - [=|~|~*|^~|@] 被称作 location modifier 
    - 关于 location modifier:
      - =
        - 这会完全匹配指定的 pattern, 且这里的 pattern 被限制成简单的字符串, 也就是说这里不能使用正则表达式。
        - 匹配情况:
          - http://website.com/abcd # 正好完全匹配
          - http://website.com/ABCD # 如果运行 Nginx server 的系统本身对大小写不敏感, 比如 Windows,  那么也匹配
          - http://website.com/abcd?param1¶m2 # 忽略查询串参数（query string arguments）, 这里就是 /abcd 后面的 ?param1¶m2
          - http://website.com/abcd/ # 不匹配, 因为末尾存在反斜杠（trailing slash）, Nginx 不认为这种情况是完全匹配
          - http://website.com/abcde # 不匹配, 因为不是完全匹配
      - (None)
        - 可以不写 location modifier, Nginx 仍然能去匹配 pattern。这种情况下, 匹配那些以指定的 patern 开头的 URI, 注意这里的 URI 只能是普通字符串, 不能使用正则表达式。
        - 匹配情况:
          - http://website.com/abcd # 正好完全匹配
          - http://website.com/ABCD # 如果运行 Nginx server 的系统本身对大小写不敏感, 比如 Windows,  那么也匹配
          - http://website.com/abcd?param1¶m2 # 忽略查询串参数（query string arguments）, 这里就是 /abcd 后面的 ?param1¶m2
          - http://website.com/abcd/ # 末尾存在反斜杠（trailing slash）也属于匹配范围内
          - http://website.com/abcde # 仍然匹配, 因为 URI 是以 pattern 开头的
      - ~
        - 这个 location modifier 对大小写敏感, 且 pattern 须是正则表达式
        - 匹配情况:
          - http://website.com/abcd # 完全匹配
          - http://website.com/ABCD # 不匹配, ~ 对大小写是敏感的
          - http://website.com/abcd?param1¶m2 # 忽略查询串参数（query string arguments）, 这里就是 /abcd 后面的 ?param1¶m2
          - http://website.com/abcd/ # 不匹配, 因为末尾存在反斜杠（trailing slash）, 并不匹配正则表达式 ^/abcd$
          - http://website.com/abcde # 不匹配正则表达式 ^/abcd$
      - ~*
        - 与 ~ 类似, 但这个 location modifier 不区分大小写, pattern 须是正则表达式
        - 匹配情况:
          - http://website.com/abcd # 完全匹配
          - http://website.com/ABCD # 匹配, 这就是它不区分大小写的特性
          - http://website.com/abcd?param1¶m2 # 忽略查询串参数（query string arguments）, 这里就是 /abcd 后面的 ?param1¶m2
          - http://website.com/abcd/ # 不匹配, 因为末尾存在反斜杠（trailing slash）, 并不匹配正则表达式 ^/abcd$
          - http://website.com/abcde # 不匹配正则表达式 ^/abcd$
      - ^~
        - 匹配情况类似 2. (None) 的情况, 以指定匹配模式开头的 URI 被匹配, 不同的是, 一旦匹配成功, 那么 Nginx 就停止去寻找其他的 Location 块进行匹配了（与 Location 匹配顺序有关）
      - @
        - 用于定义一个 Location 块, 且该块不能被外部 Client 所访问, 只能被 Nginx 内部配置指令所访问, 比如 try_files or error_page
    - 搜索顺序以及生效优先级:
      - 因为可以定义多个 Location 块, 每个 Location 块可以有各自的 pattern。因此就需要明白（不管是 Nginx 还是你）, 当 Nginx 收到一个请求时, 它是如何去匹配 URI 并找到合适的 Location 的。
      - 要注意的是, 写在配置文件中每个 Server 块中的 Location 块的次序是不重要的, Nginx 会按 location modifier 的优先级来依次用 URI 去匹配 pattern,  顺序如下:
        - 1、 =
        - 2、 (None) 如果 pattern 完全匹配 URI（不是只匹配 URI 的头部）
        - 3、 ^~
        - 4、 ~ 或 ~*
        - 5、 (None) pattern 匹配 URI 的头部

  - 返回设置
    - 返回文本格式
      ```
      location ~ ^/get_text {
        default_type text/html;
        add_header Content-Type 'text/html; charset=utf-8';
        return 200 '您好, 支持中文字体!'; 
      }
      ```
    - 返回json格式
      ```
      location ~ ^/get_json { 
        default_type application/json; 
        return 200 '{"status":"success","result":"hello world!"}'; 
      }
      ```

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

  由于nginx与php-fpm之间的一个小bug, 会导致这样的现象: 网站中的静态页面 \*.html 都能正常访问, 而 \*.php 文件虽然会返回200状态码,  但实际输出给浏览器的页面内容却是空白。

  简而言之, 原因是nginx无法正确的将 \*.php 文件的地址传递给php-fpm去解析,  相当于php-fpm接受到了请求, 但这请求却指向一个不存在的文件, 于是返回空结果。 为了解决这个问题, 需要改动nginx默认的fastcgiparams配置文件:

  - vi /etc/nginx/fastcgi_params 在文件的最后增加两行:

    fastcgi_param SCRIPT_FILENAME  $document_root$fastcgi_script_name;
    fastcgi_param PATH_INFO        $fastcgi_script_name;

  - 然后重启一下服务:
    - service php7.0-fpm reload && service nginx reload

## 参考

  * [Nginx 入门教程](https://xuexb.github.io/learn-nginx/)
    * [Github地址](https://github.com/xuexb/learn-nginx)
  * [NGINX Docs](https://docs.nginx.com/)
  * [Nginx开发从入门到精通](https://github.com/taobao/nginx-book)
  * [OpenResty® 是一款基于 NGINX 和 LuaJIT 的 Web 平台](https://openresty.org) 