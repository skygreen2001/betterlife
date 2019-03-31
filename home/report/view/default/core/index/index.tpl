{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}

    <div class="page-container">
        <div class="page-content">
            {include file="$templateDir/layout/normal/sidebar.tpl"}
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
                          <h2>👌 报表管理，责无旁贷</h2>
                          <h3><a href="https://github.com/skygreen2001/betterlife.core" target="_blank">进一步了解 > </a></h3>
                        </section>
                      </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    {include file="$templateDir/layout/normal/footer.tpl"}

{/block}
