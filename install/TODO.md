# TODO

## 基础
  
  - betterlife 从入门到实践
  - 路由器升级
    - 按规则生成的路由，隐藏或改写.php后缀名为.html
    - 路由定制
      - url改写
      - 整合Laravel的路由
      - 学习Symfony、CodeIgniter、ThinkPHP、WordPress的路由规则解决方案
  - 整合Laravel的config 
  - DB: find objects by List ids
  - 异常显示不要显示从ExceptionMe::recordException，而是从调用的地方开始
  - input checkbox on off 在Action和Service中自动转换成 1 0 参考 blog 的 edit
  - 后台的export excel加筛选条件
  - Tag 超链接  onclick ' " 不能解析的问题
  - 表示层View整合Blade Templates
  - Flysystem adapter for betterlife framework(可以单独开一个工程)
  - 级联删除图片，多对多表中的数据
  - markdown 编辑和解析
    - [LEAGUE/COMMONMARK](https://commonmark.thephpleague.com/): Markdown parser
      - [CommonMark Spec](https://spec.commonmark.org/)
      - [GitHub Flavored Markdown Spec](https://github.github.com/gfm/)
  - Api: 
    - 类似spring boot、Laravel resource、Restful
    - 提供给前端Ajax请求、axios使用
    - 提供给jQuery、react、vuejs、angularjs、angular使用
    - 需提供代码示例怎样使用
    - 提供代码生成和api帮助手册(markdown格式): 提供给使用api的前端开发人员
    - 学习参考: Laravel Sanctum 
  - HugeAmountDataPush 结合FakerPHP 伪造数据进行测试和压力测试
    - tools/tools/optimize/stresstest/HugeAmountDataPush.php: 辅助压力测试的工具: 注入海量数据
    - tools/tools/optimize/stresstest/README.md 压力测试
    - 学习参考: Laravel Octane
  - 生成全静态网站(tools/tools/web/makehtml.php)
    - netlify config:https://docs.netlify.com/configure-builds/common-configurations/
    - Material for MkDocs: https://squidfunk.github.io/mkdocs-material/
      - Alternatives: https://squidfunk.github.io/mkdocs-material/alternatives/
  - 自己算法生成站点地图(动态页面)
  - 数据库增加事务支持
  - 数据库生成导出pdf文档
  - 数据库生成导出word文档
  - 配置yml化(dotnet env)
  - 数据库之间可以互通
  - 一对多／多对多／等可通过约束条件获得
  - tools 里的editarea ：codemirror  http://codemirror.net/
  - github + composer: 框架核心 core 作为一个composer library: betterlife.framework
  - php7 and namespace（遵循规范psr）
  - 代码规范
    - php注释需遵循代码规范
    - php注释参考phpdoc要求(https://www.phpdoc.org/)
    - 查看是否有类似phpcs相关的工具
  - [ok]后台的import和export excel
  - [ok]TWIG PHPTEMPLATE
  - [ok]换行符替换成HH = PHP_EOL
  - [ok][使用composer]创建yarn、node和npm的betterlife初始化
  - [KO]Trait 重构 DataObject 和 DataObjectFunc, php 5.2 -> php 5.4
  - [KO]框架核心需加上命名空间, php 5.2 -> php 5.4
  - [KO][通过excel桥接数据到各数据库][生成各种数据库的sql脚本]各种数据库可以备份和导出导入互通
  - [KO][通过命令行和docker解决了下载覆盖UEditor的问题]bower 下载文件后再修改覆盖文件(grunt | gulf)

## 模仿优秀

  - 生成文档
    - 生成Api document by Doctum(https://github.com/code-lts/doctum)
    - 生成Api document by phpDocumentor(https://docs.phpdoc.org)
    - 生成文档 by Sphinx(https://www.sphinx-doc.org/)
  - Laravel rate limiter 
  - Symfony Console: 打造命令行工具
    - 代码生成
      - 生成 Api Resource
  - composer create project script: post-root-package-install 或 post-create-project-cmd 脚本安装ueditor
    - Composer脚本: https://docs.phpcomposer.com/articles/scripts.html
  - CodeIgnitor 4的开发模式显示debug 条
  - CodeIgnitor 4的错误异常显示页面
  - 框架安装脚本
    - Lavarel的安装脚本: https://laravel.build/betterlife
    - Symfony的安装脚本: https://get.symfony.com/cli/installer

## IDE之路

  - 开发Visual Studio Code｜Sublime|Atom|eclipse IDE 插件工具？
  - php 创建初始化:yo（nodejs npm）
  - php ide electron／atom(coffeescript)
  - 开发APICloud 下载包
