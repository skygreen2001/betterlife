{extends file="$template_dir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
    <div><h1>查看标签</h1></div>
    <table class="viewdoblock">
        <tr class="entry"><td colspan="2" class="v_g_t"><h3>¶ <span>基本信息</span></h3></td></tr>
        <tr class="entry"><th class="head">标识</th><td class="content">{$tags.tags_id}</td></tr>
        <tr class="entry"><th class="head">序号</th><td class="content">{$tags.sequence_no}</td></tr>
        <tr class="entry"><th class="head">名称</th><td class="content">{$tags.title}</td></tr>
        <tr class="entry"><th class="head">状态</th><td class="content">{$tags.status}</td></tr>
        <tr class="entry v_g_b"><td colspan="2" class="v_g_t"><h3>¶ <span>其他信息</span></h3></td></tr>
        <tr class="entry"><th class="head">标识</th><td class="content">{$tags.tags_id}</td></tr>
        <tr class="entry"><th class="head">创建时间</th><td class="content">{$tags.commitTime|date_format:"%Y-%m-%d %H:%M"}</td></tr>
        <tr class="entry"><th class="head">更新时间</th><td class="content">{$tags.updateTime|date_format:"%Y-%m-%d %H:%M"}</td></tr>
    </table>
    <div class="footer" align="center"><my:a href='{$url_base}index.php?go=model.tags.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a><my:a href='{$url_base}index.php?go=model.tags.edit&amp;id={$tags.tags_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>修改标签</my:a></div>
</div>
{/block}