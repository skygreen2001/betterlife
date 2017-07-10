<?php
$list =<<<LISTS
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

    <div class="footer" align="center">
        <div><my:page src='{\$url_base}index.php?go={$appname}.{$instancename}.lists' /></div>
        <my:a href='{\$url_base}index.php?go={$appname}.{$instancename}.edit&amp;pageNo={\$smarty.get.pageNo|default:"1"}'>新建</my:a>|<my:a href='{\$url_base}index.php?go={$appname}.index.index'>返回首页</my:a>
    </div>
</div>
LISTS;
