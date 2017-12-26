{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
    <div><h1>查看用户详细信息</h1></div>
    <table class="viewdoblock">
        <tr class="entry"><td colspan="2" class="v_g_t"><h3>¶ <span>基本信息</span></h3></td></tr>
        <tr class="entry"><th class="head">标识</th><td class="content">{$userdetail.userdetail_id}</td></tr>
        <tr class="entry"><th class="head">用户</th><td class="content">{$userdetail.user.username}</td></tr>
        <tr class="entry"><th class="head">用户标识</th><td class="content">{$userdetail.user_id}</td></tr>
        <tr class="entry"><th class="head">真实姓名</th><td class="content">{$userdetail.realname}</td></tr>
        <tr class="entry">
            <th class="head">头像</th>
            <td class="content">
                {if $userdetail.profile}
                <div class="wrap_2_inner"><img src="{$uploadImg_url|cat:$userdetail.profile}" alt="头像"></div><br/>
                存储相对路径:{$userdetail.profile}
                {else}
                无上传图片
                {/if}
            </td>
        </tr>
        <tr class="entry"><th class="head">国家</th><td class="content">{$userdetail.country}</td></tr>
        <tr class="entry"><th class="head">省</th><td class="content">{$userdetail.province}</td></tr>
        <tr class="entry"><th class="head">市</th><td class="content">{$userdetail.city}</td></tr>
        <tr class="entry"><th class="head">区</th><td class="content">{$userdetail.district}</td></tr>
        <tr class="entry"><th class="head">家庭住址</th><td class="content">{$userdetail.address}</td></tr>
        <tr class="entry"><th class="head">QQ号</th><td class="content">{$userdetail.qq}</td></tr>
        <tr class="entry"><th class="head">会员性别</th><td class="content">{$userdetail.sex}</td></tr>
        <tr class="entry"><th class="head">生日</th><td class="content">{$userdetail.birthday}</td></tr>
        <tr class="entry v_g_b"><td colspan="2" class="v_g_t"><h3>¶ <span>其他信息</span></h3></td></tr>
        <tr class="entry"><th class="head">标识</th><td class="content">{$userdetail.userdetail_id}</td></tr>
        <tr class="entry"><th class="head">提交时间</th><td class="content">{$userdetail.commitTime|date_format:"%Y-%m-%d %H:%M"}</td></tr>
        <tr class="entry"><th class="head">更新时间</th><td class="content">{$userdetail.updateTime|date_format:"%Y-%m-%d %H:%M"}</td></tr>
    </table>
    <div class="footer" align="center"><my:a href='{$url_base}index.php?go=model.userdetail.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a><my:a href='{$url_base}index.php?go=model.userdetail.edit&amp;id={$userdetail.userdetail_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>修改用户详细信息</my:a></div>
</div>
{/block}