{extends file="$template_dir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
    <div><h1>查看评论</h1></div>
    <table class="viewdoblock">
        <tr class="entry"><td colspan="2" class="v_g_t"><h3>¶ <span>基本信息</span></h3></td></tr>
        <tr class="entry"><th class="head">标识</th><td class="content">{$comment.comment_id}</td></tr>
        <tr class="entry"><th class="head">评论者</th><td class="content">{$comment.user.username}</td></tr>
        <tr class="entry"><th class="head">评论者标识</th><td class="content">{$comment.user_id}</td></tr>
        <tr class="entry"><th class="head">评论</th><td class="content">{$comment.comment}</td></tr>
        <tr class="entry"><th class="head">博客</th><td class="content">{$comment.blog.blog_name}</td></tr>
        <tr class="entry"><th class="head">博客标识</th><td class="content">{$comment.blog_id}</td></tr>
        <tr class="entry v_g_b"><td colspan="2" class="v_g_t"><h3>¶ <span>其他信息</span></h3></td></tr>
        <tr class="entry"><th class="head">标识</th><td class="content">{$comment.comment_id}</td></tr>
        <tr class="entry"><th class="head">创建时间</th><td class="content">{$comment.commitTime|date_format:"%Y-%m-%d %H:%M"}</td></tr>
        <tr class="entry"><th class="head">更新时间</th><td class="content">{$comment.updateTime|date_format:"%Y-%m-%d %H:%M"}</td></tr>
    </table>
    <div class="footer" align="center"><my:a href='{$url_base}index.php?go=model.comment.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a><my:a href='{$url_base}index.php?go=model.comment.edit&amp;id={$comment.comment_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>修改评论</my:a></div>
</div>
{/block}