{extends file="$template_dir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
    <div><h1>查看角色拥有功能</h1></div>
    <table class="viewdoblock">
        <tr class="entry"><td colspan="2" class="v_g_t"><h3>¶ <span>基本信息</span></h3></td></tr>
        <tr class="entry"><th class="head">标识</th><td class="content">{$rolefunctions.rolefunctions_id}</td></tr>
        <tr class="entry"><th class="head">角色</th><td class="content">{$rolefunctions.role.role_name}</td></tr>
        <tr class="entry"><th class="head">角色标识</th><td class="content">{$rolefunctions.role_id}</td></tr>
        <tr class="entry"><th class="head">功能</th><td class="content">{$rolefunctions.functions.functions_id}</td></tr>
        <tr class="entry"><th class="head">功能标识</th><td class="content">{$rolefunctions.functions_id}</td></tr>
        <tr class="entry v_g_b"><td colspan="2" class="v_g_t"><h3>¶ <span>其他信息</span></h3></td></tr>
        <tr class="entry"><th class="head">标识</th><td class="content">{$rolefunctions.rolefunctions_id}</td></tr>
    </table>
    <div class="footer" align="center"><my:a href='{$url_base}index.php?go=model.rolefunctions.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a><my:a href='{$url_base}index.php?go=model.rolefunctions.edit&amp;id={$rolefunctions.rolefunctions_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>修改角色拥有功能</my:a></div>
</div>
{/block}