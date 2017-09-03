{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}

    <div class="page-container">
        <div class="page-content">
            {include file="$templateDir/layout/normal/sidebar.tpl"}
            <div class="content-wrapper">
              <div class="main-content">

                <div class="container-fluid list">
                  后台管理主页面
                </div>
              </div>
            </div>

            <div class="clearfix"></div>
        </div>
    </div>

    {include file="$templateDir/layout/normal/footer.tpl"}
    <script type="text/javascript" src="{$template_url}js/normal/layout.js"></script>
{/block}
