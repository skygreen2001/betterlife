<?php

$appname = isset($appname) ? $appname : "";
$classname = isset($classname) ? $classname : "";
$realId = isset($realId) ? $realId : "";
$table_comment = isset($table_comment) ? $table_comment : "";
$classname_rela = isset($classname_rela) ? $classname_rela : "";
$column_contents = isset($column_contents) ? $column_contents : "";
$imgColumnDefs = isset($imgColumnDefs) ? $imgColumnDefs : "";
$classNameField = isset($classNameField) ? $classNameField : "";
$editApiRela             = isset($editApiRela) ? $editApiRela : "";
$editApiImg              = isset($editApiImg) ? $editApiImg : "";
$classname_rela          = isset($classname_rela) ? $classname_rela : "";
$instancename_rela       = isset($instancename_rela) ? $instancename_rela : "";
$realId_m2m              = isset($realId_m2m) ? $realId_m2m : "";
$imgColumnDefs           = isset($imgColumnDefs) ? $imgColumnDefs : "";
$bitColumnDefs           = isset($bitColumnDefs) ? $bitColumnDefs : "";
$statusColumnDefs        = isset($statusColumnDefs) ? $statusColumnDefs : "";
$idColumnDefs            = isset($idColumnDefs) ? $idColumnDefs : "";
$editImgColumn           = isset($editImgColumn) ? $editImgColumn : "";
$editDateColumn          = isset($editDateColumn) ? $editDateColumn : "";
$editEnumColumn          = isset($editEnumColumn) ? $editEnumColumn : "";
$editMulSelColumn        = isset($editMulSelColumn) ? $editMulSelColumn : "";
$editM2MSelColumn        = isset($editM2MSelColumn) ? $editM2MSelColumn : "";
$editBitColumn           = isset($editBitColumn) ? $editBitColumn : "";
$editValidRules          = isset($editValidRules) ? $editValidRules : "";
$editValidMsg            = isset($editValidMsg) ? $editValidMsg : "";
$row_no                  = isset($row_no) ? $row_no : "";
$altImgVal               = isset($altImgVal) ? $altImgVal : "";
$status_switch_show      = isset($status_switch_show) ? $status_switch_show : "";
$showColumns             = isset($showColumns) ? $showColumns : "";
$commitTimeStr           = isset($commitTimeStr) ? $commitTimeStr : "";
$updateTimeStr           = isset($updateTimeStr) ? $updateTimeStr : "";
$hasImgFormFlag          = isset($hasImgFormFlag) ? $hasImgFormFlag : "";
$edit_contents           = isset($edit_contents) ? $edit_contents : "";
$enumJsContent           = isset($enumJsContent) ? $enumJsContent : "";
$rela_js_content         = isset($rela_js_content) ? $rela_js_content : "";
$ueTextareacontents      = isset($ueTextareacontents) ? $ueTextareacontents : "";
$edit_json_enums         = isset($edit_json_enums) ? $edit_json_enums : "";
$navbar_menus            = isset($navbar_menus) ? $navbar_menus : "";
$sidebar_menus           = isset($sidebar_menus) ? $sidebar_menus : "";
$admin_modal_img_preview = isset($admin_modal_img_preview) ? $admin_modal_img_preview : "";

$api_web_template        = <<<API_WEB
<?php
// error_reporting(0);
require_once("../../../init.php");

\$draw         = \$_GET["draw"];
\$page         = \$_GET["page"];
\$page_size    = \$_GET["pageSize"];
\$query        = \$_GET["query"];
\$columns      = \$_GET["columns"];
\$where_clause = "";
\$orderDes     = "$realId desc";

if (!empty(\$query)) {
  \$where_clause = "(";
  \$search_atom  = explode(" ", trim(\$query));
  array_walk(\$search_atom, function(&\$value, \$key) {
    \$value = " ( $classNameField LIKE '%" . \$value . "%' ) ";
  });
  \$where_clause .= implode(" and ", \$search_atom);
  \$where_clause .= ")";
}

foreach (\$columns as \$key => \$column) {
  \$column_search_value = \$column["search"]["value"];
  if (\$column_search_value != "") {
    if (!empty(\$where_clause)) {
      \$where_clause .= " and ";
    }
    \$where_clause .= " " . \$column["data"] . "='" . \$column_search_value . "' ";
  }
}

\$page{$classname}s = {$classname}::queryPageByPageNo( \$page, \$where_clause, \$page_size, \$orderDes );
\$data = \$page{$classname}s["data"];
if (\$data) {
  foreach (\$data as \$key => \${$instancename}) {
$editApiRela
$editApiImg
  }
}
\$recordsFiltered = \$page{$classname}s["count"];
\$recordsTotal    = \$recordsFiltered;
\$result = array(
  'data' => \$data,
  'draw' => \$draw,
  'recordsFiltered' => \$recordsFiltered,
  'recordsTotal' => \$recordsTotal
);

//调试使用的信息
\$result["debug"] = array(
  'param' => array(
    'columns' => \$columns
  ),
  'where' => \$where_clause
);
echo json_encode(\$result);
?>
API_WEB;

$js_template = <<<JS
\$(function() {
    //Datatables中文网[帮助]: http://datatables.club/
    if (\$.dataTable) {
        var infoTable = \$('#infoTable').DataTable({
            "language"  : \$.dataTable.chinese,
            "processing": true,
            "serverSide": true,
            "retrieve"  : true,
            "ajax": {
                "url" : "api/web/list/{$instancename}.php",
                "data": function ( d) {
                    d.query    = \$("#input-search").val();
                    d.pageSize = d.length;
                    d.page     = d.start / d.length + 1;
                    d.limit    = d.start + d.length;
                    return d;
                },
                //可以对返回的结果进行改写
                "dataFilter": function(data) {
                    return data;
                }
            },
            "responsive"   : true,
            "searching"    : false,
            "ordering"     : false,
            "dom"          : '<"top">rt<"bottom"ilp><"clear">',
            "deferRender"  : true,
            "bStateSave"   : true,
            "bLengthChange": true,
            "aLengthMenu"  : [[10, 25, 50, 100,-1],[10, 25, 50, 100,'全部']],
            "columns": [
$column_contents
            ],
            "columnDefs": [
$imgColumnDefs
$bitColumnDefs
$statusColumnDefs
$idColumnDefs
             }
            ],
            "initComplete":function() {
                \$.dataTable.filterDisplay();
            },
            "drawCallback": function(settings) {
                \$.dataTable.pageNumDisplay(this);
                \$.dataTable.filterDisplay();
            }
        });
        \$.dataTable.doFilter(infoTable);

        \$("#btn-{$instancename}-import").click(function() {
            \$("#upload_file").trigger('click');
        });

        \$("#upload_file").change(function() {
            var data = new FormData();
            data.append('upload_file', \$("#upload_file").get(0).files[0]);
            \$.ajax({
                type: 'POST',
                url: "index.php?go=admin.{$instancename}.import",
                data: data,
                success: function(response) {
                    if (response && response.success) {
                        bootbox.alert("导入{$table_comment}成功!");
                        infoTable.draw();
                    } else {
                        bootbox.alert("导入{$table_comment}失败!");
                    }
                    \$("#upload_file").val("");

                },
                error: function(response) {
                    \$("#upload_file").val("");
                },
                processData: false,
                contentType: false,
                dataType   : "json"
            });
        });

        \$("#btn-{$instancename}-export").click(function() {
            var query = \$("#input-search").val();
            \$.getJSON("index.php?go=admin.{$instancename}.export&query=" + query, function(response) {
                window.open(response.data);
            });
        });
    }

    if (\$(".content-wrapper .edit form").length) {
$editImgColumn
$editDateColumn
$editEnumColumn
$editMulSelColumn
$editM2MSelColumn
$editBitColumn
        \$('#edit{$classname}Form').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            // focusInvalid: false,
            focusInvalid: true,
            // debug:true,
            rules: {
$editValidRules
            },
            messages: {
$editValidMsg
            },
            invalidHandler: function (event, validator) { //display error alert on form submit
                \$('.alert-danger', \$('.login-form')).show();
            },
            highlight: function (e) {
                \$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
            },
            success: function (e) {
                \$(e).closest('.form-group').removeClass('has-error').addClass('has-info');
                \$(e).remove();
            },
            errorPlacement: function (error, element) {
                error.insertAfter(element.parent());
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    }
});

JS;

$js_sub_template_img = <<<JS_IMG
                {"orderable": false, "targets": $row_no,
                 "render"   : function(data, type, row) {
                    var result = "";
                    if (data) {
                        var $realId = row.$realId;
                        result = '<a id="' + "imgUrl" + $realId + '" href="#"><img src="' + data + '" class="img-thumbnail" alt="' + row.$altImgVal + '" /></a>';

                        \$("body").off('click', 'a#imgUrl' + $realId);
                        \$("body").on('click', 'a#imgUrl' + $realId, function() {
                            var imgLink = \$('a#imgUrl' + $realId + " img").attr('src');
                            \$('#imagePreview').attr('src', imgLink);
                            \$('#imagePreview-link').attr('href', imgLink);
                            var isShow = \$.dataTable.showImages(\$(this).find("img"), "#imageModal .modal-dialog");
                            if (isShow) \$('#imageModal').modal('show'); else window.open(imgLink, '_blank');
                        });
                    }
                    return result;
                 }
                },

JS_IMG;

$js_sub_template_bit = <<<JS_BIT
                {"orderable": false, "targets": $row_no,
                 "render"   : function(data,type,row) {
                    if (data == 1) {
                        return '是';
                    } else {
                        return '否';
                    }
                 }
                },

JS_BIT;

$js_sub_template_status = <<<JS_STATUS
                {"orderable": false, "targets": $row_no,
                 "render"   : function(data, type, row) {
                    switch (data) {
$status_switch_show
                      default:
                        return '';
                    }
                 }
                },
JS_STATUS;

$js_sub_template_id = <<<JS_ID
                {"orderable": false, "targets": $row_no,
                 "render"   : function(data, type, row) {
                    var result = \$.templates("#actionTmpl").render({ "id"  : data });

                    \$("body").off('click', 'a#info-view' + data);
                    \$("body").on('click', "a#info-view"+data, function() {
                        location.href = 'index.php?go={$appname}.{$instancename}.view&id=' + data;
                    });

                    \$("body").off('click', 'a#info-edit' + data);
                    \$("body").on('click', "a#info-edit"+data, function() {
                        location.href = 'index.php?go={$appname}.{$instancename}.edit&id=' + data;
                    });

                    \$("body").off('click', 'a#info-dele' + data);
                    \$("body").on('click', 'a#info-dele' + data, function() {//删除
                        bootbox.confirm("确定要删除该{$table_comment}:" + data + "?",function(result) {
                            if (result == true) {
                                \$.get("index.php?go={$appname}.{$instancename}.delete&id="+data, function(response, status) {
                                    \$( 'a#info-dele' + data ).parent().parent().css("display", "none");
                                });
                            }
                        });
                    });

                    return result;
                }
JS_ID;

$list_template = <<<LIST_TPL

    <div class="page-container">
        <div class="page-content">
            {include file="\$template_dir/layout/normal/sidebar.tpl"}
            <div class="content-wrapper list-wrapper">
              <div class="main-content">
                <div class="row">
                  <div class="breadcrumb-line">
                    <ul class="breadcrumb">
                      <li><a href="{\$url_base}index.php?go={$appname}.index.index"><i class="icon-home2 position-left"></i>首页</a></li>
                      <li class="active">{$table_comment}</li>
                    </ul>
                  </div>
                </div>

                <div class="container-fluid list">
                    <div class="row">
                        <a class="btn btn-success" href="{\$url_base}index.php?go={$appname}.{$instancename}.edit">新增{$table_comment}</a>
                        <div class="btns-container">
                            <a class="btn btn-default" id="btn-{$instancename}-import">导入</a>
                            <a class="btn btn-default" id="btn-{$instancename}-export">导出</a>
                            <input id="upload_file" name="upload_file" type="file" style="display:none;" accept=".xlsx, .xls" />
                        </div>
                    </div><br/>
                    <div class="row up-container">
                        <div class="filter-up">
                            <div class="filter-up-right col-sm-12">
                                <div>
                                    <i aria-label="search-menu" class="glyphicon glyphicon-search" aria-hidden="true"></i>
                                    <input id="input-search" type="search" placeholder="搜索名称" aria-controls="infoTable" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row table-responsive col-xs-12">
                        <table id="infoTable" class="display nowrap dataTable table table-striped table-bordered">
                            <thead>
                                <tr>
$column_contents
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
              </div>
            </div>

            <div class="clearfix"></div>
        </div>
    </div>

    {include file="\$template_dir/layout/normal/footer.tpl"}
$admin_modal_img_preview
    {literal}
    <script id="actionTmpl" type="text/x-jsrender">
    <a id="info-view{{:id}}" href="#" class="btn-view">查看</a>
    <a id="info-edit{{:id}}" href="#" class="btn-edit">修改</a>
    <a id="info-dele{{:id}}" href="#" class="btn-dele" data-toggle="modal" data-target="#infoModal">删除</a>
    </script>
    {/literal}
    <script src="{\$template_url}js/normal/list.js"></script>
    <script src="{\$template_url}js/core/{$instancename}.js"></script>
LIST_TPL;

$admin_modal_img_template = <<<ADMIN_LIST_IMG_TPL
    <div id="image-model">
      <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">X</span></button>
            </div>
            <div class="modal-body">
              <a id="imagePreview-link" href="#" target="_blank"><img src="" id="imagePreview" /></a>
            </div>
          </div>
        </div>
      </div>
    </div>
ADMIN_LIST_IMG_TPL;

$view_template = <<<VIEW_TPL
    <!-- page container begin -->
    <div class="page-container">
        <!-- page content begin -->
        <div class="page-content">
            {include file="\$template_dir/layout/normal/sidebar.tpl"}

            <!-- main content begin -->
            <div class="content-wrapper">
              <div class="main-content">
                <!-- page header begin -->
                <div class="row">
                  <div class="breadcrumb-line">
                    <ul class="breadcrumb">
                      <li><a href="{\$url_base}index.php?go={$appname}.index.index"><i class="icon-home2 position-left"></i>首页</a></li>
                      <li><a href="{\$url_base}index.php?go={$appname}.{$instancename}.lists">{$table_comment}</a></li>
                      <li class="active">查看{$table_comment}</li>
                    </ul>
                  </div>
                </div>
                <!-- /page header end -->

                <!-- content area begin -->
                <div class="container-fluid view">
                  <div class="row col-xs-12">
                    <h2>{$table_comment}详情</h2><hr>
                    <h4>
                      <span class="glyphicon glyphicon-list-alt"></span>
                      <span>基本信息</span>
                    </h4><hr>
                    <dl>
                      <dt><span>标识</span></dt>
                      <dd><span>{\${$instancename}.$realId}</span></dd>
                    </dl>
$showColumns
                    <h4>
                      <span class="glyphicon glyphicon-list-alt"></span>
                      <span>其他信息</span>
                    </h4><hr>
                    <dl>
                      <dt><span>标识</span></dt>
                      <dd><span>{\${$instancename}.$realId}</span></dd>
                    </dl>
                    <dl>
                      <dt><span>创建时间</span></dt>
                      <dd><span>{\${$instancename}.{$commitTimeStr}|date_format:"%Y-%m-%d %H:%M"}</span></dd>
                    </dl><dl>
                      <dt><span>更新时间</span></dt>
                      <dd><span>{\${$instancename}.{$updateTimeStr}|date_format:"%Y-%m-%d %H:%M"}</span></dd>
                    </dl>
                    <button type="submit" onclick="location.href='{\$url_base}index.php?go={$appname}.{$instancename}.lists'" class="btn btn-info">
                      <span class="glyphicon glyphicon-arrow-left"></span>&nbsp;<span>返回</span>
                    </button>
                    <button type="submit" onclick="location.href='{\$url_base}index.php?go={$appname}.{$instancename}.edit&amp;id={\$smarty.get.id}'" class="btn btn-info">
                      <span class="glyphicon glyphicon-pencil"></span>&nbsp;<span>编辑</span>
                    </button>
                  </div>
                </div>

                <!-- /content area end -->
              </div>
            </div>
            <!-- /main content end -->

            <div class="clearfix"></div>
        </div>
        <!-- /page content end -->
    </div>
    <!-- /page container end -->

    {include file="\$template_dir/layout/normal/footer.tpl"}
VIEW_TPL;

$edit_template = <<<EDIT_TPL

    <!-- page container begin -->
    <div class="page-container">
        <!-- page content begin -->
        <div class="page-content">
            {include file="\$template_dir/layout/normal/sidebar.tpl"}

            <!-- main content begin -->
            <div class="content-wrapper">
              <div class="main-content">
                <!-- page header begin -->
                <div class="row">
                  <div class="breadcrumb-line">
                    <ul class="breadcrumb">
                      <li><a href="{\$url_base}index.php?go={$appname}.index.index"><i class="icon-home2 position-left"></i>首页</a></li>
                      <li><a href="{\$url_base}index.php?go={$appname}.{$instancename}.lists">{$table_comment}</a></li>
                      <li class="active">编辑{$table_comment}</li>
                    </ul>
                  </div>
                </div>
                <!-- /page header end -->

                <!-- content area begin -->
                <div class="container-fluid edit">
                  <div class="row col-xs-12">
                      <form id="edit{$classname}Form" class="form-horizontal" action="#" method="post" $hasImgFormFlag>
                      {if isset(\$message)}
                      <div class="form-group">
                        <label class="col-sm-2 control-label error-msg">错误信息</label>
                        <div class="col-sm-9 edit-view error-msg">{\$message}</div>
                      </div>
                      {/if}
                      {if \${$instancename}}
                      <div class="form-group">
                        <label class="col-sm-2 control-label">标识</label>
                        <div class="col-sm-9 edit-view">{\${$instancename}.$realId}</div>
                      </div>
                      {/if}
$edit_contents
                      <div class="space-4"></div>
                      <input type="hidden" name="$realId" value="{\${$instancename}.$realId|default:''}"/>
                      <div class="form-actions col-md-12">
                          <button type="submit" class="btn btn-success">确认</button>
                          <div class="btn-group" role="group">
                            <button type="reset" class="btn btn-reset"><i class="fa fa-undo bigger-110"></i>重置</button>
                          </div>
                      </div>
                      </form>
                    </div>
                </div>

                <!-- /content area end -->
              </div>
            </div>
            <!-- /main content end -->

            <div class="clearfix"></div>
        </div>
        <!-- /page content end -->
    </div>
    <!-- /page container end -->

    {include file="\$template_dir/layout/normal/footer.tpl"}
$enumJsContent
$rela_js_content
    <script src="{\$template_url}js/normal/edit.js"></script>
    <script src="{\$template_url}js/core/{$instancename}.js"></script>

$ueTextareacontents
EDIT_TPL;

$edit_sub_json_template = <<<EDIT_JSON
{
    "code": 1,
    "description": "",
    "data": [
$edit_json_enums
    ]
}
EDIT_JSON;

$sidebar_template = <<<SIDEBAR
    <div class="sidebar page-sidebar">
      <div class="sidebar-content">
        <ul class="navigation-header">
          <li ><a href="#"><i class="fa fa-th-list" title="功能导航"></i></a></li>
        </ul>
        <ul class="sidebar-nav">
          <li><a href="{\$url_base}index.php?go=admin.index.index"><i class="fa fa-dashboard"></i> <span>控制台</span></a></li>
$sidebar_menus
        </ul>
      </div>
    </div>
SIDEBAR;

$navbar_template = <<<NAVBAR
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle  collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only"></span>
            <i class="glyphicon glyphicon-briefcase"></i>
          </button>
          <button id="btn-toggle-sidebar" type="button" class="navbar-toggle collapsed">
              <span class="sr-only"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="{\$url_base}index.php?go=admin.index.index">
            <i class="glyphicon glyphicon-grain"></i> {\$site_name}
          </a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="{\$url_base}index.php?go=admin.index.index">首页</a></li>
$navbar_menus
            <li class="dropdown">
              <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="fa fa-user"></span>
                <span class="username">{\$smarty.session.username}</span>
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu" aria-labelledby="dLabel">
                <li><a href="#reset"><span class="glyphicon glyphicon-edit"></span>修改密码</a></li>
                <li><a href="{\$url_base}index.php?go=admin.auth.logout"><span class="glyphicon glyphicon-off"></span>退出</a></li>
              </ul>
            </li>

            <li id="searchbar-li" class="search-toggle collapsed" data-toggle="collapse" data-target="#searchbar" aria-expanded="false" aria-controls="searchbar">
              <a>
                <span><span class="menu-search-text">搜索</span><span class="fa fa-search" aria-hidden="true"></span></span>
              </a>
            </li>
            <li><a id="btn-layout-small"><i class="glyphicon glyphicon-resize-small"></i></a></li>
          </ul>
        </div>
        <div id="searchbar" class="collapse">
          <div id="searchbar-inner">
            <form method="get" action="" class="searchbar-form">
              <input type="search" class="form-control" name="search" autocomplete="off" autofocus="autofocus" placeholder="搜你所想">
              <i id="searchbar-close" class="fa fa-remove search-toggle"></i>
            </form>
          </div>
        </div>
      </div>
    </nav>
NAVBAR;
