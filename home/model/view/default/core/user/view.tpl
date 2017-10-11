{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
    <div><h1>查看用户</h1></div>
    <table class="viewdoblock">
        <tr class="entry"><td colspan="2" class="v_g_t"><h3>¶ <span>基本信息</span></h3></td></tr>
        <tr class="entry"><th class="head">用户标识</th><td class="content">{$user.user_id}</td></tr>
        <tr class="entry"><th class="head">用户名</th><td class="content">{$user.username}</td></tr>
        <tr class="entry"><th class="head">用户密码</th><td class="content">{$user.password}</td></tr>
        <tr class="entry"><th class="head">邮箱地址</th><td class="content">{$user.email}</td></tr>
        <tr class="entry"><th class="head">手机电话</th><td class="content">{$user.cellphone}</td></tr>
        <tr class="entry"><th class="head">访问次数</th><td class="content">{$user.loginTimes}</td></tr>
        <tr class="entry">
            <th class="head">通知</th>
            <td class="content">{foreach item=notice from=$user.notices}<span>{$notice.noticeType}</span> {/foreach}
        </tr>
        <tr class="entry">
            <th class="head">角色</th>
            <td class="content">{foreach item=role from=$user.roles}<span>{$role.role_name}</span> {/foreach}
        </tr>
        <tr class="entry v_g_b"><td colspan="2" class="v_g_t"><h3>¶ <span>其他信息</span></h3></td></tr>
        <tr class="entry"><th class="head">用户标识</th><td class="content">{$user.user_id}</td></tr>
        <tr class="entry"><th class="head">提交时间</th><td class="content">{$user.commitTime|date_format:"%Y-%m-%d %H:%M"}</td></tr>
        <tr class="entry"><th class="head">更新时间</th><td class="content">{$user.updateTime|date_format:"%Y-%m-%d %H:%M"}</td></tr>
    </table>
    <div class="footer" align="center"><my:a href='{$url_base}index.php?go=model.user.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.user.edit&amp;id={$user.user_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>修改用户</my:a></div>
</div>
{/block}