<?php
$view_contents   = isset($view_contents) ? $view_contents : "";
$headers         = isset($headers) ? $headers : "";
$contents        = isset($contents) ? $contents : "";
$realId          = isset($realId) ? $realId : "";
$hasImgFormFlag  = isset($hasImgFormFlag) ? $hasImgFormFlag : "";
$idColumnName    = isset($idColumnName) ? $idColumnName : "";
$edit_contents   = isset($edit_contents) ? $edit_contents : "";
$edit_js_content = isset($edit_js_content) ? $edit_js_content : "";
$ueTextareacontents = isset($ueTextareacontents) ? $ueTextareacontents : "";
$list_template =<<<LISTS
<div class="block">
    <div><h1>{$table_comment}列表(共计{\$count{$classname}s}个)</h1></div>
    <table class="viewdoblock">
        <tr class="entry">
$headers
            <th class="header">操作</th>
        </tr>
        {foreach item={$instancename} from=\${$instancename}s}
        <tr class="entry">
$contents
            <td class="btnCol"><my:a href="{\$url_base}index.php?go={$appname}.{$instancename}.view&amp;id={\${$instancename}.$realId}&amp;pageNo={\$smarty.get.pageNo|default:"1"}">查看</my:a>|<my:a href="{\$url_base}index.php?go={$appname}.{$instancename}.edit&amp;id={\${$instancename}.$realId}&amp;pageNo={\$smarty.get.pageNo|default:"1"}">修改</my:a>|<my:a href="{\$url_base}index.php?go={$appname}.{$instancename}.delete&amp;id={\${$instancename}.$realId}&amp;pageNo={\$smarty.get.pageNo|default:"1"}">删除</my:a></td>
        </tr>
        {/foreach}
    </table>
    <div class="page-control-bar" align="center"><my:page src='{\$url_base}index.php?go={$appname}.{$instancename}.lists' /></div>
    <div class="footer" align="center">
        <my:a href='{\$url_base}index.php?go={$appname}.{$instancename}.edit&amp;pageNo={\$smarty.get.pageNo|default:"1"}'>新建</my:a><my:a href='{\$url_base}index.php?go={$appname}.index.index'>返回首页</my:a>
    </div>
</div>
LISTS;

$edit_template = <<<EDIT
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script> -->

    <link rel="stylesheet" type="text/css" href="{\$template_url}resources/css/bower/select2.min.css" />
    <script type="text/javascript" src="{\$template_url}js/bower/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{\$template_url}resources/css/edit.css" />
    <div class="block">
        <div><h1>{if \${$instancename}}编辑{else}新增{/if}{$table_comment}</h1><p><font color="red">{\$message|default:''}</font></p></div>
        <form name="{$instancename}Form" method="post"$hasImgFormFlag><input type="hidden" name="$idColumnName" value="{\${$instancename}.$idColumnName}"/>
        <table class="viewdoblock">
$edit_contents
            <tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
        </table>
        </form>
        <div class="footer" align="center">
            <my:a href='{\$url_base}index.php?go=$appname.{$instancename}.lists&amp;pageNo={\$smarty.get.pageNo|default:"1"}'>返回列表</my:a>
            {if \${$instancename}}
            <my:a href='{\$url_base}index.php?go=$appname.{$instancename}.view&amp;id={\${$instancename}.id}&amp;pageNo={\$smarty.get.pageNo|default:"1"}'>查看{$table_comment}</my:a>
            {/if}
        </div>
    </div>
$ueTextareacontents
$edit_js_content
EDIT;

$view_template = <<<VIEW
<div class="block">
    <div><h1>查看{$table_comment}</h1></div>
    <table class="viewdoblock">
$view_contents
    </table>
    <div class="footer" align="center"><my:a href='{\$url_base}index.php?go=$appname.{$instancename}.lists&amp;pageNo={\$smarty.get.pageNo|default:"1"}'>返回列表</my:a><my:a href='{\$url_base}index.php?go=$appname.{$instancename}.edit&amp;id={\${$instancename}.$realId}&amp;pageNo={\$smarty.get.pageNo|default:"1"}'>修改{$table_comment}</my:a></div>
</div>
VIEW;
?>
