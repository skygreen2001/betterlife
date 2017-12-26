{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
    <div><h1>标签列表(共计{$countTagss}个)</h1></div>
    <table class="viewdoblock">
        <tr class="entry">
            <th class="header">标识</th>
            <th class="header">序号</th>
            <th class="header">名称</th>
            <th class="header">状态</th>
            <th class="header">操作</th>
        </tr>
        {foreach item=tags from=$tagss}
        <tr class="entry">
            <td class="content">{$tags.tags_id}</td>
            <td class="content">{$tags.sequence_no}</td>
            <td class="content">{$tags.title}</td>
            <td class="content">{$tags.status}</td>
            <td class="btnCol"><my:a href="{$url_base}index.php?go=model.tags.view&amp;id={$tags.tags_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}">查看</my:a>|<my:a href="{$url_base}index.php?go=model.tags.edit&amp;id={$tags.tags_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}">修改</my:a>|<my:a href="{$url_base}index.php?go=model.tags.delete&amp;id={$tags.tags_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}">删除</my:a></td>
        </tr>
        {/foreach}
    </table>
    <div class="page-control-bar" align="center"><my:page src='{$url_base}index.php?go=model.tags.lists' /></div>
    <div class="footer" align="center">
        <my:a href='{$url_base}index.php?go=model.tags.edit&amp;pageNo={$smarty.get.pageNo|default:"1"}'>新建</my:a><my:a href='{$url_base}index.php?go=model.index.index'>返回首页</my:a>
    </div>
</div>
{/block}