{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
    <div><h1>查看博客</h1></div>
    <table class="viewdoblock">
        <tr class="entry"><td colspan="2" class="v_g_t"><h3>¶ <span>基本信息</span></h3></td></tr>
        <tr class="entry"><th class="head">标识</th><td class="content">{$blog.blog_id}</td></tr>
        <tr class="entry"><th class="head">用户</th><td class="content">{$blog.user.username}</td></tr>
        <tr class="entry"><th class="head">用户标识</th><td class="content">{$blog.user_id}</td></tr>
        <tr class="entry"><th class="head">博客标题</th><td class="content">{$blog.blog_name}</td></tr>
        <tr class="entry"><th class="head">排序</th><td class="content">{$blog.sequenceNo}</td></tr>
        <tr class="entry"><th class="head">分类</th><td class="content">{$blog.category.name}</td></tr>
        <tr class="entry"><th class="head">分类编号</th><td class="content">{$blog.category_id}</td></tr>
        <tr class="entry">
            <th class="head">封面</th>
            <td class="content">
                {if $blog.icon_url}
                <div class="wrap_2_inner"><img src="{$uploadImg_url|cat:$blog.icon_url}" alt="封面"></div><br/>
                存储相对路径:{$blog.icon_url}
                {else}
                无上传图片
                {/if}
            </td>
        </tr>
        <tr class="entry"><th class="head">是否公开</th><td class="content">{$blog.isPublic}</td></tr>
        <tr class="entry"><th class="head">博客内容</th><td class="content">{$blog.blog_content}</td></tr>
        <tr class="entry"><th class="head">状态</th><td class="content">{$blog.status}</td></tr>
        <tr class="entry"><th class="head">发布日期</th><td class="content">{$blog.publish_date}</td></tr>
        <tr class="entry">
            <th class="head">标签</th>
            <td class="content">{foreach item=tags from=$blog.tagss}<span>{$tags.title}</span> {/foreach}
        </tr>
        <tr class="entry v_g_b"><td colspan="2" class="v_g_t"><h3>¶ <span>其他信息</span></h3></td></tr>
        <tr class="entry"><th class="head">标识</th><td class="content">{$blog.blog_id}</td></tr>
        <tr class="entry"><th class="head">创建时间</th><td class="content">{$blog.commitTime|date_format:"%Y-%m-%d %H:%M"}</td></tr>
        <tr class="entry"><th class="head">更新时间</th><td class="content">{$blog.updateTime|date_format:"%Y-%m-%d %H:%M"}</td></tr>
    </table>
    <div class="footer" align="center"><my:a href='{$url_base}index.php?go=model.blog.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a><my:a href='{$url_base}index.php?go=model.blog.edit&amp;id={$blog.blog_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>修改博客</my:a></div>
</div>
{/block}