{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script> -->

    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/bower/select2.min.css" />
    <script type="text/javascript" src="{$template_url}js/bower/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/edit.css" />
    <div class="block">
        <div><h1>{if $functions}编辑{else}新增{/if}功能信息</h1><p><font color="red">{$message|default:''}</font></p></div>
        <form name="functionsForm" method="post"><input type="hidden" name="functions_id" value="{$functions.functions_id}"/>
        <table class="viewdoblock">
            {if $functions}<tr class="entry"><th class="head">标识</th><td class="content">{$functions.functions_id}</td></tr>{/if}
            <tr class="entry"><th class="head">允许访问的URL权限</th><td class="content"><input type="text" class="edit" name="url" value="{$functions.url}"/></td></tr>
            <tr class="entry">
              <td class="content" colspan="2" align="center">
                <input type="submit" value="提交" class="btnSubmit" />
                <input type="reset" value="重置" class="btnReset" />
              </td>
            </tr>
        </table>
        </form>
        <div class="footer" align="center">
            <my:a href='{$url_base}index.php?go=model.functions.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>
            {if $functions}
            <my:a href='{$url_base}index.php?go=model.functions.view&amp;id={$functions.id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>查看功能信息</my:a>
            {/if}
        </div>
    </div>


{/block}