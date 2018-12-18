{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script> -->

    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/bower/select2.min.css" />
    <script type="text/javascript" src="{$template_url}js/bower/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/edit.css" />
    <div class="block">
        <div><h1>{if $role}编辑{else}新增{/if}角色</h1><p><font color="red">{$message|default:''}</font></p></div>
        <form name="roleForm" method="post"><input type="hidden" name="role_id" value="{$role.role_id}"/>
        <table class="viewdoblock">
            {if $role}<tr class="entry"><th class="head">角色标识</th><td class="content">{$role.role_id}</td></tr>{/if}
            <tr class="entry"><th class="head">角色名称</th><td class="content"><input type="text" class="edit" name="role_name" value="{$role.role_name}"/></td></tr>
            <tr class="entry">
                <th class="head">功能信息</th>
                <td class="content select">
                    <select id="functions_id" name="functions_id[]" class="form-control" multiple ></select>
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
            <my:a href='{$url_base}index.php?go=model.role.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>
            {if $role}
            <my:a href='{$url_base}index.php?go=model.role.view&amp;id={$role.id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>查看角色</my:a>
            {/if}
        </div>
    </div>

    <script type="text/javascript">
    $(function() {
        var select_functions =  new Array({count($role.functionss)});
        {foreach $role.functionss as $functions}

        var functions       = {};
        functions.id        = "{$functions.functions_id}";
        functions.text      = "{$functions.url}";
        select_functions[{$functions@index}] = functions;
        {/foreach}


        $.edit.select2('#functions_id', "api/web/select/functions.php", select_functions);
    });
    </script>

{/block}