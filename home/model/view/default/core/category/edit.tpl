{extends file="$template_dir/layout/normal/layout.tpl"}
{block name=body}

    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script> -->

    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/bower/select2.min.css" />
    <script type="text/javascript" src="{$template_url}js/bower/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{$template_url}resources/css/edit.css" />
    <div class="block">
        <div><h1>{if $category}编辑{else}新增{/if}博客分类</h1><p><font color="red">{$message|default:''}</font></p></div>
        <form name="categoryForm" method="post" enctype="multipart/form-data"><input type="hidden" name="category_id" value="{$category.category_id|default:''}"/>
        <table class="viewdoblock">
            {if $category}<tr class="entry"><th class="head">标识</th><td class="content">{$category.category_id}</td></tr>{/if}
            <tr class="entry"><th class="head">序号</th><td class="content"><input type="number" class="edit" name="sequence_no" value="{$category.sequence_no|default:100}"/></td></tr>
            <tr class="entry"><th class="head">名称</th><td class="content"><input type="text" class="edit" name="name" value="{$category.name|default:''}"/></td></tr>
            <tr class="entry">
                <th class="head">图标</th>
                <td class="content">
                    <div class="file-upload-container">
                        <input type="text" id="icon_urlTxt" readonly="readonly" class="file-show-path" />
                        <span class="btn-file-browser" id="icon_urlDiv">浏览 ...</span>
                        <input type="file" id="icon_url" name="icon_url" style="display:none;" accept="image/*" />
                    </div>
                </td>
            </tr>
            <tr class="entry">
                <th class="head">说明</th>
                <td class="content">
                    <textarea id="intro" name="intro" rows="6" cols="60" placeholder="说明">{$category.intro|default:''}</textarea>
                </td>
            </tr
            <tr class="entry">
              <td class="content" colspan="2" align="center">
                <input type="submit" value="提交" class="btnSubmit" />
                <input type="reset" value="重置" class="btnReset" />
              </td>
            </tr>
        </table>
        </form>
        <div class="footer" align="center">
            <my:a href='{$url_base}index.php?go=model.category.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>
            {if $category}
            <my:a href='{$url_base}index.php?go=model.category.view&amp;id={$category.id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>查看博客分类</my:a>
            {/if}
        </div>
    </div>
    {if ($online_editor == 'UEditor')}
        <script>
        $(function() {
            if (typeof UE != 'undefined') {
                pageInit_ue_intro();

                // 在线编辑器设置默认样式
                ue_intro.ready(function() {
                    UE.dom.domUtils.setStyles(ue_intro.body, {
                        'background-color': '#4caf50','color': '#fff','font-family' : "'Microsoft Yahei','Helvetica Neue', Helvetica, STHeiTi, Arial, sans-serif", 'font-size' : '16px'
                    });
                });

            }
        });
        </script>
    {/if}
    <script type="text/javascript">
    $(function() {
        $.edit.fileBrowser("#icon_url", "#icon_urlTxt", "#icon_urlDiv");

    });
    </script>

{/block}