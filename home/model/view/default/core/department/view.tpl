{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<div class="block">
    <div><h1>查看用户所属部门</h1></div>
    <table class="viewdoblock">
        <tr class="entry"><td colspan="2" class="v_g_t"><h3>¶ <span>基本信息</span></h3></td></tr>
        <tr class="entry"><th class="head">编号</th><td class="content">{$department.department_id}</td></tr>
        <tr class="entry"><th class="head">部门名称</th><td class="content">{$department.department_name}</td></tr>
        <tr class="entry"><th class="head">管理者</th><td class="content">{$department.manager}</td></tr>
        <tr class="entry"><th class="head">预算</th><td class="content">{$department.budget}</td></tr>
        <tr class="entry"><th class="head">实际开销</th><td class="content">{$department.actualexpenses}</td></tr>
        <tr class="entry"><th class="head">预估平均工资</th><td class="content">{$department.estsalary}</td></tr>
        <tr class="entry"><th class="head">实际工资</th><td class="content">{$department.actualsalary}</td></tr>
        <tr class="entry v_g_b"><td colspan="2" class="v_g_t"><h3>¶ <span>其他信息</span></h3></td></tr>
        <tr class="entry"><th class="head">编号</th><td class="content">{$department.department_id}</td></tr>
        <tr class="entry"><th class="head">提交时间</th><td class="content">{$department.commitTime|date_format:"%Y-%m-%d %H:%M"}</td></tr>
        <tr class="entry"><th class="head">更新时间</th><td class="content">{$department.updateTime|date_format:"%Y-%m-%d %H:%M"}</td></tr>
    </table>
    <div class="footer" align="center"><my:a href='{$url_base}index.php?go=model.department.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>|<my:a href='{$url_base}index.php?go=model.department.edit&amp;id={$department.department_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>修改用户所属部门</my:a></div>
</div>
{/block}