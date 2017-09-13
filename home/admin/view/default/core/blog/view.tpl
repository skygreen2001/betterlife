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
                      <li class="active">查看博客</li>
                    </ul>
                  </div>
                </div>
                <!-- /page header end -->

                <!-- content area begin -->
                <div class="container-fluid view">
                  <div class="row col-xs-12">
                    <h2>博客详情</h2><hr>
                    <h4>
                      <span class="glyphicon glyphicon-list-alt"></span>
                      <span>基本信息</span>
                    </h4><hr>
                    <dl>
                      <dt><span>标识</span></dt>
                      <dd>
                        <span>{$blog.blog_id}</span>
                      </dd>
                    </dl>
                    <dl>
                      <dt><span>序号</span></dt>
                      <dd>
                        <span>{$blog.sequenceNo}</span>
                      </dd>
                    </dl>
                    <dl>
                      <dt><span>名称</span></dt>
                      <dd>
                        <span>{$blog.blog_name}</span>
                      </dd>
                    </dl>
                    <dl>
                      <dt><span>用户</span></dt>
                      <dd>
                        <span>{$blog.username}</span>
                      </dd>
                    </dl>
                    <dl>
                      <dt><span>封面</span></dt>
                      <dd>
                        {if $blog.icon_url}
                        <span><img class="img-thumbnail" src="{$blog.icon_url}" alt="{$blog.blog_name}" /></span>
                        <span>存储相对路径:{$blog.icon_url}</span>
                        {else}
                        <span><img class="img-thumbnail" src="https://lorempixel.com/900/500?r=1" alt="{$blog.blog_name}" /></span>
                        {/if}
                      </dd>
                    </dl>
                    <dl>
                      <dt><span>状态</span></dt>
                      <dd>
                        <span>{$blog.statusShow}</span>
                      </dd>
                    </dl>
                    <dl>
                      <dt><span>博客内容</span></dt>
                      <dd>
                        <span>{$blog.blog_content}</span>
                      </dd>
                    </dl>
                    <button type="submit" onclick="javascript: history.go(-1);" class="btn btn-info">
                      <span class="glyphicon glyphicon-arrow-left"></span>&nbsp;<span>返回</span>
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

    {include file="$templateDir/layout/normal/footer.tpl"}
{/block}
