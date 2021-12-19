{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script> -->

    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/bower/select2.min.css" />
    <script type="text/javascript" src="{$template_url}js/bower/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/edit.css" />
    <div class="block">
        <div><h1>{if $userrole}编辑{else}新增{/if}用户角色</h1><p><font color="red">{$message|default:''}</font></p></div>
        <form name="userroleForm" method="post"><input type="hidden" name="userrole_id" value="{$userrole.userrole_id}"/>
        <table class="viewdoblock">
            {if $userrole}<tr class="entry"><th class="head">标识</th><td class="content">{$userrole.userrole_id}</td></tr>{/if}
            <tr class="entry">
                <th class="head">用户</th>
                <td class="content select">
                    <select id="user_id" name="user_id" class="form-control">
                        <option value="-1">请选择</option>
                        {foreach item=user from=$users}
                        <option value="{$user.user_id}">{$user.username}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr class="entry">
                <th class="head">角色</th>
                <td class="content select">
                    <select id="role_id" name="role_id" class="form-control">
                        <option value="-1">请选择</option>
                        {foreach item=role from=$roles}
                        <option value="{$role.role_id}">{$role.role_name}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr class="entry">
              <td class="content" colspan="2" align="center">
                <input type="submit" value="提交" class="btnSubmit" />
                <input type="reset" value="重置" class="btnReset" />
              </td>
            </tr>
        </table>
        </form>
        <div class="footer" align="center">
            <my:a href='{$url_base}index.php?go=model.userrole.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>
            {if $userrole}
            <my:a href='{$url_base}index.php?go=model.userrole.view&amp;id={$userrole.id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>查看用户角色</my:a>
            {/if}
        </div>
    </div>

    <script type="text/javascript">
    $(function() {
        var select_user = {};
        {if $userrole && $userrole.user}
        select_user.id   = "{$userrole.user.user_id}";
        select_user.text = "{$userrole.user.username}";
        select_user = new Array(select_user);
        {/if}

        var select_role = {};
        {if $userrole && $userrole.role}
        select_role.id   = "{$userrole.role.role_id}";
        select_role.text = "{$userrole.role.role_name}";
        select_role = new Array(select_role);
        {/if}


        $.edit.select2('#user_id', "", select_user);
        $.edit.select2('#role_id', "", select_role);
    });
    </script>

{/block}