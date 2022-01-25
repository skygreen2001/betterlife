{extends file="$template_dir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
    <div><h1>查看系统日志</h1></div>
    <table class="viewdoblock">
        <tr class="entry"><td colspan="2" class="v_g_t"><h3>¶ <span>基本信息</span></h3></td></tr>
        <tr class="entry"><th class="head">标识</th><td class="content">{$logsystem.logsystem_id}</td></tr>
        <tr class="entry"><th class="head">日志记录时间</th><td class="content">{$logsystem.logtime}</td></tr>
        <tr class="entry"><th class="head">分类</th><td class="content">{$logsystem.ident}</td></tr>
        <tr class="entry"><th class="head">优先级</th><td class="content">{$logsystem.priorityShow}</td></tr>
        <tr class="entry"><th class="head">日志内容</th><td class="content">{$logsystem.message}</td></tr>
        <tr class="entry v_g_b"><td colspan="2" class="v_g_t"><h3>¶ <span>其他信息</span></h3></td></tr>
        <tr class="entry"><th class="head">标识</th><td class="content">{$logsystem.logsystem_id}</td></tr>
    </table>
    <div class="footer" align="center"><my:a href='{$url_base}index.php?go=model.logsystem.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a><my:a href='{$url_base}index.php?go=model.logsystem.edit&amp;id={$logsystem.logsystem_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>修改系统日志</my:a></div>
</div>
{/block}