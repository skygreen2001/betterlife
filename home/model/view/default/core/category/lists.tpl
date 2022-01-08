{extends file="$template_dir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
    <div><h1>博客分类列表(共计{$countCategorys}个)</h1></div>
    <table class="viewdoblock">
        <tr class="entry">
            <th class="header">标识</th>
            <th class="header">序号</th>
            <th class="header">名称</th>
            <th class="header">说明</th>
            <th class="header">状态</th>
            <th class="header">操作</th>
        </tr>
        {foreach item=category from=$categorys}
        <tr class="entry">
            <td class="content">{$category.category_id}</td>
            <td class="content">{$category.sequence_no}</td>
            <td class="content">{$category.name}</td>
            <td class="content">{$category.intro}</td>
            <td class="content">{$category.status}</td>
            <td class="btnCol"><my:a href="{$url_base}index.php?go=model.category.view&amp;id={$category.category_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}">查看</my:a>|<my:a href="{$url_base}index.php?go=model.category.edit&amp;id={$category.category_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}">修改</my:a>|<my:a href="{$url_base}index.php?go=model.category.delete&amp;id={$category.category_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}">删除</my:a></td>
        </tr>
        {/foreach}
    </table>
    <div class="page-control-bar" align="center"><my:page src='{$url_base}index.php?go=model.category.lists' /></div>
    <div class="footer" align="center">
        <my:a href='{$url_base}index.php?go=model.category.edit&amp;pageNo={$smarty.get.pageNo|default:"1"}'>新建</my:a><my:a href='{$url_base}index.php?go=model.index.index'>返回首页</my:a>
    </div>
</div>
{/block}