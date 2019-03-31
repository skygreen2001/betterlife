{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}

    <div class="page-container">
        <div class="page-content">
            {include file="$templateDir/layout/normal/sidebar.tpl"}
            <div class="content-wrapper list-wrapper">
              <div class="main-content">
                <div class="row">
                  <div class="breadcrumb-line">
                    <ul class="breadcrumb">
                      <li><a href="{$url_base}index.php?go=admin.index.index"><i class="icon-home2 position-left"></i>首页</a></li>
                      <li id="rtitle" class="active">报表标题说明</li>
                    </ul>
                  </div>
                </div>

                <div class="container-fluid list">
                    <div class="row">
                        <div class="btns-container">
                            <a class="btn btn-default" id="btn-report-export">导出</a>
                            <input id="upload_file" name="upload_file" type="file" style="display:none;" accept=".xlsx, .xls" />
                        </div>
                    </div><br/>
                    <div class="row table-responsive col-xs-12">
                        <table id="infoTable" class="display nowrap dataTable table table-striped table-bordered">
                            <thead>
                                <tr id="rhead">
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
              </div>
            </div>
        </div>
    </div>

    {include file="$templateDir/layout/normal/footer.tpl"}
    <script src="{$template_url}js/normal/list.js"></script>
    <script src="{$template_url}js/core/reportone.js"></script>
{/block}
