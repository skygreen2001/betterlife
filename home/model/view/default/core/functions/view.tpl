{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
    <div><h1>查看功能信息</h1></div>
    <table class="viewdoblock">
        <tr class="entry"><td colspan="2" class="v_g_t"><h3>¶ <span>基本信息</span></h3></td></tr>
        <tr class="entry"><th class="head">标识</th><td class="content">{$functions.functions_id}</td></tr>
        <tr class="entry"><th class="head">允许访问的URL权限</th><td class="content">{$functions.url}</td></tr>
        <tr class="entry v_g_b"><td colspan="2" class="v_g_t"><h3>¶ <span>其他信息</span></h3></td></tr>
        <tr class="entry"><th class="head">标识</th><td class="content">{$functions.functions_id}</td></tr>
        <tr class="entry"><th class="head">提交时间</th><td class="content">{$functions.commitTime|date_format:"%Y-%m-%d %H:%M"}</td></tr>
        <tr class="entry"><th class="head">更新时间</th><td class="content">{$functions.updateTime|date_format:"%Y-%m-%d %H:%M"}</td></tr>
    </table>
    <div class="footer" align="center"><my:a href='{$url_base}index.php?go=model.functions.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.functions.edit&amp;id={$functions.functions_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>修改功能信息</my:a></div>
</div>
{/block}