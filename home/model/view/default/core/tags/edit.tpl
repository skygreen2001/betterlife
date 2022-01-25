{extends file="$template_dir/layout/normal/layout.tpl"}
{block name=body}
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script> -->

    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/bower/select2.min.css" />
    <script type="text/javascript" src="{$template_url}js/bower/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/edit.css" />
    <div class="block">
        <div><h1>{if $tags}编辑{else}新增{/if}标签</h1><p><font color="red">{$message|default:''}</font></p></div>
        <form name="tagsForm" method="post"><input type="hidden" name="tags_id" value="{$tags.tags_id|default:''}"/>
        <table class="viewdoblock">
            {if $tags}<tr class="entry"><th class="head">标识</th><td class="content">{$tags.tags_id}</td></tr>{/if}
            <tr class="entry"><th class="head">序号</th><td class="content"><input type="number" class="edit" name="sequence_no" value="{$tags.sequence_no|default:100}"/></td></tr>
            <tr class="entry"><th class="head">名称</th><td class="content"><input type="text" class="edit" name="title" value="{$tags.title|default:''}"/></td></tr
            <tr class="entry">
              <td class="content" colspan="2" align="center">
                <input type="submit" value="提交" class="btnSubmit" />
                <input type="reset" value="重置" class="btnReset" />
              </td>
            </tr>
        </table>
        </form>
        <div class="footer" align="center">
            <my:a href='{$url_base}index.php?go=model.tags.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>
            {if $tags}
            <my:a href='{$url_base}index.php?go=model.tags.view&amp;id={$tags.id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>查看标签</my:a>
            {/if}
        </div>
    </div>


{/block}