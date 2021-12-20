<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
{* xhtml1-transitional.dtd *}
<html lang="zh-CN" xml:lang="zh-CN" xmlns="http://www.w3.org/1999/xhtml">
  <head>
{include file="$templateDir/layout/normal/header.tpl"}
    <link rel="icon" href="favicon.ico" mce_href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/public.css" />
    <script type="text/javascript" src="{$url_base}misc/js/ajax/jquery/jquery.min.js"></script>
    {* <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script> *}
    <script type="text/javascript" src="{$template_url}js/public.js"></script>
    {$viewObject->css_ready|default:""}
  </head>
  {php}
     flush();
  {/php}
  <body>
    {block name=body}{/block}
    {$viewObject->js_ready|default:""}
  </body>
</html>
