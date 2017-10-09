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
                      <li><a href="{$url_base}index.php?go=admin.index.index"><i class="icon-home2 position-left"></i> 首页</a></li>
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
                      <dd><span>{$blog.blog_id}</span></dd>
                    </dl>
                    <dl>
                      <dt><span>序号</span></dt>
                      <dd><span>{$blog.sequenceNo}</span></dd>
                    </dl>
                    <dl>
                      <dt><span>分类</span></dt>
                      <dd><span>{$blog.category.name}</span></dd>
                    </dl>
                    <dl>
                      <dt><span>名称</span></dt>
                      <dd><span>{$blog.blog_name}</span></dd>
                    </dl>
                    <dl>
                      <dt><span>标签</span></dt>
                      <dd>{foreach item=tags from=$blog.tagss}<span>{$tags.title}</span> {/foreach}</dd>
                    </dl>
                    <dl>
                      <dt><span>作者</span></dt>
                      <dd><span>{$blog.user.username}</span></dd>
                    </dl>
                    <dl>
                      <dt><span>封面</span></dt>
                      <dd>
                        {if $blog.icon_url}
                        <span><a href="{$blog.icon_url}" target="_blank"><img class="img-thumbnail" src="{$blog.icon_url}" alt="{$blog.blog_name}" /></a></span><br>
                        <span>存储路径:</span><br><span>{$blog.icon_url}</span>
                        {else}
                        <span><a href="https://lorempixel.com/900/500?r=1" target="_blank"><img class="img-thumbnail" src="https://lorempixel.com/900/500?r=1" alt="{$blog.blog_name}" /></a></span>
                        {/if}
                      </dd>
                    </dl>
                    <dl>
                      <dt><span>是否公开</span></dt>
                      <dd><span>{$blog.isPublicShow}</span></dd>
                    </dl>
                    <dl>
                      <dt><span>状态</span></dt>
                      <dd><span>{$blog.statusShow}</span></dd>
                    </dl>
                    <dl>
                      <dt><span>博客内容</span></dt>
                      <dd><span>{$blog.blog_content}</span></dd>
                    </dl>
                    <dl>
                      <dt><span>发布日期</span></dt>
                      <dd><span>{$blog.publish_date|date_format:"%Y-%m-%d"}</span></dd>
                    </dl>

                    <h4>
                      <span class="glyphicon glyphicon-list-alt"></span>
                      <span>其他信息</span>
                    </h4><hr>
                    <dl>
                      <dt><span>标识</span></dt>
                      <dd><span>{$blog.blog_id}</span></dd>
                    </dl>
                    <dl>
                      <dt><span>创建时间</span></dt>
                      <dd><span>{$blog.commitTime|date_format:"%Y-%m-%d %H:%M"}</span></dd>
                    </dl><dl>
                      <dt><span>更新时间</span></dt>
                      <dd><span>{$blog.updateTime|date_format:"%Y-%m-%d %H:%M"}</span></dd>
                    </dl>
                    <button type="submit" onclick="location.href='{$url_base}index.php?go=admin.blog.lists&amp;pageNo={$smarty.get.pageNo|default:1}'" class="btn btn-info">
                      <span class="glyphicon glyphicon-arrow-left"></span>&nbsp;<span>返回</span>
                    </button>
                    <button type="submit" onclick="location.href='{$url_base}index.php?go=admin.blog.edit&amp;id={$smarty.get.id}&amp;pageNo={$smarty.get.pageNo|default:1}'" class="btn btn-info">
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

    {include file="$templateDir/layout/normal/footer.tpl"}
{/block}
