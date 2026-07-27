# betterlife Agent Instructions

## 语言与注释

- 标识符、类名、方法名、变量名和新增代码使用英文，遵循现有 PHP 命名风格。
- 新增或修改的代码注释、PHPDoc 中的说明文字默认使用简体中文；保留协议名、库名、命令和必要的英文术语。
- 注释只记录意图、约束、非显而易见的原因或兼容性背景，不重复代码本身。
- 修改已有注释时保持中文；除非用户明确要求，不把中文注释翻译成英文。

## 提交说明

- 需要用户提交时，提交说明使用双语格式，先写简洁、准确的中文，再写对应英文；标题和正文均遵循此顺序。
- 标题使用动词开头，概括本次变更；正文说明原因、影响和验证结果，避免罗列实现细节。
- 不要擅自创建提交、修改提交历史或执行破坏性 Git 操作；只有用户明确要求时才提交。
- 项目没有统一的 Conventional Commits 格式，除非用户另有要求，不强行添加英文类型前缀。

## 项目结构

- `index.php` 和 `init.php` 是 Web 请求的启动入口；`Gc.php` 集中配置模块、路径和运行环境。
- `core/` 是框架核心，`home/` 是应用模块，`api/` 是 JSON 接口，`admin/` 是后台入口，`install/` 保存 Composer 与安装相关内容。
- 传统 MVC 请求通常遵循 `go=module.controller.action`，由 `core/main/Router.php` 和 `core/main/Dispatcher.php` 路由、分发。
- 修改模块前先确认其是否在 `Gc::$module_names` 中注册；不要把 API、模板、上传文件或日志误当作同一层代码处理。
- 生成代码、数据库命名和部署约束遵循现有文档，不在代理指令中重复维护一份规则。

## 验证与环境

- 安装依赖：`composer install --ignore-platform-reqs --no-interaction --no-plugins --no-scripts --prefer-dist`。
- 检查 PHP 风格：`composer check-style`；自动修复前先确认改动范围，必要时使用 `composer fix-style`。
- 项目未配置 PHPUnit 测试脚本；修改后优先运行与改动相关的 PHP 命令或接口检查，并说明未覆盖的运行时验证。
- 运行应用前确认 PHP 扩展、数据库连接和 `upload`、`log`、Smarty `templates_c` 等目录的写权限。
- 不要把 `install/vendor`、生成模板、上传文件、日志或其他被忽略的运行产物当作源代码提交。

## 文档入口

- 快速上手、目录和框架用法：[docs/README.md](docs/README.md)
- 安装与运行环境：[docs/deploy.md](docs/deploy.md)
- 数据库和生成模型命名：[docs/database_define_rule.md](docs/database_define_rule.md)
- 自动代码生成：[docs/autocodeconfig.md](docs/autocodeconfig.md)
- Nginx/PHP-FPM：[docs/nginx.md](docs/nginx.md)
- 项目总体约定：[README.md](README.md)

## 工作方式

- 先阅读目标文件及其相邻测试、调用方或文档，再做最小必要修改。
- 保留用户已有的未提交改动；不要为了格式化或重构扩大变更范围。
- 修改后先运行最窄且能验证行为的检查，再视结果扩大验证范围。
