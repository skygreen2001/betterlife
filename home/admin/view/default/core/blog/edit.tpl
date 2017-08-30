{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}

    <!-- page container begin -->
    <div class="page-container">
        <!-- page content begin -->
        <div class="page-content">
            <!-- main sidebar begin -->

            <div class="sidebar page-sidebar">
              <div class="sidebar-content">
                <ul class="navigation-header">
                  <li ><a href="#"><i class="icon-th-list" title="功能导航"></i></a></li>
                </ul>
                <ul class="sidebar-nav">
                  <!-- main -->
                  <li><a href="#"><i class="icon-dashboard"></i> <span>控制台</span></a></li>
                  <li data-role="dropdown">
                    <a class="has-ul" href="#collapse-it" aria-expanded="false" aria-controls="collapse-it"><i class="icon-book"></i> <span>科技类</span><i class="glyphicon glyphicon-menu-right menu-right"></i></a>
                    <ul class="sub-menu" id="collapse-it">
                      <li><a href="#">微信</a></li>
                      <li><a href="#">百度</a></li>
                      <li><a href="#">阿里巴巴</a></li>
                      <li><a href="#">华为</a></li>
                      <li><a href="#">vivo</a></li>
                      <li><a href="#">小米</a></li>
                      <li role="separator" class="nav-splitter"></li>
                      <li><a href="#">宇航</a></li>
                      <li><a href="#">移动</a></li>
                      <li><a href="#">交通</a></li>
                    </ul>
                  </li>
                  <li>
                    <a class="has-ul" href="#collapse-philosophy" aria-expanded="false" aria-controls="collapse-philosophy"><i class="icon-user"></i> <span>哲学类</span><i class="glyphicon glyphicon-menu-right menu-right"></i></a>
                    <ul class="sub-menu" id="collapse-philosophy">
                      <li><a href="#">老子</a></li>
                      <li><a href="#">基督</a></li>
                      <li><a href="#">佛教</a></li>
                      <li><a href="#">唯心主义</a></li>
                      <li><a href="#">唯物主义</a></li>
                    </ul>
                  </li>
                  <li data-role="dropdown">
                    <a class="has-ul" href="#collapse-art" aria-expanded="false" aria-controls="collapse-art"><i class="icon-music"></i> <span>艺术类</span><i class="glyphicon glyphicon-menu-right menu-right"></i></a>
                    <ul class="sub-menu" id="collapse-art">
                      <li><a href="#">音乐</a></li>
                      <li><a href="#">电影</a></li>
                      <li><a href="#">文学</a></li>
                      <li><a href="#">舞蹈</a></li>
                      <li><a href="#">绘画</a></li>
                      <li><a href="#">雕塑</a></li>
                    </ul>
                  </li>
                  <li>
                      <a href="#"><i class="icon-life-ring"></i> <span>生活类</span></a>
                  </li>
                  <li>
                      <a href="#"><i class="icon-arrow-circle-o-right"></i> <span>军事类</span></a>
                  </li>
                  <li>
                      <a href="#"><i class="icon-film"></i> <span>综合类</span></a>
                  </li>
                  <li>
                      <a href="#"><i class="icon-opera"></i> <span>其它</span></a>
                  </li>
                </ul>
              </div>
            </div>

            <!-- /main sidebar end -->

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
                                  <input type="text" id="iconImageTxt" name="iconImageTxt" readonly="readonly" class="form-control" />
                                  <span class="btn-file-browser btn-success input-group-addon" id="iconImageDiv">浏览 ...</span>
                                  <input type="file" id="iconImage" name="iconImage" style="display:none;" accept="image/*" />
                              </div>
                          </div>
                      </div>
                      <div class="form-group">
                          <label for="title" class="col-sm-3 control-label">标题</label>
                          <div class="col-sm-9">
                            <div class="clearfix">
                              <input id="title" name="title" placeholder="标题" class="form-control" type="text" value=""/>
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
                          <label for="keyword_id" class="col-sm-3 control-label">关键词</label>
                          <div class="col-sm-9">
                              <select id="keyword_id" name="keyword_id" class="form-control"></select>
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
                          <label for="authorUrl" class="col-sm-3 control-label"></label>
                          <div class="col-sm-9">
                              <div>
                                  <input id="authorUrl" name="authorUrl" placeholder="作者百科链接地址" class="form-control" style="margin-top: -2px;" type="text" value=""/>
                              </div>
                              <div class="input-group col-sm-9" style="padding-top: 3px;">
                                  <input type="text" id="authorImageTxt" name="authorImageTxt" readonly="readonly" class="form-control" />
                                  <span class="btn-file-browser btn-success input-group-addon" id="authorImageDiv">浏览 ...</span>
                                  <input type="file" id="authorImage" name="authorImage" style="display:none;" accept="image/*" />
                              </div>
                          </div>
                      </div>
                      <div class="form-group">
                          <label for="creationTimeStr" class="col-sm-3 control-label">发起日期</label>
                          <div class="col-sm-9">
                              <div class="input-group col-sm-9 datetimeStyle" id="creationTime">
                                  <input id="creationTimeStr" name="creationTime" class="form-control date-picker" type="text" value=""/>
                                  <span class="input-group-addon">
                                      <i class="icon-calendar bigger-110"></i>
                                  </span>
                              </div>
                          </div>
                      </div>
                      <div class="form-group">
                          <label for="deadTimeStr" class="col-sm-3 control-label">结束日期</label>
                          <div class="col-sm-9">
                              <div class="input-group col-sm-9 datetimeStyle" id="deadTime">
                                  <input id="deadTimeStr" name="deadTime" class="form-control date-picker" type="text" value=""/>
                                  <span class="input-group-addon">
                                      <i class="icon-calendar bigger-110"></i>
                                  </span>
                              </div>
                          </div>
                      </div>

                      <div class="space-4"></div>
                      <input id="id" name="id" type="hidden" value=""/>
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
