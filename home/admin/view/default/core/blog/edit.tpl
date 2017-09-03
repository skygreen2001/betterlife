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
                      <li><a href="list.html">读书</a></li>
                      <li class="active">编辑读书</li>
                    </ul>
                  </div>
                </div>
                <!-- /page header end -->

                <!-- content area begin -->
                <div class="container-fluid edit">
                  <div class="row col-xs-12">
                      <form id="editBlogForm" class="form-horizontal" action="#" method="post" enctype="multipart/form-data">
                      <div class="form-group">
                          <label for="iconImage" class="col-sm-3 control-label">封面</label>
                          <div class="col-sm-9">
                              <div class="input-group col-sm-9">
                                  <input type="text" id="iconImageTxt" readonly="readonly" class="form-control" />
                                  <span class="btn-file-browser btn-success input-group-addon" id="iconImageDiv">浏览 ...</span>
                                  <input type="file" id="iconImage" name="icon_url" style="display:none;" accept="image/*" />
                              </div>
                          </div>
                      </div>
                      <div class="form-group">
                          <label for="title" class="col-sm-3 control-label">标题</label>
                          <div class="col-sm-9">
                            <div class="clearfix">
                              <input id="title" name="blog_name" placeholder="标题" class="form-control" type="text" value=""/>
                            </div>
                          </div>
                      </div>
                      <div class="form-group">
                          <label for="sequenceNo" class="col-sm-3 control-label">序号</label>
                          <div class="col-sm-9">
                            <div class="clearfix">
                              <input id="sequenceNo" name="sequenceNo" placeholder="序号" class="form-control" type="number" value=""/>
                            </div>
                          </div>
                      </div>
                      <div class="form-group">
                          <label for="categoryIds" class="col-sm-3 control-label">博客类型</label>
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
                          <label for="content" class="col-sm-3 control-label">内容</label>
                          <div class="col-sm-9">
                            <div class="clearfix">
                              <textarea class="form-control" id="content" name="content" placeholder="内容" rows="6" cols="60"></textarea>
                            </div>
                          </div>
                      </div>
                      <div class="form-group">
                          <label for="isPublic" class="col-sm-3 control-label">公开</label>
                          <div class="col-sm-9">
                              <input class="form-control" id="isPublic" type="checkbox" name="isPublic" data-on-text="是" data-off-text="否" />
                          </div>
                      </div>
                      <div class="form-group">
                          <label for="authorName" class="col-sm-3 control-label">作者</label>
                          <div class="col-sm-9">
                              <div>
                                  <input id="authorName" name="authorName" placeholder="作者" class="form-control" type="text" value=""/>
                              </div>
                          </div>
                      </div>
                      <div class="form-group">
                          <label for="creationTimeStr" class="col-sm-3 control-label">发布日期</label>
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

    {if ($online_editor=='CKEditor')}
        {$editorHtml}
        <script>
        $(function(){
                ckeditor_replace_blog_content();
        });
        </script>
    {/if}
     <div class="block">
        <div><h1>{if $blog}编辑{else}新增{/if}博客</h1><p><font color="red">{$message|default:''}</font></p></div>
        <form name="blogForm" method="post" enctype="multipart/form-data"><input type="hidden" name="blog_id" value="{$blog.blog_id}"/>
        <table class="viewdoblock">
        {if $blog}<tr class="entry"><th class="head">标识</th><td class="content">{$blog.blog_id}</td></tr>{/if}
        <tr class="entry"><th class="head">用户标识</th><td class="content"><input type="text" class="edit" name="user_id" value="{$blog.user_id}"/></td></tr>
        <tr class="entry"><th class="head">博客标题</th><td class="content"><input type="text" class="edit" name="blog_name" value="{$blog.blog_name}"/></td></tr>
        <tr class="entry"><th class="head">博客头像</th><td class="content"><input type="file" class="edit" name="icon_urlUpload" accept="image/png,image/gif,image/jpg,image/jpeg" value="{$blog.icon_url}"/></td></tr>
        <tr class="entry"><th class="head">博客内容</th>
            <td class="content">
                <textarea id="blog_content" name="blog_content" style="width:90%;height:300px;">{$blog.blog_content}</textarea>
            </td>
        </tr>
            <tr class="entry"><td class="content" colspan="2" align="center"><input type="submit" value="提交" class="btnSubmit" /></td></tr>
        </table>
        </form>
        <div class="footer" align="center">
            <my:a href='{$url_base}index.php?go=model.blog.lists&amp;pageNo={$smarty.get.pageNo|default:"1"}'>返回列表</my:a>
            {if $blog}
            |<my:a href='{$url_base}index.php?go=model.blog.view&amp;id={$blog.id}&amp;pageNo={$smarty.get.pageNo|default:"1"}'>查看博客</my:a>
            {/if}
        </div>
    </div>
    {if ($online_editor == 'UEditor')}
        <script>pageInit_ue_blog_content();</script>
    {/if}
{/block}
