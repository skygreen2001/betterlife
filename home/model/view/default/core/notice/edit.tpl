{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}

    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script> -->

    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/bower/select2.min.css" />
    <script type="text/javascript" src="{$template_url}js/bower/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/edit.css" />
    <div class="block">
        <div><h1>{if $notice}编辑{else}新增{/if}通知</h1><p><font color="red">{$message|default:''}</font></p></div>
        <form name="noticeForm" method="post"><input type="hidden" name="notice_id" value="{$notice.notice_id}"/>
        <table class="viewdoblock">
            {if $notice}<tr class="entry"><th class="head">编号</th><td class="content">{$notice.notice_id}</td></tr>{/if}
            <tr class="entry"><th class="head">通知分类</th><td class="content"><input type="text" class="edit" name="noticeType" value="{$notice.noticeType}"/></td></tr>
            <tr class="entry"><th class="head">标题</th><td class="content"><input type="text" class="edit" name="title" value="{$notice.title}"/></td></tr>
            <tr class="entry">
                <th class="head">通知内容</th>
                <td class="content">
                    <textarea id="notice_content" name="notice_content">{$notice.notice_content}</textarea>
                </td>
            </tr>
            <tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
        </table>
        </form>
        <div class="footer" align="center">
            <my:a href='{$url_base}index.php?go=model.notice.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>
            {if $notice}
            <my:a href='{$url_base}index.php?go=model.notice.view&amp;id={$notice.id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>查看通知</my:a>
            {/if}
        </div>
    </div>
    {if ($online_editor == 'UEditor')}
        <script>pageInit_ue_notice_content();</script>
    {/if}

{/block}