# 学习Lavarel

## 版本

- Lavarel版本: 8.75.0

## 起步

### 使用Docker

- 安装项目: curl -s "https://laravel.build/betterlife" | bash
- 启动项目: cd betterlife && ./vendor/bin/sail up

### 使用Composer

- 安装项目: composer create-project laravel/laravel betterlife
- 启动项目: cd betterlife && php artisan serve

### 查看命令集

- 查看可使用的命令: php artisan list
  
## 框架目录定义

  - app      : 存放应用核心代码，如模型、控制器、命令、服务等
    - Console   : 包含应用所有自定义的 Artisan 命令
    - Http      : 包含了控制器、中间件以及表单请求等，几乎所有通过 Web 进入应用的请求处理都在这里进行
    - Exceptions: 包含应用的异常处理器，同时还是处理应用抛出的任何异常的好地方。
    - Models    : 包含所有 Eloquent 模型类。 Eloquent ORM 为处理数据库提供了一个漂亮、简单的 ActiveRecord 实现。
    - Providers : 包含程序中所有的的服务提供者。通过在服务容器中绑定服务、注册事件。
  - bootstrap: 存放 Laravel 框架每次启动时用到的文件
  - config   : 用于存放项目所有配置文件
  - database : 存放数据库迁移和填充类文件
  - public   : Web 应用入口目录，用于存放入口文件 index.php 及前端资源文件（CSS、JS、图片等）
  - resources: 用于存放非 PHP 资源文件，如视图模板、语言文件、待编译的 Vue 模板、Sass、JS 源文件
  - routes   : 项目的所有路由文件都定义在这里
  - storage  : 用于存放缓存、日志、上传文件、已经编译过的视图模板等
  - tests    : 存放单元测试及功能测试代码
  - vendor   : 通过 Composer 安装的依赖包都存放在这里，通常该目录会放到 .gitignore 文件里以排除到版本控制系统之外

## 安装Valet
  - Valet 是 Mac 极简主义者的 Laravel 开发环境。
  - 确保 ~/.composer/vendor/bin 目录在系统的「PATH」中。

    ```
      vi ~/.bash_profile
        > export PATH=$PATH:~/.composer/vendor/bin
      source ~/.bash_profile
      echo $PATH
    ```

  - 安装Valet

    ```
      brew update && brew install php
      composer global require laravel/valet
      valet install
    ```

  - 绑定应用: cd betterlife && valet link
  - 访问网站: http://betterlife.test/
    - http://betterlife[同应用目录名称].test/
    - 任何二级域名也可以访问，如: http://abc.betterlife.test
  - 查看列表: valet links
  - 取消绑定: valet unlink betterlife

## 安装数据库
  - 安装Dbngin: https://dbngin.com/
  - 新建数据库 : betterlife
  - 修改根路径下文件.env配置
    ```
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=betterlife
        DB_USERNAME=root
        DB_PASSWORD=
    ```
  - 初始化数据库: php artisan migrate

## 创建权限认证
  - 
    ```
        composer require laravel/ui
        php artisan ui bootstrap --auth
        npm install && npm run dev
        npm run watch
    ```

## 创建应用: 博客
  - 创建表: blog
    ```
        php artisan make:migration create_blogs_table --create=blogs
    ```
  - 表字段定义
    - 打开文件: database/migrations/{{datetime}}_create_blogs_table.php
    - 在up()方法里添加如下代码:
        ```
            Schema::create('blogs', function (Blueprint $table) {
                $table->increments('id');
                $table->string('title');
                $table->string('url')->unique();
                $table->text('description');
                $table->timestamps();
            });
        ```
    - 数据库同步: php artisan migrate
    - 数据库同步: php artisan migrate:fresh
      - 会重置整个数据库。
  
  - 建模和创建模拟数据
    - 建模和数据工厂: php artisan make:model --factory Blog
    - 在工厂类里定义模拟数据数据
      - 文件: database/factories/BlogFactory.php
      - 在definition()方法里添加代码
        ```
            return [
                'title' => substr($this->faker->sentence(2), 0, -1),
                'url' => $this->faker->url,
                'description' => $this->faker->paragraph,
            ];
        ```
    
    - 创建Blog Seeder: php artisan make:seeder BlogsTableSeeder
      - 方便添加代码中定义的数据到表中
    - 修改新生成的文件: database/seeders/BlogsTableSeeder
      ```
        public function run()
        {
            \App\Models\Blog::factory(5)->create();
        }
      ```
    - 修改原本存在的文件: database/seeders/DatabaseSeeder.php
      - 以激活使用: \Database\Seeders\BlogsTableSeeder
      - 修改如下:
        ```
            public function run()
            {
                $this->call(BlogsTableSeeder::class);
            }
        ```
    - 重新同步表和模拟数据到数据库: php artisan migrate:fresh --seed
    - 使用Tink Shell查看模型数据: php artisan tinker
        ```
            \App\Models\Blog::first();
        ```
  - Routing 和 Views
    - 在文件 routes/web.php 里添加路由
      ```
        Route::get('/', function () {
            $blogs = \App\Models\Blog::all();
            return view('welcome', ['blogs' => $blogs]);
        });
      ```
    - 修改文件: resources/views/welcome.blade.php
      ```
        <div class="blogs">
        @foreach ($blogs as $blog)
            <a href="{{ $blog->url }}">{{ $blog->title }}</a><br/>
        @endforeach
        </div>
      ```
    - 创建Form
      - 新建路由
        ```
          Route::get('/submit', function () {
              return view('submit');
          });
        ```
      - 新建文件:  resources/views/submit.blade.php



## 学习资料

- [官网](https://laravel.com/)
- [安装Lavarel](https://laravel.com/docs/8.x/installation)
- [Laravel 8 中文文档](https://learnku.com/docs/laravel)
- [Laravel News](https://laravel-news.com/)
  - [Building Your First Laravel Application](https://laravel-news.com/your-first-laravel-application)
- [Laravel Valet](https://laravel.com/docs/8.x/valet) 
  - [Valet中文文档](https://learnku.com/docs/laravel/8.x/valet/9358)