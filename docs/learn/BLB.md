# Betterlife 与 Laravel

## 龙之路

  - 探索Betterlife和其它框架的整合之道
  - 步骤
    - 在Betterlife里使用其它框架
    - 在其它框架里使用Betterlife
    - Beterlife和其它框架合二为一
  - 何为龙, 中国魂; 吸取各种文化之精要，形神具备; 龙之路是Betterlife要走的下一步，博纳万家之长.

## 在Betterlife里使用Laravel

  - 需新开分支: lb
  - 使用Laravel除MVC之外的其它所有功能
  - [mattstauffer/Torch](https://github.com/mattstauffer/Torch): Examples of using each Illuminate component in non-Laravel applications
  - 路由定制
    - 整合Laravel的路由
  - 整合Laravel的config
    - [Using illuminate/config v5 outside of Laravel](https://madewithlove.com/blog/software-engineering/illuminate-config-v5/)
  - Api: 
    - 类似spring boot、Laravel resource、Restful
    - [Lumen](https://lumen.laravel.com/): Laravel微框架
    - 学习参考: Laravel Sanctum
  - 现状
    - 在Betterlife框架里暂不考虑融入Laravel
    - 喜欢擅长使用Laravel的开发者可自己在开发中使用Laravel
    - 在生产线上发布部署可无需使用Composer 安装 Laravel包
    - 框架唯一一处使用到的是Laravel框架里包含的 `Symfony: polyfill-php81` 注解: #[\ReturnTypeWillChange]
    - 可在Betterlife里直接使用的代码如下
      ```php
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

## 在Laravel里使用Betterlife

  - 需新开分支: bl
  - 使用Betterlife除MVC之外的其它所有功能
  - 包括原型设计、数据对象、代码生成、后台管理、报表生成、各种工具等

## 合二为一

   - 遥遥无期
   - 靠个人, 此路不通; 需要众人的力量; Betterlife说: 人人为我, 我为人人