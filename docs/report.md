# 报表系统框架

## 目标

网站除了前台(供用户使用)，后台供基础数据设置以及数据的监控，还有报表系统提供数据统计，一般也会将报表系统整合在后台里。
在本框架中对报表系统在实际使用中开发流程和数据进行分析后，提供了一套快速开发报表系统的工具。

## 报表功能

包括报表分页列表、按时间查询、导出功能。

## 前提条件

只需要写好报表的SQL语句

## SQL格式规则要求

- 在报表的SQL语句外面包上一层，形式如下:
    select a '列1', b as '列2', ... from
    (  报表SQL  ) result

- 报表SQL外面这一层SQL有以下规则
  - select 表列名 '报表显示列名',表列名 as '报表显示列名', ... from (  报表SQL  ) result

## 报表代码生成

- 报表代码生成有两种方式:
  - 默认方式是将报表的SQL作为配置放置在misc/sql/report.php文件里。
  - 默认方式还会生成导航代码(报表后台的左侧导航和上方导航)
  - 自定义方式，会生成一套从前台到后台的属于该报表的代码可独立维护
  - 自定义方式，代码包括导航页面、列表页面、js文件、控制器、服务、api接口代码。

- 用途
  - 默认方式生成的代码一般报表简单、易于维护，开发前期方便使用。
  - 自定义方式，报表复杂，需要定制，更强的伸缩，功能复杂，后期需要自己维护，开发后期需要使用。

## 报表代码生成揭秘

### 代码生成工具

  - 访问路径: tools/tools/autocode/report_onekey.php

### 核心代码

  - 模版文件: core/autocode/view/template/report.php
  - 核心代码生成文件: core/autocode/AutoCodeCreateReport.php

### 报表代码生成后结构说明

#### 默认方式生成

  - SQL配置文件: misc/sql/report.php
  - 顶部导航文件: home/report/view/default/layout/normal/navbar.tpl
  - 左侧导航文件: home/report/view/default/layout/normal/sidebar.tpl

#### 自定义方式

  - 顶部导航文件: home/report/view/default/layout/normal/navbar.tpl
  - 左侧导航文件: home/report/view/default/layout/normal/sidebar.tpl
  - api接口文件: api/web/report/report[报表英文名].php
  - 控制器文件 : home/report/action/Action_Report[报表英文名].php
  - 服务文件     : home/report/src/services/Service[报表英文名].php
  - 服务管理器文件:  home/report/src/services/Manager_ReportService.php
  - 显示列表文件  : home/report/view/default/core/report[报表英文名]/lists.tpl
  - Js文件       : home/report/view/default/js/core/report[报表英文名].js
