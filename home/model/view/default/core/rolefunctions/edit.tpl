{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script> -->

    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/bower/select2.min.css" />
    <script type="text/javascript" src="{$template_url}js/bower/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/edit.css" />
    <div class="block">
        <div><h1>{if $rolefunctions}编辑{else}新增{/if}角色拥有功能</h1><p><font color="red">{$message|default:''}</font></p></div>
        <form name="rolefunctionsForm" method="post"><input type="hidden" name="rolefunctions_id" value="{$rolefunctions.rolefunctions_id}"/>
        <table class="viewdoblock">
            {if $rolefunctions}<tr class="entry"><th class="head">标识</th><td class="content">{$rolefunctions.rolefunctions_id}</td></tr>{/if}
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
                <th class="head">功能</th>
                <td class="content select">
                    <select id="functions_id" name="functions_id" class="form-control">
                        <option value="-1">请选择</option>
                        {foreach item=functions from=$functionss}
                        <option value="{$functions.functions_id}">{$functions.url}</option>
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
            <my:a href='{$url_base}index.php?go=model.rolefunctions.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>
            {if $rolefunctions}
            <my:a href='{$url_base}index.php?go=model.rolefunctions.view&amp;id={$rolefunctions.id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>查看角色拥有功能</my:a>
            {/if}
        </div>
    </div>

    <script type="text/javascript">
    $(function() {
        var select_role = {};
        {if $rolefunctions && $rolefunctions.role}
        select_role.id   = "{$rolefunctions.role.role_id}";
        select_role.text = "{$rolefunctions.role.role_name}";
        select_role = new Array(select_role);
        {/if}

        var select_functions = {};
        {if $rolefunctions && $rolefunctions.functions}
        select_functions.id   = "{$rolefunctions.functions.functions_id}";
        select_functions.text = "{$rolefunctions.functions.url}";
        select_functions = new Array(select_functions);
        {/if}


        $.edit.select2('#role_id', "", select_role);
        $.edit.select2('#functions_id', "", select_functions);
    });
    </script>

{/block}