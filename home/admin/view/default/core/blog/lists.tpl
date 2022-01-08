{extends file="$template_dir/layout/normal/layout.tpl"}
{block name=body}
    <div class="page-container">
        <div class="page-content">
            {include file="$template_dir/layout/normal/sidebar.tpl"}
            <div class="content-wrapper list-wrapper">
              <div class="main-content">
                <div class="row">
                  <div class="breadcrumb-line">
                    <ul class="breadcrumb">
                      <li><a href="{$url_base}index.php?go=admin.index.index"><i class="icon-home2 position-left"></i> 首页</a></li>
                      <li class="active">博客</li>
                    </ul>
                  </div>
                </div>

                <div class="container-fluid list">
                    <div class="row">
                        <a class="btn btn-success" href="{$url_base}index.php?go=admin.blog.edit">新增博客</a>
                        <div class="btns-container">
                            <a class="btn btn-default" id="btn-blog-import">导入</a>
                            <a class="btn btn-default" id="btn-blog-export">导出</a>
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
                                    <th>标题</th>
                                    <th>作者</th>
                                    <th>封面</th>
                                    <th>是否公开</th>
                                    <th>状态</th>
                                    <th>发布时间</th>
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

    {include file="$template_dir/layout/normal/footer.tpl"}
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
    {literal}
    <script id="actionTmpl" type="text/x-jsrender">
    <a id="info-view{{:id}}" href="#" class="btn-view">查看</a>
    <a id="info-edit{{:id}}" href="#" class="btn-edit">修改</a>
    <a id="info-dele{{:id}}" href="#" class="btn-dele" data-toggle="modal" data-target="#infoModal">删除</a>
    </script>
    {/literal}
    <script src="{$template_url}js/normal/list.js"></script>
    <script src="{$template_url}js/core/blog.js"></script>
{/block}
