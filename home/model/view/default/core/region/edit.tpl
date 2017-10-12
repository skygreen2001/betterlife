{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script> -->

    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/bower/select2.min.css" />
    <script type="text/javascript" src="{$template_url}js/bower/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/edit.css" />
    <div class="block">
        <div><h1>{if $region}编辑{else}新增{/if}地区</h1><p><font color="red">{$message|default:''}</font></p></div>
        <form name="regionForm" method="post"><input type="hidden" name="region_id" value="{$region.region_id}"/>
        <table class="viewdoblock">
            {if $region}<tr class="entry"><th class="head">标识</th><td class="content">{$region.region_id}</td></tr>{/if}
            <tr class="entry"><th class="head">父地区标识</th><td class="content"><input type="text" class="edit" name="parent_id" value="{$region.parent_id}"/></td></tr>
            <tr class="entry"><th class="head">地区名称</th><td class="content"><input type="text" class="edit" name="region_name" value="{$region.region_name}"/></td></tr>
            <tr class="entry">
                <th class="head">地区类型</th>
                <td class="content select">
                    <select id="region_type" name="region_type" class="form-control"></select>
                </td>
            </tr>
            <tr class="entry"><th class="head">目录层级</th><td class="content"><input type="text" class="edit" name="level" value="{$region.level}"/></td></tr>
            <tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
        </table>
        </form>
        <div class="footer" align="center">
            <my:a href='{$url_base}index.php?go=model.region.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>
            {if $region}
            |<my:a href='{$url_base}index.php?go=model.region.view&amp;id={$region.id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>查看地区</my:a>
            {/if}
        </div>
    </div>

    <script type="text/javascript">
    $(function() {
        var select_region_p = {};
        {if $region.region_p}
        select_region_p.id   = "{$region.region_p.region_id}";
        select_region_p.text = "{$region.region_p.region_name}";
        select_region_p =  new Array(select_region_p);
        {/if}

        var select_region_type = {};
        {if $region.region_type}
        select_region_type.id   = "{$region.region_type}";
        select_region_type.text = "{$region.region_typeShow}";
        select_region_type =  new Array(select_region_type);
        {/if}

        $.edit.select2('#region_type', "api/web/data/regionRegion_type.json", select_region_type);
    });
    </script>

{/block}