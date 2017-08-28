{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
    <div><h1>查看博客</h1></div>
    <table class="viewdoblock">
        <tr class="entry"><th class="head">标识</th><td class="content">{$blog.blog_id}</td></tr>
        <tr class="entry"><th class="head">用户</th><td class="content">{$blog.username}</td></tr>
        <tr class="entry"><th class="head">用户标识</th><td class="content">{$blog.user_id}</td></tr>
        <tr class="entry"><th class="head">博客标题</th><td class="content">{$blog.blog_name}</td></tr>
        <tr class="entry"><th class="head">博客头像</th><td class="content">
            <div class="wrap_2_inner"><img src="{$uploadImg_url|cat:$blog.icon_url}" alt="博客头像"></div>
            <br/>存储相对路径:{$blog.icon_url}</td></tr>
        <tr class="entry"><th class="head">博客内容</th><td class="content">{$blog.blog_content}</td></tr>
    </table>
    <div class="footer" align="center"><my:a href='{$url_base}index.php?go=model.blog.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.blog.edit&amp;id={$blog.blog_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>修改博客</my:a></div>
</div>
{/block}