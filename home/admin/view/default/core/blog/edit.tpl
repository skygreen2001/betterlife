{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
    <!-- page container begin -->
    <div class="page-container">
        <!-- page content begin -->
        <div class="page-content">
            {include file="$templateDir/layout/normal/sidebar.tpl"}

            <!-- main content begin -->
            <div class="content-wrapper">
              <div class="main-content">
                <!-- page header begin -->
                <div class="row">
                  <div class="breadcrumb-line">
                    <ul class="breadcrumb">
                      <li><a href="/"><i class="icon-home2 position-left"></i> 首页</a></li>
                      <li><a href="{$url_base}index.php?go=admin.blog.lists">博客</a></li>
                      <li class="active">编辑博客</li>
                    </ul>
                  </div>
                </div>
                <!-- /page header end -->

                <!-- content area begin -->
                <div class="container-fluid edit">
                  <div class="row col-xs-12">
                      <form id="editBlogForm" class="form-horizontal" action="#" method="post" enctype="multipart/form-data">
                      {if $message}
                      <div class="form-group">
                        <label class="col-sm-2 control-label error-msg">错误信息</label>
                        <div class="col-sm-9 edit-view error-msg">{$message}</div>
                      </div>
                      {/if}
                      {if $blog}
                      <div class="form-group">
                        <label class="col-sm-2 control-label">标识</label>
                        <div class="col-sm-9 edit-view">{$blog.blog_id}</div>
                      </div>
                      {/if}
                      <div class="form-group">
                          <label for="iconImage" class="col-sm-2 control-label">封面</label>
                          <div class="col-sm-9">
                              <div class="input-group col-sm-9">
                                  <input type="text" id="iconImageTxt" readonly="readonly" class="form-control" />
                                  <span class="btn-file-browser btn-success input-group-addon" id="iconImageDiv">浏览 ...</span>
                                  <input type="file" id="iconImage" name="icon_url" style="display:none;" accept="image/*" />
                              </div>
                          </div>
                      </div>
                      <div class="form-group">
                          <label for="title" class="col-sm-2 control-label">标题</label>
                          <div class="col-sm-9">
                            <div class="clearfix">
                              <input id="title" name="blog_name" placeholder="标题" class="form-control" type="text" value="{$blog.blog_name}"/>
                            </div>
                          </div>
                      </div>
                      <div class="form-group">
                          <label for="sequenceNo" class="col-sm-2 control-label">序号</label>
                          <div class="col-sm-9">
                            <div class="clearfix">
                              <input id="sequenceNo" name="sequenceNo" placeholder="序号" class="form-control" type="number" value="{$blog.sequenceNo}"/>
                            </div>
                          </div>
                      </div>
                      <div class="form-group">
                          <label for="categoryIds" class="col-sm-2 control-label">博客类型</label>
                          <div class="col-sm-9">
                              <select id="categoryIds" name="categoryId" class="form-control" multiple>
                                  <option value="1">科技类</option>
                                  <option value="2" selected>时尚类</option>
                                  <option value="3">新闻类</option>
                                  <option value="4">体育类</option>
                                  <option value="5">军事类</option>
                                  <option value="6">生活类</option>
                              </select>
                          </div>
                      </div>
                      <div class="form-group">
                          <label for="blog_content" class="col-sm-2 control-label">内容</label>
                          <div class="col-sm-9">
                            <div class="clearfix">
                              <textarea class="form-control" id="blog_content" name="blog_content" rows="6" cols="60" placeholder="内容">{$blog.blog_content}</textarea>
                            </div>
                          </div>
                      </div>
                      <div class="form-group">
                          <label for="isPublic" class="col-sm-2 control-label">公开</label>
                          <div class="col-sm-9">
                              <input class="form-control" id="isPublic" type="checkbox" name="isPublic" {if $blog.isPublic} checked {/if} data-on-text="是" data-off-text="否" />
                          </div>
                      </div>
                      <div class="form-group">
                          <label for="creationTimeStr" class="col-sm-2 control-label">发布日期</label>
                          <div class="col-sm-9">
                              <div class="input-group col-sm-9 datetimeStyle" id="creationTime">
                                  <input id="creationTimeStr" name="updateTime" class="form-control date-picker" type="text" value=""/>
                                  <span class="input-group-addon">
                                      <i class="icon-calendar bigger-110"></i>
                                  </span>
                              </div>
                          </div>
                      </div>

                      <div class="space-4"></div>
                      <input type="hidden" name="blog_id" value="{$blog.blog_id}"/>
                      <div class="form-actions col-md-12">
                          <button type="submit" class="btn btn-success">确认</button>
                          <div  class="btn-group" role="group">
                            <button class="btn" type="reset"><i class="icon-undo bigger-110"></i>重置</button>
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

    {include file="$templateDir/layout/normal/footer.tpl"}

    <script src="{$template_url}js/normal/edit.js"></script>
    <script src="{$template_url}js/betterlife/blog.js"></script>
    {if ($online_editor == 'UEditor')}
        <script>pageInit_ue_blog_content();</script>
    {/if}
{/block}
