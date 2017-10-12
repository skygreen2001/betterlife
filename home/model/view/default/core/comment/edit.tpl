{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
    {if ($online_editor=='CKEditor')}
        {$editorHtml}
        <script>
        $(function(){
            ckeditor_replace_comment();
        });
        </script>
    {/if}
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script> -->

    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/bower/select2.min.css" />
    <script type="text/javascript" src="{$template_url}js/bower/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/edit.css" />
    <div class="block">
        <div><h1>{if $comment}编辑{else}新增{/if}评论</h1><p><font color="red">{$message|default:''}</font></p></div>
        <form name="commentForm" method="post"><input type="hidden" name="comment_id" value="{$comment.comment_id}"/>
        <table class="viewdoblock">
            {if $comment}<tr class="entry"><th class="head">标识</th><td class="content">{$comment.comment_id}</td></tr>{/if}
            <tr class="entry">
                <th class="head">评论者</th>
                <td class="content select">
                    <select id="user_id" name="user_id" class="form-control">
                        <option value="-1">请选择</option>
                        {foreach item=user from=$users}
                        <option value="{$user.user_id}">{$user.username}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr class="entry">
                <th class="head">评论</th>
                <td class="content">
                    <textarea id="comment" name="comment">{$comment.comment}</textarea>
                </td>
            </tr>
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
            <tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
        </table>
        </form>
        <div class="footer" align="center">
            <my:a href='{$url_base}index.php?go=model.comment.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>
            {if $comment}
            |<my:a href='{$url_base}index.php?go=model.comment.view&amp;id={$comment.id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>查看评论</my:a>
            {/if}
        </div>
    </div>
    {if ($online_editor == 'UEditor')}
        <script>pageInit_ue_comment();</script>
    {/if}
    <script type="text/javascript">
    $(function() {
        var select_user = {};
        {if $comment.user}
        select_user.id   = "{$comment.user.user_id}";
        select_user.text = "{$comment.user.username}";
        select_user =  new Array(select_user);
        {/if}

        var select_blog = {};
        {if $comment.blog}
        select_blog.id   = "{$comment.blog.blog_id}";
        select_blog.text = "{$comment.blog.blog_name}";
        select_blog =  new Array(select_blog);
        {/if}


        $.edit.select2('#user_id', "", select_user);
        $.edit.select2('#blog_id', "", select_blog);
    });
    </script>

{/block}