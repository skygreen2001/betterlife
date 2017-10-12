{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
    {if ($online_editor=='CKEditor')}
        {$editorHtml}
        <script>
        $(function(){
            ckeditor_replace_content();
        });
        </script>
    {/if}
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script> -->

    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/bower/select2.min.css" />
    <script type="text/javascript" src="{$template_url}js/bower/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/edit.css" />
    <div class="block">
        <div><h1>{if $msg}编辑{else}新增{/if}消息</h1><p><font color="red">{$message|default:''}</font></p></div>
        <form name="msgForm" method="post"><input type="hidden" name="msg_id" value="{$msg.msg_id}"/>
        <table class="viewdoblock">
            {if $msg}<tr class="entry"><th class="head">标识</th><td class="content">{$msg.msg_id}</td></tr>{/if}
            <tr class="entry"><th class="head">发送者</th><td class="content"><input type="number" class="edit" name="senderId" value="{$msg.senderId}"/></td></tr>
            <tr class="entry"><th class="head">接收者</th><td class="content"><input type="number" class="edit" name="receiverId" value="{$msg.receiverId}"/></td></tr>
            <tr class="entry"><th class="head">发送者名称</th><td class="content"><input type="text" class="edit" name="senderName" value="{$msg.senderName}"/></td></tr>
            <tr class="entry"><th class="head">接收者名称</th><td class="content"><input type="text" class="edit" name="receiverName" value="{$msg.receiverName}"/></td></tr>
            <tr class="entry">
                <th class="head">发送内容</th>
                <td class="content">
                    <textarea id="content" name="content">{$msg.content}</textarea>
                </td>
            </tr>
            <tr class="entry">
                <th class="head">消息状态</th>
                <td class="content select">
                    <select id="status" name="status" class="form-control"></select>
                </td>
            </tr>
            <tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
        </table>
        </form>
        <div class="footer" align="center">
            <my:a href='{$url_base}index.php?go=model.msg.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>
            {if $msg}
            |<my:a href='{$url_base}index.php?go=model.msg.view&amp;id={$msg.id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>查看消息</my:a>
            {/if}
        </div>
    </div>
    {if ($online_editor == 'UEditor')}
        <script>pageInit_ue_content();</script>
    {/if}
    <script type="text/javascript">
    $(function() {
        var select_status = {};
        {if $msg.status}
        select_status.id   = "{$msg.status}";
        select_status.text = "{$msg.statusShow}";
        select_status =  new Array(select_status);
        {/if}

        $.edit.select2('#status', "api/web/data/msgStatus.json", select_status);
    });
    </script>

{/block}