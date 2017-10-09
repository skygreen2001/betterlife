{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
     <div class="block">
        <div><h1>{if $tags}编辑{else}新增{/if}标签</h1><p><font color="red">{$message|default:''}</font></p></div>
        <form name="tagsForm" method="post"><input type="hidden" name="tags_id" value="{$tags.tags_id}"/>
        <table class="viewdoblock">
        {if $tags}<tr class="entry"><th class="head">标识</th><td class="content">{$tags.tags_id}</td></tr>{/if}
        <tr class="entry"><th class="head">序号</th><td class="content"><input type="text" class="edit" name="sequence_no" value="{$tags.sequence_no}"/></td></tr>
        <tr class="entry"><th class="head">名称</th><td class="content"><input type="text" class="edit" name="title" value="{$tags.title}"/></td></tr>
        <tr class="entry"><th class="head">状态</th><td class="content"><input type="text" class="edit" name="status" value="{$tags.status}"/></td></tr>
            <tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
        </table>
        </form>
        <div class="footer" align="center">
            <my:a href='{$url_base}index.php?go=model.tags.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>
            {if $tags}
            |<my:a href='{$url_base}index.php?go=model.tags.view&amp;id={$tags.id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>查看标签</my:a>
            {/if}
        </div>
    </div>

{/block}