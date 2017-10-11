{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
    {if ($online_editor=='CKEditor')}
        {$editorHtml}
        <script>
        $(function(){
            ckeditor_replace_blog_content();
        });
        </script>
    {/if}
     <div class="block">
        <div><h1>{if $blog}编辑{else}新增{/if}博客</h1><p><font color="red">{$message|default:''}</font></p></div>
        <form name="blogForm" method="post" enctype="multipart/form-data"><input type="hidden" name="blog_id" value="{$blog.blog_id}"/>
        <table class="viewdoblock">
            {if $blog}<tr class="entry"><th class="head">标识</th><td class="content">{$blog.blog_id}</td></tr>{/if}
            <tr class="entry"><th class="head">用户标识</th><td class="content"><input type="text" class="edit" name="user_id" value="{$blog.user_id}"/></td></tr>
            <tr class="entry"><th class="head">博客标题</th><td class="content"><input type="text" class="edit" name="blog_name" value="{$blog.blog_name}"/></td></tr>
            <tr class="entry"><th class="head">排序</th><td class="content"><input type="text" class="edit" name="sequenceNo" value="{$blog.sequenceNo}"/></td></tr>
            <tr class="entry"><th class="head">分类编号</th><td class="content"><input type="text" class="edit" name="category_id" value="{$blog.category_id}"/></td></tr>
            <tr class="entry">
                <th class="head">封面</th>
                <td class="content">
                    <div class="file-upload-container">
                        <input type="text" id="icon_urlTxt" readonly="readonly" class="file-show-path" />
                        <span class="btn-file-browser" id="icon_urlDiv">浏览 ...</span>
                        <input type="file" id="icon_url" name="icon_url" style="display:none;" accept="image/*" />
                    </div>
                </td>
            </tr>
            <tr class="entry"><th class="head">是否公开</th><td class="content"><input type="text" class="edit" name="isPublic" value="{$blog.isPublic}"/></td></tr>
            <tr class="entry"><th class="head">状态</th><td class="content"><input type="text" class="edit" name="status" value="{$blog.status}"/></td></tr>
            <tr class="entry"><th class="head">发布日期</th><td class="content"><input type="text" class="edit" name="publish_date" value="{$blog.publish_date}"/></td></tr>
            <tr class="entry"><th class="head">博客内容</th>
                <td class="content">
                    <textarea id="blog_content" name="blog_content">{$blog.blog_content}</textarea>
                </td>
            </tr>
            <tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
        </table>
        </form>
        <div class="footer" align="center">
            <my:a href='{$url_base}index.php?go=model.blog.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>
            {if $blog}
            |<my:a href='{$url_base}index.php?go=model.blog.view&amp;id={$blog.id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>查看博客</my:a>
            {/if}
        </div>
    </div>
    {if ($online_editor == 'UEditor')}
        <script>pageInit_ue_blog_content();</script>
    {/if}
    <script type="text/javascript">
    $(function() {
        $.edit.fileBrowser("#icon_url", "#icon_urlTxt", "#icon_urlDiv");
    });
    </script>

{/block}