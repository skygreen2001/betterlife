{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
    <div><h1>博客标签列表(共计{$countBlogtagss}个)</h1></div>
    <table class="viewdoblock">
        <tr class="entry">
            <th class="header">标识</th>
            <th class="header">博客编号</th>
            <th class="header">标签编号</th>
            <th class="header">操作</th>
        </tr>
        {foreach item=blogtags from=$blogtagss}
        <tr class="entry">
            <td class="content">{$blogtags.blogtags_id}</td>
            <td class="content">{$blogtags.blog_id}</td>
            <td class="content">{$blogtags.tags_id}</td>
            <td class="btnCol"><my:a href="{$url_base}index.php?go=model.blogtags.view&amp;id={$blogtags.blogtags_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}">查看</my:a>|<my:a href="{$url_base}index.php?go=model.blogtags.edit&amp;id={$blogtags.blogtags_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}">修改</my:a>|<my:a href="{$url_base}index.php?go=model.blogtags.delete&amp;id={$blogtags.blogtags_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}">删除</my:a></td>
        </tr>
        {/foreach}
    </table>

    <div class="footer" align="center">
        <div><my:page src='{$url_base}index.php?go=model.blogtags.lists' /></div>
        <my:a href='{$url_base}index.php?go=model.blogtags.edit&amp;pageNo={$smarty.get.pageNo|default:"1"}'>新建</my:a>|<my:a href='{$url_base}index.php?go=model.index.index'>返回首页</my:a>
    </div>
</div>
{/block}