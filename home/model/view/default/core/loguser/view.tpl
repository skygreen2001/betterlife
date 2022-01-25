{extends file="$template_dir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
    <div><h1>查看用户日志</h1></div>
    <table class="viewdoblock">
        <tr class="entry"><td colspan="2" class="v_g_t"><h3>¶ <span>基本信息</span></h3></td></tr>
        <tr class="entry"><th class="head">标识</th><td class="content">{$loguser.loguser_id}</td></tr>
        <tr class="entry"><th class="head">用户</th><td class="content">{$loguser.user.username}</td></tr>
        <tr class="entry"><th class="head">用户标识</th><td class="content">{$loguser.user_id}</td></tr>
        <tr class="entry"><th class="head">类型</th><td class="content">{$loguser.userTypeShow}</td></tr>
        <tr class="entry"><th class="head">日志详情</th><td class="content">{$loguser.log_content}</td></tr>
        <tr class="entry v_g_b"><td colspan="2" class="v_g_t"><h3>¶ <span>其他信息</span></h3></td></tr>
        <tr class="entry"><th class="head">标识</th><td class="content">{$loguser.loguser_id}</td></tr>
        <tr class="entry"><th class="head">提交时间</th><td class="content">{$loguser.commitTime|date_format:"%Y-%m-%d %H:%M"}</td></tr>
    </table>
    <div class="footer" align="center"><my:a href='{$url_base}index.php?go=model.loguser.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a><my:a href='{$url_base}index.php?go=model.loguser.edit&amp;id={$loguser.loguser_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>修改用户日志</my:a></div>
</div>
{/block}