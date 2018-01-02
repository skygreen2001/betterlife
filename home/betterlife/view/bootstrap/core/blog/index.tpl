{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
<body>
    <div class="page-container">
      <div class="page-content">
        <div id="main-content-container" class="content-wrapper">
          <div class="main-content">
            <div class="container-fluid">
            Hello

            </div>
          </div>
        </div>

        <div class="clearfix"></div>
      </div>
    </div>

    {include file="$templateDir/layout/normal/footer.tpl"}
    <script src="{$template_url}js/core/blog.js"></script>
</body>
{/block}
