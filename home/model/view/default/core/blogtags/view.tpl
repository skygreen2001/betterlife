{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
    <div><h1>查看博客标签</h1></div>
    <table class="viewdoblock">
        <tr class="entry"><th class="head">标识</th><td class="content">{$blogtags.blogtags_id}</td></tr>
        <tr class="entry"><th class="head">博客编号</th><td class="content">{$blogtags.blog_id}</td></tr>
        <tr class="entry"><th class="head">标签编号</th><td class="content">{$blogtags.tags_id}</td></tr>
    </table>
    <div class="footer" align="center"><my:a href='{$url_base}index.php?go=model.blogtags.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.blogtags.edit&amp;id={$blogtags.blogtags_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>修改博客标签</my:a></div>
</div>
{/block}