{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
    <div><h1>博客列表(共计{$countBlogs}个)</h1></div>
    <table class="viewdoblock">
        <tr class="entry">
            <th class="header">标识</th>
            <th class="header">用户</th>
            <th class="header">用户标识</th>
            <th class="header">博客标题</th>
            <th class="header">排序</th>
            <th class="header">分类</th>
            <th class="header">分类编号</th>
            <th class="header">是否公开</th>
            <th class="header">博客内容</th>
            <th class="header">状态</th>
            <th class="header">发布日期</th>
            <th class="header">操作</th>
        </tr>
        {foreach item=blog from=$blogs}
        <tr class="entry">
            <td class="content">{$blog.blog_id}</td>
            <td class="content">{$blog.user.username}</td>
            <td class="content">{$blog.user_id}</td>
            <td class="content">{$blog.blog_name}</td>
            <td class="content">{$blog.sequenceNo}</td>
            <td class="content">{$blog.category.name}</td>
            <td class="content">{$blog.category_id}</td>
            <td class="content">{$blog.isPublicShow}</td>
            <td class="content">{$blog.blog_content}</td>
            <td class="content">{$blog.statusShow}</td>
            <td class="content">{$blog.publish_date}</td>
            <td class="btnCol"><my:a href="{$url_base}index.php?go=model.blog.view&amp;id={$blog.blog_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}">查看</my:a>|<my:a href="{$url_base}index.php?go=model.blog.edit&amp;id={$blog.blog_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}">修改</my:a>|<my:a href="{$url_base}index.php?go=model.blog.delete&amp;id={$blog.blog_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}">删除</my:a></td>
        </tr>
        {/foreach}
    </table>
    <div class="page-control-bar" align="center"><my:page src='{$url_base}index.php?go=model.blog.lists' /></div>
    <div class="footer" align="center">
        <my:a href='{$url_base}index.php?go=model.blog.edit&amp;pageNo={$smarty.get.pageNo|default:"1"}'>新建</my:a><my:a href='{$url_base}index.php?go=model.index.index'>返回首页</my:a>
    </div>
</div>
{/block}