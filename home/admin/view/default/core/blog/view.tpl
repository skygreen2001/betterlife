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
                      <li><a href="{$url_base}index.php?go=admin.blog.lists">读书</a></li>
                      <li class="active">编辑读书</li>
                    </ul>
                  </div>
                </div>
                <!-- /page header end -->

                <!-- content area begin -->
                <div class="container-fluid edit">
                  <div class="row col-xs-12">

<div class="block">
    <div><h1>查看博客</h1></div>
    <table class="viewdoblock">
        <tr class="entry"><th class="head">标识</th><td class="content">{$blog.blog_id}</td></tr>
        <tr class="entry"><th class="head">用户</th><td class="content">{$blog.username}</td></tr>
        <tr class="entry"><th class="head">用户标识</th><td class="content">{$blog.user_id}</td></tr>
        <tr class="entry"><th class="head">博客标题</th><td class="content">{$blog.blog_name}</td></tr>
        <tr class="entry"><th class="head">博客头像</th><td class="content">
            <div class="wrap_2_inner"><img src="{$uploadImg_url|cat:$blog.icon_url}" alt="博客头像"></div>
            <br/>存储相对路径:{$blog.icon_url}</td></tr>
        <tr class="entry"><th class="head">博客内容</th><td class="content">{$blog.blog_content}</td></tr>
    </table>
</div>

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

    <script src="{$template_url}js/normal/view.js"></script>
{/block}
