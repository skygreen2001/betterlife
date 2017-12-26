{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
    <div><h1>查看博客分类</h1></div>
    <table class="viewdoblock">
        <tr class="entry"><td colspan="2" class="v_g_t"><h3>¶ <span>基本信息</span></h3></td></tr>
        <tr class="entry"><th class="head">标识</th><td class="content">{$category.category_id}</td></tr>
        <tr class="entry"><th class="head">序号</th><td class="content">{$category.sequence_no}</td></tr>
        <tr class="entry"><th class="head">名称</th><td class="content">{$category.name}</td></tr>
        <tr class="entry">
            <th class="head">图标</th>
            <td class="content">
                {if $category.icon_url}
                <div class="wrap_2_inner"><img src="{$uploadImg_url|cat:$category.icon_url}" alt="图标"></div><br/>
                存储相对路径:{$category.icon_url}
                {else}
                无上传图片
                {/if}
            </td>
        </tr>
        <tr class="entry"><th class="head">说明</th><td class="content">{$category.intro}</td></tr>
        <tr class="entry"><th class="head">状态</th><td class="content">{$category.status}</td></tr>
        <tr class="entry v_g_b"><td colspan="2" class="v_g_t"><h3>¶ <span>其他信息</span></h3></td></tr>
        <tr class="entry"><th class="head">标识</th><td class="content">{$category.category_id}</td></tr>
        <tr class="entry"><th class="head">创建时间</th><td class="content">{$category.commitTime|date_format:"%Y-%m-%d %H:%M"}</td></tr>
        <tr class="entry"><th class="head">更新时间</th><td class="content">{$category.updateTime|date_format:"%Y-%m-%d %H:%M"}</td></tr>
    </table>
    <div class="footer" align="center"><my:a href='{$url_base}index.php?go=model.category.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a><my:a href='{$url_base}index.php?go=model.category.edit&amp;id={$category.category_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>修改博客分类</my:a></div>
</div>
{/block}