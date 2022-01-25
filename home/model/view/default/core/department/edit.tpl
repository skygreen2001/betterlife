{extends file="$template_dir/layout/normal/layout.tpl"}
{block name=body}
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script> -->

    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/bower/select2.min.css" />
    <script type="text/javascript" src="{$template_url}js/bower/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/edit.css" />
    <div class="block">
        <div><h1>{if $department}编辑{else}新增{/if}用户所属部门</h1><p><font color="red">{$message|default:''}</font></p></div>
        <form name="departmentForm" method="post"><input type="hidden" name="department_id" value="{$department.department_id|default:''}"/>
        <table class="viewdoblock">
            {if $department}<tr class="entry"><th class="head">编号</th><td class="content">{$department.department_id}</td></tr>{/if}
            <tr class="entry"><th class="head">部门名称</th><td class="content"><input type="text" class="edit" name="department_name" value="{$department.department_name|default:''}"/></td></tr>
            <tr class="entry"><th class="head">管理者</th><td class="content"><input type="text" class="edit" name="manager" value="{$department.manager|default:''}"/></td></tr>
            <tr class="entry"><th class="head">预算</th><td class="content"><input type="number" class="edit" name="budget" value="{$department.budget|default:100}"/></td></tr>
            <tr class="entry"><th class="head">实际开销</th><td class="content"><input type="number" class="edit" name="actualexpenses" value="{$department.actualexpenses|default:100}"/></td></tr>
            <tr class="entry"><th class="head">预估平均工资</th><td class="content"><input type="number" class="edit" name="estsalary" value="{$department.estsalary|default:100}"/></td></tr>
            <tr class="entry"><th class="head">实际工资</th><td class="content"><input type="number" class="edit" name="actualsalary" value="{$department.actualsalary|default:100}"/></td></tr
            <tr class="entry">
              <td class="content" colspan="2" align="center">
                <input type="submit" value="提交" class="btnSubmit" />
                <input type="reset" value="重置" class="btnReset" />
              </td>
            </tr>
        </table>
        </form>
        <div class="footer" align="center">
            <my:a href='{$url_base}index.php?go=model.department.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>
            {if $department}
            <my:a href='{$url_base}index.php?go=model.department.view&amp;id={$department.id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>查看用户所属部门</my:a>
            {/if}
        </div>
    </div>


{/block}