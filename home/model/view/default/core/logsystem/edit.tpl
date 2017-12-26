{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script> -->

    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/bower/select2.min.css" />
    <script type="text/javascript" src="{$template_url}js/bower/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/edit.css" />
    <div class="block">
        <div><h1>{if $logsystem}编辑{else}新增{/if}系统日志</h1><p><font color="red">{$message|default:''}</font></p></div>
        <form name="logsystemForm" method="post"><input type="hidden" name="logsystem_id" value="{$logsystem.logsystem_id}"/>
        <table class="viewdoblock">
            {if $logsystem}<tr class="entry"><th class="head">标识</th><td class="content">{$logsystem.logsystem_id}</td></tr>{/if}
            <tr class="entry"><th class="head">日志记录时间</th><td class="content"><input type="text" placeholder="yyyy-mm-dd" class="edit" name="logtime" value="{$logsystem.logtime}"/></td></tr>
            <tr class="entry"><th class="head">分类</th><td class="content"><input type="text" class="edit" name="ident" value="{$logsystem.ident}"/></td></tr>
            <tr class="entry"><th class="head">优先级</th><td class="content"><input type="text" class="edit" name="priority" value="{$logsystem.priority}"/></td></tr>
            <tr class="entry"><th class="head">日志内容</th><td class="content"><input type="text" class="edit" name="message" value="{$logsystem.message}"/></td></tr>
            <tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
        </table>
        </form>
        <div class="footer" align="center">
            <my:a href='{$url_base}index.php?go=model.logsystem.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>
            {if $logsystem}
            <my:a href='{$url_base}index.php?go=model.logsystem.view&amp;id={$logsystem.id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>查看系统日志</my:a>
            {/if}
        </div>
    </div>


{/block}