# 好用的第三方库

本框架考虑到PHP的通用性和易用性，并未使用命名空间，在底层也尽量未使用有命名空间的第三方库；但是这些第三方库的确很优秀，很好用，开发者在网站实际的开发中，可以自己使用这些第三方库；我在这里也将这些第三方库整理如下:

## Laravel带来的库

### 已安装

以下库已随着compser Laravel库的安装，已经安装好，开箱即用。

- [Carbon](https://carbon.nesbot.com/docs/): 日期及时间处理包
- [FakerPHP/Faker](https://fakerphp.github.io/): 生成假数据的PHP库
- [filp/whoops](https://github.com/filp/whoops): 错误处理框架
  - [whoops](http://filp.github.io/whoops/)
  - 本框架(betterlife)中已使用whoops框架，查看core/main/Initializer.php, 属性:EXCEPTION_WAY
  - [ErrorHandler Component](https://github.com/symfony/symfony/tree/6.1/src/Symfony/Component/ErrorHandler): symfony错误处理组件
- [league/flysystem](https://flysystem.thephpleague.com/)
  - Flysystem is a file storage library for PHP. 
  - It provides one interface to interact with many types of filesystems.
  - [Filesystem Abstraction for PHP](https://github.com/thephpleague/flysystem)
  - [集成 Flysystem 实现对文件系统的高级操作](https://laravelacademy.org/post/8419.html)
  - [iiDestiny/flysystem-oss](https://github.com/iiDestiny/flysystem-oss)
  - [Flysystem Adapter for Tencent Cloud](https://github.com/freyo/flysystem-qcloud-cos-v5)
  - [Flysystem Qiniu](https://github.com/overtrue/flysystem-qiniu)
  - [PHP SDK for 新浪云存储](https://github.com/SinaCloudStorage/SinaCloudStorage-SDK-PHP)
- [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv): 从.env文件加载环境变量
- [Dot Access Data](https://github.com/dflydev/dflydev-dot-access-data): 用.访问复杂数据
- [Monolog](https://seldaek.github.io/monolog/): 流行的PHP日志记录库
- [ezyang/htmlpurifier](https://github.com/ezyang/htmlpurifier): 标准、兼容的 HTML 过滤器，支持自定义标签、属性、过滤规则。
- [CssToInlineStyles](https://github.com/tijsverkoyen/CssToInlineStyles): 转换css文件内容如html文件，发送邮件内容很有用

### 未安装 

- [PsySH](https://psysh.org/): PHP交互式控制台
  - [PsySH——PHP交互式控制台](http://vergil.cn/archives/psysh)
- [Ignition](https://flareapp.io/ignition): Error Page for Laravel
- [Lumen](https://lumen.laravel.com/): Laravel微框架

## Symfony库

- [Symfony Mailer](https://symfony.com/doc/current/mailer.html): 发送电子邮件

## 其它

- [Guzzle](https://github.com/guzzle/guzzle): PHP HTTP client

## 好酷的工具网站

- [Install any command on any operating system](https://command-not-found.com/)