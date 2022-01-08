{extends file="$template_dir/layout/normal/layout.tpl"}
{block name=body}
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script> -->

    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/bower/select2.min.css" />
    <script type="text/javascript" src="{$template_url}js/bower/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/edit.css" />
    <div class="block">
        <div><h1>{if $blogtags}编辑{else}新增{/if}博客标签</h1><p><font color="red">{$message|default:''}</font></p></div>
        <form name="blogtagsForm" method="post"><input type="hidden" name="blogtags_id" value="{$blogtags.blogtags_id}"/>
        <table class="viewdoblock">
            {if $blogtags}<tr class="entry"><th class="head">标识</th><td class="content">{$blogtags.blogtags_id}</td></tr>{/if}
            <tr class="entry">
                <th class="head">博客</th>
                <td class="content select">
                    <select id="blog_id" name="blog_id" class="form-control">
                        <option value="-1">请选择</option>
                        {foreach item=blog from=$blogs}
                        <option value="{$blog.blog_id}">{$blog.blog_name}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr class="entry">
                <th class="head">标签</th>
                <td class="content select">
                    <select id="tags_id" name="tags_id" class="form-control">
                        <option value="-1">请选择</option>
                        {foreach item=tags from=$tagss}
                        <option value="{$tags.tags_id}">{$tags.title}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr class="entry">
              <td class="content" colspan="2" align="center">
                <input type="submit" value="提交" class="btnSubmit" />
                <input type="reset" value="重置" class="btnReset" />
              </td>
            </tr>
        </table>
        </form>
        <div class="footer" align="center">
            <my:a href='{$url_base}index.php?go=model.blogtags.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>
            {if $blogtags}
            <my:a href='{$url_base}index.php?go=model.blogtags.view&amp;id={$blogtags.id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>查看博客标签</my:a>
            {/if}
        </div>
    </div>

    <script type="text/javascript">
    $(function() {
        var select_blog = {};
        {if $blogtags && $blogtags.blog}
        select_blog.id   = "{$blogtags.blog.blog_id}";
        select_blog.text = "{$blogtags.blog.blog_name}";
        select_blog = new Array(select_blog);
        {/if}

        var select_tags = {};
        {if $blogtags && $blogtags.tags}
        select_tags.id   = "{$blogtags.tags.tags_id}";
        select_tags.text = "{$blogtags.tags.title}";
        select_tags = new Array(select_tags);
        {/if}


        $.edit.select2('#blog_id', "", select_blog);
        $.edit.select2('#tags_id', "", select_tags);
    });
    </script>

{/block}