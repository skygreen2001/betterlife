{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script> -->

    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/bower/select2.min.css" />
    <script type="text/javascript" src="{$template_url}js/bower/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/edit.css" />
    <div class="block">
        <div><h1>{if $user}编辑{else}新增{/if}用户</h1><p><font color="red">{$message|default:''}</font></p></div>
        <form name="userForm" method="post"><input type="hidden" name="user_id" value="{$user.user_id}"/>
        <table class="viewdoblock">
            {if $user}<tr class="entry"><th class="head">用户标识</th><td class="content">{$user.user_id}</td></tr>{/if}
            <tr class="entry"><th class="head">用户名</th><td class="content"><input type="text" class="edit" name="username" value="{$user.username}"/></td></tr>
            <tr class="entry"><th class="head">用户密码</th><td class="content"><input type="text" class="edit" name="password" value="{$user.password}"/></td></tr>
            <tr class="entry"><th class="head">邮箱地址</th><td class="content"><input type="text" class="edit" name="email" value="{$user.email}"/></td></tr>
            <tr class="entry"><th class="head">手机电话</th><td class="content"><input type="text" class="edit" name="cellphone" value="{$user.cellphone}"/></td></tr>
            <tr class="entry"><th class="head">访问次数</th><td class="content"><input type="text" class="edit" name="loginTimes" value="{$user.loginTimes}"/></td></tr>
            <tr class="entry">
                <th class="head">通知</th>
                <td class="content select">
                    <select id="notice_id" name="notice_id[]" class="form-control" multiple ></select>
                </td>
            </tr>
            <tr class="entry">
                <th class="head">角色</th>
                <td class="content select">
                    <select id="role_id" name="role_id[]" class="form-control" multiple ></select>
                </td>
            </tr>
            <tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
        </table>
        </form>
        <div class="footer" align="center">
            <my:a href='{$url_base}index.php?go=model.user.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>
            {if $user}
            <my:a href='{$url_base}index.php?go=model.user.view&amp;id={$user.id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>查看用户</my:a>
            {/if}
        </div>
    </div>

    <script type="text/javascript">
    $(function() {
        var select_notice =  new Array({count($user.notices)});
        {foreach $user.notices as $notice}

        var notice       = {};
        notice.id        = "{$notice.notice_id}";
        notice.text      = "{$notice.noticeType}";
        select_notice[{$notice@index}] = notice;
        {/foreach}

        var select_role =  new Array({count($user.roles)});
        {foreach $user.roles as $role}

        var role       = {};
        role.id        = "{$role.role_id}";
        role.text      = "{$role.role_name}";
        select_role[{$role@index}] = role;
        {/foreach}


        $.edit.select2('#notice_id', "api/web/select/notice.php", select_notice);
        $.edit.select2('#role_id', "api/web/select/role.php", select_role);
    });
    </script>

{/block}