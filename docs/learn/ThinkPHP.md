# ThinkPHP从入门到实践

## 版本

- ThinkPHP版本: V6.0

## 安装与配置

> 运行环境要求PHP7.2+，兼容PHP8.1

### 使用Composer

- 使用国内镜像（阿里云）: composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/
- 安装项目: composer create-project topthink/think tp
- 启动项目测试运行: cd tp && php think run
- 如果本地80端口没有被占用的话，也可以直接使用: php think run -p 80

## 开发

- [开发规范](https://www.kancloud.cn/manual/thinkphp6_0/1037482)
- [目录结构](https://www.kancloud.cn/manual/thinkphp6_0/1037483)

### 开启调试模式

- 应用默认是部署模式
- 复制根路径下 .example.env 文件为 .env
- 可以修改.env文件里的环境变量APP_DEBUG开启调试模式

## 学习资料

- [ThinkPHP 框架](https://www.thinkphp.cn/)
- [ThinkPHP6.0完全开发手册](https://www.kancloud.cn/manual/thinkphp6_0)
- [Laytp Admin](https://www.laytp.com/): 基于ThinkPHP6+LayUI的极速后台开发框架
- [phpEnv](http://www.phpenv.cn/): phpEnv是Windows系统上一款专业优雅强大的集成开发环境

