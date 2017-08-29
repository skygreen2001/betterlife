{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}

    <div class="page-container">
        <div class="page-content">
            {include file="$templateDir/layout/normal/sidebar.tpl"}
            <div class="content-wrapper">
              <div class="main-content">
                <div class="row">
                  <div class="breadcrumb-line">
                    <ul class="breadcrumb">
                      <li><a href="/"><i class="icon-home2 position-left"></i> 首页</a></li>
                      <li class="active">读书</li>
                    </ul>
                  </div>
                </div>

                <div class="container-fluid list">
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

    {include file="$templateDir/layout/normal/footer.tpl"}
    <script type="text/javascript" src="{$template_url}js/normal/layout.js"></script>


    <div id="image-model"></div>
    <script id="imgModalTmpl" type="text/x-jsrender">
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">X</span></button>
          </div>
          <div class="modal-body">
            <a id="imagePreview-link" href="#" target="_blank"><img src="" id="imagePreview" style="width: 100%; height: 100%;" ></a>
          </div>
        </div>
      </div>
    </div>
    </script>
    {literal}
    <script id="imgTmpl" type="text/x-jsrender">
    <a id="{{:img_id}}" href="#"><img src="{{:img_src}}" class="img-thumbnail" alt="{{:img_name}}" /></a>
    </script>
    <script id="actionTmpl" type="text/x-jsrender">
    <a id="info-view{{:id}}" href="#" class="btn-view" data-toggle="modal" data-target="#infoModal">查看</a>
    <a id="info-edit{{:id}}" href="edit.html" class="btn-edit">修改</a>
    <a id="info-dele{{:id}}" href="#" class="btn-dele" data-toggle="modal" data-target="#infoModal">删除</a>
    </script>
    {/literal}
    <script src="{$template_url}js/normal/list.js"></script>
    <script src="{$template_url}js/betterlife/blog.js"></script>


<div class="block">
    <div><h1>博客列表(共计{$countBlogs}个)</h1></div>
    <table class="viewdoblock">
        <tr class="entry">
            <th class="header">标识</th>
            <th class="header">用户</th>
            <th class="header">博客标题</th>
            <th class="header">博客内容</th>
            <th class="header">操作</th>
        </tr>
        {foreach item=blog from=$blogs}
        <tr class="entry">
            <td class="content">{$blog.blog_id}</td>
            <td class="content">{$blog.username}</td>
            <td class="content">{$blog.blog_name}</td>
            <td class="content">{$blog.blog_content}</td>
            <td class="btnCol"><my:a href="{$url_base}index.php?go=model.blog.view&amp;id={$blog.blog_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}">查看</my:a>|<my:a href="{$url_base}index.php?go=model.blog.edit&amp;id={$blog.blog_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}">修改</my:a>|<my:a href="{$url_base}index.php?go=model.blog.delete&amp;id={$blog.blog_id}&amp;pageNo={$smarty.get.pageNo|default:"1"}">删除</my:a></td>
        </tr>
        {/foreach}
    </table>

    <div class="footer" align="center">
        <div><my:page src='{$url_base}index.php?go=model.blog.lists' /></div>
        <my:a href='{$url_base}index.php?go=model.blog.edit&amp;pageNo={$smarty.get.pageNo|default:"1"}'>新建</my:a>|<my:a href='{$url_base}index.php?go=model.index.index'>返回首页</my:a>
    </div>
</div>
{/block}
