{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
     <div class="block">
        <div><h1>{if $blogtags}编辑{else}新增{/if}博客标签</h1><p><font color="red">{$message|default:''}</font></p></div>
        <form name="blogtagsForm" method="post"><input type="hidden" name="blogtags_id" value="{$blogtags.blogtags_id}"/>
        <table class="viewdoblock">
            {if $blogtags}<tr class="entry"><th class="head">标识</th><td class="content">{$blogtags.blogtags_id}</td></tr>{/if}
            <tr class="entry"><th class="head">博客编号</th><td class="content"><input type="text" class="edit" name="blog_id" value="{$blogtags.blog_id}"/></td></tr>
            <tr class="entry"><th class="head">标签编号</th><td class="content"><input type="text" class="edit" name="tags_id" value="{$blogtags.tags_id}"/></td></tr>
            <tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
        </table>
        </form>
        <div class="footer" align="center">
            <my:a href='{$url_base}index.php?go=model.blogtags.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>
            {if $blogtags}
            |<my:a href='{$url_base}index.php?go=model.blogtags.view&amp;id={$blogtags.id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>查看博客标签</my:a>
            {/if}
        </div>
    </div>


{/block}