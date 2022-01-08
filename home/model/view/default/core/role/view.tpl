{extends file="$template_dir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
    <div><h1>查看角色</h1></div>
    <table class="viewdoblock">
        <tr class="entry"><td colspan="2" class="v_g_t"><h3>¶ <span>基本信息</span></h3></td></tr>
        <tr class="entry"><th class="head">角色标识</th><td class="content">{$role.role_id}</td></tr>
        <tr class="entry"><th class="head">角色名称</th><td class="content">{$role.role_name}</td></tr>
        <tr class="entry">
            <th class="head">功能信息</th>
            <td class="content">{foreach item=functions from=$role.functionss}<span>{$functions.url}</span> {/foreach}
        </tr>
        <tr class="entry v_g_b"><td colspan="2" class="v_g_t"><h3>¶ <span>其他信息</span></h3></td></tr>
        <tr class="entry"><th class="head">角色标识</th><td class="content">{$role.role_id}</td></tr>
        <tr class="entry"><th class="head">提交时间</th><td class="content">{$role.commitTime|date_format:"%Y-%m-%d %H:%M"}</td></tr>
        <tr class="entry"><th class="head">更新时间</th><td class="content">{$role.updateTime|date_format:"%Y-%m-%d %H:%M"}</td></tr>
    </table>
    <div class="footer" align="center"><my:a href='{$url_base}index.php?go=model.role.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a><my:a href='{$url_base}index.php?go=model.role.edit&amp;id={$role.role_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>修改角色</my:a></div>
</div>
{/block}