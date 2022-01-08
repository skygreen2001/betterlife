{extends file="$template_dir/layout/normal/layout.tpl"}
{block name=body}
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script> -->

    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/bower/select2.min.css" />
    <script type="text/javascript" src="{$template_url}js/bower/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/edit.css" />
    <div class="block">
        <div><h1>{if $usernotice}编辑{else}新增{/if}用户收到通知</h1><p><font color="red">{$message|default:''}</font></p></div>
        <form name="usernoticeForm" method="post"><input type="hidden" name="usernotice_id" value="{$usernotice.usernotice_id}"/>
        <table class="viewdoblock">
            {if $usernotice}<tr class="entry"><th class="head">标识</th><td class="content">{$usernotice.usernotice_id}</td></tr>{/if}
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
                <th class="head">通知</th>
                <td class="content select">
                    <select id="notice_id" name="notice_id" class="form-control">
                        <option value="-1">请选择</option>
                        {foreach item=notice from=$notices}
                        <option value="{$notice.notice_id}">{$notice.noticeType}</option>
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
            <my:a href='{$url_base}index.php?go=model.usernotice.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>
            {if $usernotice}
            <my:a href='{$url_base}index.php?go=model.usernotice.view&amp;id={$usernotice.id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>查看用户收到通知</my:a>
            {/if}
        </div>
    </div>

    <script type="text/javascript">
    $(function() {
        var select_user = {};
        {if $usernotice && $usernotice.user}
        select_user.id   = "{$usernotice.user.user_id}";
        select_user.text = "{$usernotice.user.username}";
        select_user = new Array(select_user);
        {/if}

        var select_notice = {};
        {if $usernotice && $usernotice.notice}
        select_notice.id   = "{$usernotice.notice.notice_id}";
        select_notice.text = "{$usernotice.notice.noticeType}";
        select_notice = new Array(select_notice);
        {/if}


        $.edit.select2('#user_id', "", select_user);
        $.edit.select2('#notice_id', "", select_notice);
    });
    </script>

{/block}