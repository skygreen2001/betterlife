# 使用框架开发工作流定义

## 工具定义

  ﻿* 数据库原型设计: MysqlWorkBench
  * 代码原型     : Betterlife框架的代码生成工具

  * 页面原型设计     : Axure      [Visio]
  * 设计图到静态页面 : Dreamweaver[Firework] | Sublime | Atom

  * 中间件服务器：Apache
  * 部署工具 :Xampp|Wamp
  * 开发语言 :Php
  * 数据库   :Mysql|Postgresql

## 流程定义

  1.数据层：MysqlWorkBench -> Mysql -> Betterlife框架的代码生成工具 -> 生成前端和后台代码
  2.表示层：Axure -> Dreamweaver | Sublime | Atom -> 静态标准Html页面
  3.逻辑层：整合数据层 <=> 表示层[模板：Smarty | twig | PHPTemplate]
