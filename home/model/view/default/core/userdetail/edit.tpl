{extends file="$template_dir/layout/normal/layout.tpl"}
{block name=body}
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script> -->

    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/bower/select2.min.css" />
    <script type="text/javascript" src="{$template_url}js/bower/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/edit.css" />
    <div class="block">
        <div><h1>{if $userdetail}编辑{else}新增{/if}用户详细信息</h1><p><font color="red">{$message|default:''}</font></p></div>
        <form name="userdetailForm" method="post" enctype="multipart/form-data"><input type="hidden" name="userdetail_id" value="{$userdetail.userdetail_id}"/>
        <table class="viewdoblock">
            {if $userdetail}<tr class="entry"><th class="head">标识</th><td class="content">{$userdetail.userdetail_id}</td></tr>{/if}
            <tr class="entry">
                <th class="head">用户</th>
                <td class="content select">
                    <select id="user_id" name="user_id" class="form-control">
                        <option value="-1">请选择</option>
                        {foreach item=user from=$users}
                        <option value="{$user.user_id}">{$user.username}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr class="entry"><th class="head">真实姓名</th><td class="content"><input type="text" class="edit" name="realname" value="{$userdetail.realname|default:''}"/></td></tr>
            <tr class="entry">
                <th class="head">头像</th>
                <td class="content">
                    <div class="file-upload-container">
                        <input type="text" id="profileTxt" readonly="readonly" class="file-show-path" />
                        <span class="btn-file-browser" id="profileDiv">浏览 ...</span>
                        <input type="file" id="profile" name="profile" style="display:none;" accept="image/*" />
                    </div>
                </td>
            </tr>
            <tr class="entry"><th class="head">国家</th><td class="content"><input type="number" class="edit" name="country" value="{$userdetail.country|default:100}"/></td></tr>
            <tr class="entry"><th class="head">省</th><td class="content"><input type="number" class="edit" name="province" value="{$userdetail.province|default:100}"/></td></tr>
            <tr class="entry"><th class="head">市</th><td class="content"><input type="number" class="edit" name="city" value="{$userdetail.city|default:100}"/></td></tr>
            <tr class="entry"><th class="head">区</th><td class="content"><input type="number" class="edit" name="district" value="{$userdetail.district|default:100}"/></td></tr>
            <tr class="entry"><th class="head">家庭住址</th><td class="content"><input type="text" class="edit" name="address" value="{$userdetail.address|default:''}"/></td></tr>
            <tr class="entry"><th class="head">QQ号</th><td class="content"><input type="text" class="edit" name="qq" value="{$userdetail.qq|default:''}"/></td></tr>
            <tr class="entry">
                <th class="head">会员性别</th>
                <td class="content select">
                    <select id="sex" name="sex" class="form-control"></select>
                </td>
            </tr>
            <tr class="entry"><th class="head">生日</th><td class="content"><input type="text" placeholder="yyyy-mm-dd" class="edit" name="birthday" value="{$userdetail.birthday|default:''}"/></td></tr>
            <tr class="entry">
              <td class="content" colspan="2" align="center">
                <input type="submit" value="提交" class="btnSubmit" />
                <input type="reset" value="重置" class="btnReset" />
              </td>
            </tr>
        </table>
        </form>
        <div class="footer" align="center">
            <my:a href='{$url_base}index.php?go=model.userdetail.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>
            {if $userdetail}
            <my:a href='{$url_base}index.php?go=model.userdetail.view&amp;id={$userdetail.id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>查看用户详细信息</my:a>
            {/if}
        </div>
    </div>

    <script type="text/javascript">
    $(function() {
        $.edit.fileBrowser("#profile", "#profileTxt", "#profileDiv");
        var select_user = {};
        {if $userdetail && $userdetail.user}
        select_user.id   = "{$userdetail.user.user_id}";
        select_user.text = "{$userdetail.user.username}";
        select_user = new Array(select_user);
        {/if}

        var select_sex = {};
        {if $userdetail && $userdetail.sex}
        select_sex.id   = "{$userdetail.sex}";
        select_sex.text = "{$userdetail.sexShow}";
        select_sex = new Array(select_sex);
        {/if}

        $.edit.select2('#user_id', "", select_user);
        $.edit.select2('#sex', "api/web/data/userdetailSex.json", select_sex);
    });
    </script>

{/block}