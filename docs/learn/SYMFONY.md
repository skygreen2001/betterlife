# 学习搭建 Symfony 框架

## 起步
- 安装PHP >= 7.1
- 安装composer: https://getcomposer.org/download/
- 安装symfony: curl -sS https://get.symfony.com/cli/installer | bash
- 创建项目工程
  - 创建web工程: symfony new --full betterlife
  - 创建微服务项目: symfony new bb
  - 创建示例项目: symfony new --demo demo

## 部署运行

- 本地部署运行项目工程:
  - symfony server:start
  - symfony serve
  - php bin/console server:stop

- 部署代理运行
  - 操作系统代理设置为[Automatic Proxy Configuration]: http://127.0.0.1:7080/proxy.pac
    - 参考: [Symfony Local Web Server](https://symfony.com/doc/current/setup/symfony_server.html)
    - [Setting up the Local Proxy]: Open the proxy settings of your operating system (proxy settings in Windows, proxy settings in macOS, proxy settings in Ubuntu)                
  - 命令行运行配置: symfony proxy:domain:attach betterlife
  - 运行代理: symfony proxy:start
  - 浏览器里运行: https://betterlife.wip/

## 开始开发

- [Create your First Page in Symfony](https://symfony.com/doc/current/page_creation.html)
- [创建你的第一个Symfony页面](http://www.symfonychina.com/doc/current/page_creation.html)
- 添加路由注解: composer require annotations
- 创建控制器  : php bin/console make:controller BetterController

## 数据库操作

- 数据库生成代码
  - 从数据库表设计导出配置: sudo php bin/console doctrine:mapping:import --force BbWebBundle yml --path=src/Entity/config
    - 缺点: 无法配置表前缀
    - Doctrine不支持bit:
      - 在config下面增加如下映射
        doctrine:
          dbal:
            mapping_types:
                bit: string
                enum: string
                set: string
                varbinary: string
                tinyblob: text
  - 从数据库表配置生成实体类: sudo php bin/console doctrine:generate:entities BbWebBundle --path=src/Entity/config --no-backup

- 创建实体类  : sudo php bin/console make:entity Blog
- 从实体类到数据库表:
  - 生成移植程序: php bin/console make:migration
  - 执行移植: php bin/console doctrine:migrations:migrate
- 创建CRUD   : php bin/console make:crud Blog


## 可重用的工程模版

- Symfony Demo Application: https://github.com/symfony/demo


## 常用命令行

- 查看项目信息: php bin/console about
- 查看路由信息: php bin/console debug:router
- 查看服务信息: php bin/console debug:autowiring
- 查看配置信息: php bin/console debug:config doctrine
- 查看环境变量: php bin/console debug:container --env-vars
- 查看显示模版: php bin/console debug:twig

## 学习资料

- [Symfony, High Performance PHP Framework for Web Development](https://symfony.com/)
- [Symfony 框架中国开源](http://www.symfonychina.com/)
- [Symfony Getting Started](https://symfony.com/doc/current/index.html)
- [安装和设置Symfony框架](http://www.symfonychina.com/doc/current/setup.html)
