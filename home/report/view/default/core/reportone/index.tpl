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
                      <li><a href="{$url_base}index.php?go=admin.index.index"><i class="icon-home2 position-left"></i>首页</a></li>
                      <li id="rtitle" class="active">报表标题说明</li>
                    </ul>
                  </div>
                </div>

                <div class="container-fluid list">
                    <div class="row">
                        <div class="form-group filter-report">
                          <label for="startDateStr" class="col-sm-2 control-label">开始时间</label>
                          <div class="input-group col-sm-3 datetimeStyle" id="startDate">
                              <input id="startDateStr" name="startDate" class="form-control date-picker" type="text" value=""/>
                              <span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span>
                          </div>
                          <label for="endDateStr" class="col-sm-2 control-label">结束时间</label>
                          <div class="input-group col-sm-3 datetimeStyle" id="endDate">
                              <input id="endDateStr" name="endDate" class="form-control date-picker" type="text" value=""/>
                              <span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span>
                          </div>
                        </div>

                        <div class="btns-container">
                            <a class="btn btn-default" id="btn-report-export">导出</a>
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

    {include file="$template_dir/layout/normal/footer.tpl"}
    <script src="{$template_url}js/normal/list.js"></script>
    <script src="{$template_url}js/normal/edit.js"></script>
    <script src="{$template_url}js/core/reportone.js"></script>
{/block}
