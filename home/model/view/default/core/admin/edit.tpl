{extends file="$template_dir/layout/normal/layout.tpl"}
{block name=body}
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script> -->

    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/bower/select2.min.css" />
    <script type="text/javascript" src="{$template_url}js/bower/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/edit.css" />
    <div class="block">
        <div><h1>{if $admin}编辑{else}新增{/if}系统管理人员</h1><p><font color="red">{$message|default:''}</font></p></div>
        <form name="adminForm" method="post"><input type="hidden" name="admin_id" value="{$admin.admin_id}"/>
        <table class="viewdoblock">
            {if $admin}<tr class="entry"><th class="head">管理员标识</th><td class="content">{$admin.admin_id}</td></tr>{/if}
            <tr class="entry">
                <th class="head">部门</th>
                <td class="content select">
                    <select id="department_id" name="department_id" class="form-control">
                        <option value="-1">请选择</option>
                        {foreach item=department from=$departments}
                        <option value="{$department.department_id}">{$department.department_name}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr class="entry"><th class="head">用户名</th><td class="content"><input type="text" class="edit" name="username" value="{$admin.username|default:''}"/></td></tr>
            <tr class="entry"><th class="head">真实姓名</th><td class="content"><input type="text" class="edit" name="realname" value="{$admin.realname|default:''}"/></td></tr>
            <tr class="entry"><th class="head">密码</th><td class="content"><input type="text" class="edit" name="password" value="{$admin.password|default:''}"/></td></tr>
            <tr class="entry">
                <th class="head">扮演角色</th>
                <td class="content select">
                    <select id="roletype" name="roletype" class="form-control"></select>
                </td>
            </tr>
            <tr class="entry">
                <th class="head">视野</th>
                <td class="content select">
                    <select id="seescope" name="seescope" class="form-control"></select>
                </td>
            </tr>
            <tr class="entry"><th class="head">登录次数</th><td class="content"><input type="number" class="edit" name="loginTimes" value="{$admin.loginTimes|default:100}"/></td></tr>
            <tr class="entry">
              <td class="content" colspan="2" align="center">
                <input type="submit" value="提交" class="btnSubmit" />
                <input type="reset" value="重置" class="btnReset" />
              </td>
            </tr>
        </table>
        </form>
        <div class="footer" align="center">
            <my:a href='{$url_base}index.php?go=model.admin.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>
            {if $admin}
            <my:a href='{$url_base}index.php?go=model.admin.view&amp;id={$admin.id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>查看系统管理人员</my:a>
            {/if}
        </div>
    </div>

    <script type="text/javascript">
    $(function() {
        var select_department = {};
        {if $admin && $admin.department}
        select_department.id   = "{$admin.department.department_id}";
        select_department.text = "{$admin.department.department_name}";
        select_department = new Array(select_department);
        {/if}

        var select_roletype = {};
        {if $admin && $admin.roletype}
        select_roletype.id   = "{$admin.roletype}";
        select_roletype.text = "{$admin.roletypeShow}";
        select_roletype = new Array(select_roletype);
        {/if}
        var select_seescope = {};
        {if $admin && $admin.seescope}
        select_seescope.id   = "{$admin.seescope}";
        select_seescope.text = "{$admin.seescopeShow}";
        select_seescope = new Array(select_seescope);
        {/if}

        $.edit.select2('#department_id', "", select_department);
        $.edit.select2('#roletype', "api/web/data/adminRoletype.json", select_roletype);
        $.edit.select2('#seescope', "api/web/data/adminSeescope.json", select_seescope);
    });
    </script>

{/block}