# Laravel从入门到实践

## 版本

- Laravel版本: 8.75.0

## 安装与配置

### 使用Docker

- 安装项目: curl -s "https://laravel.build/betterlife" | bash
- 启动项目: cd betterlife && ./vendor/bin/sail up

### 使用Composer

- 安装项目: composer create-project laravel/laravel betterlife
- 启动项目: cd betterlife && php artisan serve

### 环境要求

- PHP >= 7.3
- PHP Extension
  - BCMath
  - Ctype
  - Fileinfo
  - JSON
  - Mbstring
  - OpenSSL
  - PDO
  - Tokenizer
  - XML

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

## Valet

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
  - 关闭Valet: valet stop
  - 开启Valet: valet start
  - 重启Valet: valet restart

  - 绑定应用: cd betterlife && valet link
  - 访问网站: http://betterlife.test/
    - http://betterlife[同应用目录名称].test/
    - 任何二级域名也可以访问，如: http://abc.betterlife.test
  - 查看列表: valet links
  - 取消绑定: valet unlink betterlife

## Telescope

  - Telescope 调试工具是本地 Laravel 开发环境的绝佳伴侣。
  - Telescope 可以洞察进入应用程序的请求、异常、日志条目、数据库查询、队列任务、邮件、通知、缓存操作、计划任务、变量输出。
  - 安装Telescope, 仅本地使用: 
    ```
      composer require laravel/telescope --dev
      php artisan telescope:install
      php artisan migrate
    ```
  - 浏览器访问Telescope: /telescope 
    - 如当前应用: http://betterlife.test/telescope

## 安装Laravel Package

  - [Packalyst](https://packalyst.com/) 
    - 拥有15000多个Laravel Package安装包可使用
  - [安装laravel-debugbar](https://packalyst.com/packages/package/barryvdh/laravel-debugbar)
  - 修改根路径下composer.json文件
    ```
      {
          "name": "laravel/laravel",
          ......
          "require": {
              ......
              "barryvdh/laravel-debugbar": "v3.6.5"
          }
          ......
      }
    ```
  - 删除根路径下的composer.lock文件，在根路径下运行: composer install
  - 浏览器网页下方会显示调试条，可修改根路径下.env文件的属性: APP_DEBUG 显示隐藏它
  - 配置: 修改根路径下.env文件的属性
    - DEBUGBAR_ENABLED=false
    - DEBUGBAR_EDITOR=vscode

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

## 启动工具包

- 创建权限认证: Laravel UI
  ```
      composer require laravel/ui
      php artisan ui bootstrap --auth
      npm install && npm run dev
      npm run watch
  ```
  - views页面放置在resources/views目录下

- 创建权限认证: Laravel Breeze
  - 使用Blade模版语言
    ```
      composer require laravel/breeze --dev
      php artisan breeze:install
      npm install && npm run dev
      php artisan migrate
      npm run watch
    ```
    - views页面放置在resources/views目录下

  - 使用Inertia.js前端Vue或React实现
    ```
      php artisan breeze:install vue
      或
      php artisan breeze:install react

      npm install && npm run dev
      php artisan migrate
      npm run watch
    ```
    - views页面放置在resources/js/Pages目录下

  - 提供权限认证后台Api支持
    - 提供给类似Next.js, Nuxt或其它类似的现代JavaScript应用程序权限认证功能
      ```
        php artisan breeze:install api
        php artisan migrate
      ```
      - Netx.js实现: https://github.com/laravel/breeze-next

- 创建权限认证: Laravel Jetstream
  - Jetstream 官方文档: https://jetstream.laravel.com/2.x/introduction.html
  - 安装Jetstream: composer require laravel/jetstream
  - Jetstream 使用 Tailwind CSS 设计样式，并提供 Livewire 或 Inertia.js 驱动的前端脚手架技术栈。
  - [Tailwind CSS](https://tailwindcss.com/)
  - Livewire + Blade
    ```
      php artisan jetstream:install livewire

      php artisan jetstream:install livewire --teams
      npm install && npm run dev
      php artisan migrate
    ```
    - views页面放置在resources/views目录下
    
  - Inertia + Vue
    ```
      php artisan jetstream:install inertia

      php artisan jetstream:install inertia --teams
      npm install && npm run dev
      php artisan migrate
    ```
    - views页面放置在resources/js/Pages目录下

  - 移除Jetstream
    ```
      composer remove laravel/jetstream
    ```

- 自定义权限认证: https://laravel.com/docs/8.x/authentication#authenticating-users

## 创建应用: 博客

  - 创建表: blogs
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

    - 创建权限认证: Laravel UI
      ```
          composer require laravel/ui
          php artisan ui bootstrap --auth
          npm install && npm run dev
          npm run watch
      ```

    - 创建Form
      - 新建路由
        ```
          Route::get('/edit', function () {
              return view('edit');
          });
        ```
      - 新建文件:  resources/views/edit.blade.php
        ```
          @extends('layouts.app')
          @section('content')
              <div class="container">
                  <div class="row">
                      <h1>Submit a blog</h1>
                  </div>
                  <div class="row">
                      <form action="/edit" method="post">
                          @csrf
                          @if ($errors->any())
                              <div class="alert alert-danger" role="alert">
                                  Please fix the following errors
                              </div>
                          @endif
                          <div class="form-group">
                              <label for="title">Title</label>
                              <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="Title" value="{{ old('title') }}">
                              @error('title')
                                  <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                          </div>
                          <div class="form-group">
                              <label for="url">Url</label>
                              <input type="text" class="form-control @error('url') is-invalid @enderror" id="url" name="url" placeholder="URL" value="{{ old('url') }}">
                              @error('url')
                                  <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                          </div>
                          <div class="form-group">
                              <label for="description">Description</label>
                              <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" placeholder="description">{{ old('description') }}</textarea>
                              @error('description')
                                  <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                          </div><br/>
                          <button type="submit" class="btn btn-primary">Submit</button>
                      </form>
                  </div>
              </div>
          @endsection
        ```
      - 在文件 routes/web.php 里新建提交博客路由
        ```
          use Illuminate\Http\Request;

          Route::post('/edit', function (Request $request) {
              $data = $request->validate([
                  'title' => 'required|max:255',
                  'url' => 'required|url|max:255',
                  'description' => 'required|max:255',
              ]);
          
              $blog = tap(new App\Models\Blog($data))->save();
          
              return redirect('/');
          });
        ```
      - 修改app/Models/Blog.php
        ```
          <?php

          namespace App\Models;

          use Illuminate\Database\Eloquent\Factories\HasFactory;
          use Illuminate\Database\Eloquent\Model;

          class Blog extends Model
          {
              use HasFactory;
              protected $fillable = [
                  'title',
                  'url',
                  'description'
              ];
          }
        ```
  - Controllers
    - 基于Model生成Controller: php artisan make:controller BlogController --model=Blog --resource
    - 在文件 routes/web.php 里添加路由: 
      ```
        use App\Http\Controllers\BlogController;
        Route::resource('blog', BlogController::class);
      ``` 
    - 修改文件 app/Http/Controllers/BlogController.php :
      ```
        public function show(Blog $blog)
        {
            // dd($blog);
            return $blog;
        }
      ```
      - 说明: 变量名要和resource的名称相同，
        - 可以通过指令查看函数的变量名是什么: php artisan route:list
        - 如路由名改为book: Route::resource('book', 那么方法名的变量也是book: public function show(Blog $book)
        - 复数也是可以的,如路由名是blogs: Route::resource('blogs', 那么方法名的变量还是blog: public function show(Blog $blog)
    - 访问url: http://betterlife.test/blog/5 
    - [更多关于Resource Controller](https://laravel.com/docs/8.x/controllers#actions-handled-by-resource-controller)
    - 添加分页方法
      - 新建Resource Collection: php artisan make:resource BlogCollection
      - 修改文件: routes/web.php
        ```
          use App\Http\Controllers\BlogController;
          Route::get('/blog/page', [BlogController::class, 'querypage']);
        ```
      - 修改文件: app/Http/Resources/BlogController.php
        ```
          use App\Http\Resources\BlogCollection;
          ......
          /**
           * 分页查询
           * @param  \Illuminate\Http\Request $request
           * @return ResourceCollection
           */
          public function querypage(Request $request) {
              $per_page = $request->per_page;
              return new BlogCollection(Blog::paginate($per_page));
          }
          ......
        ```
    - 访问url: http://betterlife.test/blog/page?page=2&per_page=2

  - 添加测试
    - 测试Form提交
      - 根路径下修改文件: phpunit.xml
        - 配置使用SQLite(in-memory)数据库
          ```
            <php>
                ... 
                <env name="DB_CONNECTION" value="sqlite"/>
                <env name="DB_DATABASE" value=":memory:"/>
                ...
            </php>
          ```
      - 删除Laravel默认生成的Feature测试类: rm tests/Feature/ExampleTest.php
      - 新建Feature测试类: php artisan make:test EditBlogsTest
      - 在新建的文件: tests/Feature/EditBlogsTest.php 新增测试用例如下
        - 合法有效的Blog保存进数据库
        - 校验失败的Blog不能保存进数据库
        - 不允许提交不符合格式的url
        - 当字段长度超过255，校验失败
        - 字段长度在255以内，校验成功
        - 以上测试用例编码如下
          ```
            <?php

            namespace Tests\Feature;

            use Illuminate\Foundation\Testing\RefreshDatabase;
            use Illuminate\Validation\ValidationException;
            use Tests\TestCase;

            class EditBlogsTest extends TestCase
            {
                use RefreshDatabase;
                /**
                * A basic feature test example.
                *
                * @return void
                */
                public function test_example()
                {
                    $response = $this->get('/');

                    $response->assertStatus(200);
                }

                /** @test */
                public function guest_can_submit_a_new_blog() {

                    $response = $this->post('/edit', [
                        'title' => 'Example Title',
                        'url' => 'http://example.com',
                        'description' => 'Example description.',
                    ]);
            
                    $this->assertDatabaseHas('blogs', [
                        'title' => 'Example Title'
                    ]);
            
                    $response
                        ->assertStatus(302)
                        ->assertHeader('Location', url('/'));
            
                    $this
                        ->get('/')
                        ->assertSee('Example Title');
                }
                
                /** @test */
                public function blog_is_not_created_if_validation_fails() {

                    $response = $this->post('/edit');
                    $response->assertSessionHasErrors(['title', 'url', 'description']);
                }
                
                /** @test */
                public function blog_is_not_created_with_an_invalid_url() {

                    $this->withoutExceptionHandling();
                
                    $cases = ['//invalid-url.com', '/invalid-url', 'foo.com'];
                
                    foreach ($cases as $case) {
                        try {
                            $response = $this->post('/edit', [
                                'title' => 'Example Title',
                                'url' => $case,
                                'description' => 'Example description',
                            ]);
                        } catch (ValidationException $e) {
                            $this->assertEquals(
                                'The url must be a valid URL.',
                                $e->validator->errors()->first('url')
                            );
                            continue;
                        }
                        $this->fail("The URL $case passed validation when it should have failed.");
                    }
                }
                
                /** @test */
                public function max_length_fails_when_too_long() {

                    $this->withoutExceptionHandling();
            
                    $title = str_repeat('a', 256);
                    $description = str_repeat('a', 256);
                    $url = 'http://';
                    $url .= str_repeat('a', 256 - strlen($url));
                
                    try {
                        $this->post('/edit', compact('title', 'url', 'description'));
                    } catch(ValidationException $e) {
                        $this->assertEquals(
                            'The title must not be greater than 255 characters.',
                            $e->validator->errors()->first('title')
                        );
                
                        $this->assertEquals(
                            'The url must not be greater than 255 characters.',
                            $e->validator->errors()->first('url')
                        );
                
                        $this->assertEquals(
                            'The description must not be greater than 255 characters.',
                            $e->validator->errors()->first('description')
                        );
                
                        return;
                    }
                
                    $this->fail('Max length should trigger a ValidationException');
                }
                
                /** @test */
                public function max_length_succeeds_when_under_max() {

                    $url = 'http://';
                    $url .= str_repeat('a', 255 - strlen($url));
                
                    $data = [
                        'title' => str_repeat('a', 255),
                        'url' => $url,
                        'description' => str_repeat('a', 255),
                    ];
                
                    $this->post('/edit', $data);
                
                    $this->assertDatabaseHas('blogs', $data);
                }
            }

          ```
      - 运行测试用例: php artisan test

## 在本框架中使用Laravel

- 本框架[skygreen2001/betterlife](https://github.com/skygreen2001/betterlife.git)
- Laravel 框架中底层使用了Symfony框架
  - 如 dd() 实现使用了Symfony 的 VarDumper::dump 方法
- Composer类似Java中的Maven专为解决第三方库的依赖安装使用
  - 本框架通过Composer安装了Laravel库: laravel/framework，相应自动下载了Symfony的底层函数库
  - 本框架核心独立实现，并未使用任何Laravel框架代码，加载Laravel库，是提供给Laravel使用者使用，兼顾两者的优势
- 可在根路径test.php文件中取消相关注释查看运行效果 
- 在本框架中使用Laravel
  ```
      // 可使用Laravel的Helper方法
      // [dd()](https://laravel.com/docs/8.x/helpers#method-dd)
      // 在Visual Studio Code编辑器里如果函数语法提示错误，需修改Visual Studio Code配置: 
      // - "intelephense.files.associations": ["*.php", "*.phtml", "*.inc", "*.module", "*.install", "*.theme", ".engine", ".profile", ".info", ".test"]
      $value1 = "Hello";
      $value2 = "World";
      $value3 = "Skygreen";
      dd($value1, $value2, $value3);
      $v = [$value1, $value2, $value3];
      use Symfony\Component\VarDumper\VarDumper;
      VarDumper::dump($v);

      // [可使用Laravel的Collections](https://laravel.com/docs/8.x/collections)
      // 在Visual Studio Code编辑器里如果函数语法提示错误，需修改Visual Studio Code配置: 
      // - "intelephense.files.associations": ["*.php", "*.phtml", "*.inc", "*.module", "*.install", "*.theme", ".engine", ".profile", ".info", ".test"]
      use Illuminate\Support\Collection;
      use Illuminate\Support\Str;
      Collection::macro('toUpper', function () {
          return $this->map(function ($value) {
              return Str::upper($value);
          });
      });
      $collection = collect(['first', 'second']);
      $upper = $collection->toUpper();
      echo $upper;
  ```

## 部署

- Nginx 配置: https://laravel.com/docs/8.x/deployment#nginx
  - 网站根路径下应指向 public/index.php , 以确保网站配置文件配置项不暴露，及其它网站源码安全问题。

- 生产环境性能优化
  - Autoloader优化: composer install --optimize-autoloader --no-dev
    - 确保根路径下保留composer.lock文件
  - 配置优化     : php artisan config:cache
  - Route加载优化: php artisan route:cache
  - View加载优化 : php artisan view:cache

## 学习资料

- [官网](https://laravel.com/)
- [安装Laravel](https://laravel.com/docs/8.x/installation)
- [Laravel 8 中文文档](https://learnku.com/docs/laravel)
- [Laravel News](https://laravel-news.com/)
  - [Building Your First Laravel Application](https://laravel-news.com/your-first-laravel-application)
- [20 Best Laravel Tutorials](https://kinsta.com/blog/laravel-tutorial/) 
- [Laravel Valet](https://laravel.com/docs/8.x/valet) 
  - [Valet中文文档](https://learnku.com/docs/laravel/8.5/valet/)
- [Packalyst](https://packalyst.com/)
- [Spatie](https://github.com/spatie): craft web applications, courses & open source packages in the Laravel ecosystem