{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}

    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script> -->

    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/bower/select2.min.css" />
    <script type="text/javascript" src="{$template_url}js/bower/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/edit.css" />
    <div class="block">
        <div><h1>{if $blog}编辑{else}新增{/if}博客</h1><p><font color="red">{$message|default:''}</font></p></div>
        <form name="blogForm" method="post" enctype="multipart/form-data"><input type="hidden" name="blog_id" value="{$blog.blog_id}"/>
        <table class="viewdoblock">
            {if $blog}<tr class="entry"><th class="head">标识</th><td class="content">{$blog.blog_id}</td></tr>{/if}
            <tr class="entry">
                <th class="head">用户</th>
                <td class="content select">
                    <select id="user_id" name="user_id" class="form-control">
                        <option value="-1">请选择</option>
                        {foreach item=user from=$users}
                        <option value="{$user.user_id}">{$user.username}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr class="entry"><th class="head">博客标题</th><td class="content"><input type="text" class="edit" name="blog_name" value="{$blog.blog_name|default:''}"/></td></tr>
            <tr class="entry"><th class="head">排序</th><td class="content"><input type="number" class="edit" name="sequenceNo" value="{$blog.sequenceNo|default:100}"/></td></tr>
            <tr class="entry">
                <th class="head">分类</th>
                <td class="content select">
                    <select id="category_id" name="category_id" class="form-control">
                        <option value="-1">请选择</option>
                        {foreach item=category from=$categorys}
                        <option value="{$category.category_id}">{$category.name}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
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
            <tr class="entry">
                <th class="head">是否公开</th>
                <td class="content">
                    <input type="radio" id="isPublic1" name="isPublic" value="1" {if $blog && $blog.isPublic} checked {/if} /><label for="isPublic1" class="radio_label">是</label>
                    <input type="radio" id="isPublic0" name="isPublic" value="0" {if $blog && !$blog.isPublic} checked {/if}/><label for="isPublic0" class="radio_label">否</label>
                </td>
            </tr>
            <tr class="entry">
                <th class="head">博客内容</th>
                <td class="content">
                    <textarea id="blog_content" name="blog_content">{$blog.blog_content|default:''}</textarea>
                </td>
            </tr>
            <tr class="entry"><th class="head">发布日期</th><td class="content"><input type="text" placeholder="yyyy-mm-dd" class="edit" name="publish_date" value="{$blog.publish_date|default:''}"/></td></tr>
            <tr class="entry">
                <th class="head">标签</th>
                <td class="content select">
                    <select id="tags_id" name="tags_id[]" class="form-control" multiple ></select>
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
            <my:a href='{$url_base}index.php?go=model.blog.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>
            {if $blog}
            <my:a href='{$url_base}index.php?go=model.blog.view&amp;id={$blog.id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>查看博客</my:a>
            {/if}
        </div>
    </div>
    {if ($online_editor == 'UEditor')}
        <script>pageInit_ue_blog_content();</script>
    {/if}
    <script type="text/javascript">
    $(function() {
        $.edit.fileBrowser("#icon_url", "#icon_urlTxt", "#icon_urlDiv");
        var select_user = {};
        {if $blog && $blog.user}
        select_user.id   = "{$blog.user.user_id}";
        select_user.text = "{$blog.user.username}";
        select_user = new Array(select_user);
        {/if}

        var select_category = {};
        {if $blog && $blog.category}
        select_category.id   = "{$blog.category.category_id}";
        select_category.text = "{$blog.category.name}";
        select_category = new Array(select_category);
        {/if}

        var select_tags = new Array();
        {if $blog && $blog.tagss}
        var select_tags = new Array({count($blog.tagss)});
        {foreach $blog.tagss as $tags}

        var tags       = {};
        tags.id        = "{$tags.tags_id}";
        tags.text      = "{$tags.title}";
        select_tags[{$tags@index}] = tags;
        {/foreach}
        {/if}


        $.edit.select2('#user_id', "", select_user);
        $.edit.select2('#category_id', "", select_category);
        $.edit.select2('#tags_id', "api/web/select/tags.php", select_tags);
    });
    </script>

{/block}