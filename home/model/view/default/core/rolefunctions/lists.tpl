{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
    <div><h1>角色拥有功能列表(共计{$countRolefunctionss}个)</h1></div>
    <table class="viewdoblock">
        <tr class="entry">
            <th class="header">标识</th>
            <th class="header">角色</th>
            <th class="header">角色标识</th>
            <th class="header">功能</th>
            <th class="header">功能标识</th>
            <th class="header">操作</th>
        </tr>
        {foreach item=rolefunctions from=$rolefunctionss}
        <tr class="entry">
            <td class="content">{$rolefunctions.rolefunctions_id}</td>
            <td class="content">{$rolefunctions.role.role_name}</td>
            <td class="content">{$rolefunctions.role_id}</td>
            <td class="content">{$rolefunctions.functions.functions_id}</td>
            <td class="content">{$rolefunctions.functions_id}</td>
            <td class="btnCol"><my:a href="{$url_base}index.php?go=model.rolefunctions.view&amp;id={$rolefunctions.rolefunctions_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}">查看</my:a>|<my:a href="{$url_base}index.php?go=model.rolefunctions.edit&amp;id={$rolefunctions.rolefunctions_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}">修改</my:a>|<my:a href="{$url_base}index.php?go=model.rolefunctions.delete&amp;id={$rolefunctions.rolefunctions_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}">删除</my:a></td>
        </tr>
        {/foreach}
    </table>
    <div class="page-control-bar" align="center"><my:page src='{$url_base}index.php?go=model.rolefunctions.lists' /></div>
    <div class="footer" align="center">
        <my:a href='{$url_base}index.php?go=model.rolefunctions.edit&amp;pageNo={$smarty.get.pageNo|default:"1"}'>新建</my:a><my:a href='{$url_base}index.php?go=model.index.index'>返回首页</my:a>
    </div>
</div>
{/block}