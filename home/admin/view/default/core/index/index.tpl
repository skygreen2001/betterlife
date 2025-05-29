{extends file="$template_dir/layout/normal/layout.tpl"}
{block name=body}

    <div class="page-container">
        <div class="page-content">
            {include file="$template_dir/layout/normal/sidebar.tpl"}
            <div class="content-wrapper">
                <div class="main-content">
                    <!-- page header begin -->
                    <div class="row">
                      <div class="breadcrumb-line">
                        <ul class="breadcrumb">
                          <li><a href="/"><i class="icon-home2 position-left"></i> 首页</a></li>
                          <li class="active">控制台</li>
                        </ul>
                      </div>
                    </div>
                    <!-- /page header end -->

                    <div class="container-fluid home">
                      <div class="row col-xs-12">
                        <section class="section container-fluid">
                          <h1 class="page-header">{$site_name}</h1>
                          <h2>👌 后台管理，责无旁贷</h2>
                          <a href="https://gitee.com/skygreen2015/betterlife" target="_blank">
                            <div class="load-more col-xs-12 col-sm-12 col-md-12 col-lg-12">
                              <h3 class="see-more">
                                <span class="plus"><div class="plus"></div></span>
                                <span class="text">进一步了解 ></span>
                              </h3>
                            </div>
                          </a>
                        </section>
                      </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    {include file="$template_dir/layout/normal/footer.tpl"}

    <script src="{$template_url}js/index.js"></script>
{/block}
