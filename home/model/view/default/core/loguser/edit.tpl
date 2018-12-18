{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}

    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script> -->

    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/bower/select2.min.css" />
    <script type="text/javascript" src="{$template_url}js/bower/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/edit.css" />
    <div class="block">
        <div><h1>{if $loguser}编辑{else}新增{/if}用户日志</h1><p><font color="red">{$message|default:''}</font></p></div>
        <form name="loguserForm" method="post"><input type="hidden" name="loguser_id" value="{$loguser.loguser_id}"/>
        <table class="viewdoblock">
            {if $loguser}<tr class="entry"><th class="head">标识</th><td class="content">{$loguser.loguser_id}</td></tr>{/if}
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
                <th class="head">类型</th>
                <td class="content select">
                    <select id="userType" name="userType" class="form-control"></select>
                </td>
            </tr>
            <tr class="entry">
                <th class="head">日志详情</th>
                <td class="content">
                    <textarea id="log_content" name="log_content">{$loguser.log_content}</textarea>
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
            <my:a href='{$url_base}index.php?go=model.loguser.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>
            {if $loguser}
            <my:a href='{$url_base}index.php?go=model.loguser.view&amp;id={$loguser.id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>查看用户日志</my:a>
            {/if}
        </div>
    </div>
    {if ($online_editor == 'UEditor')}
        <script>pageInit_ue_log_content();</script>
    {/if}
    <script type="text/javascript">
    $(function() {
        var select_user = {};
        {if $loguser.user}
        select_user.id   = "{$loguser.user.user_id}";
        select_user.text = "{$loguser.user.username}";
        select_user =  new Array(select_user);
        {/if}

        var select_userType = {};
        {if $loguser.userType}
        select_userType.id   = "{$loguser.userType}";
        select_userType.text = "{$loguser.userTypeShow}";
        select_userType =  new Array(select_userType);
        {/if}

        $.edit.select2('#user_id', "", select_user);
        $.edit.select2('#userType', "api/web/data/loguserUserType.json", select_userType);
    });
    </script>

{/block}