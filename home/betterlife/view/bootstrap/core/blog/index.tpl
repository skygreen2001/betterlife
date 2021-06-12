{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}

  <div class="page-container blog">
    <div class="page-content">
      <div id="sidebar" class="sidebar page-sidebar"></div>
      <div id="main-content-container" class="content-wrapper">
        <div class="main-content">
          <div class="container-fluid">
            <div class="main-header" style="background-image: url('{$template_url}resources/images/beauty.jpg')">
              <div class="head-container">
                <h1 class="header-title">展现自我 &amp; 好奇发现<br>Creative Work</h1>
              </div>
            </div>

            <div class="content-container">
              <div id="top-panel">
                <div id="top-menus" class="top-menus" style="height: 60px;">
                  <div class="menu-navs">
                    <ul class="menu-nav-container" style="position: relative;">
                      <li class="menu-nav active">
                        <a class="menu-nav-link" href="{$url_base}/index.php?go={$appName}.blog.index"> 热点(火炎焱燚) </a>
                      </li>
                      <li class="menu-nav">
                        <a class="menu-nav-link" href="{$url_base}/index.php?go={$appName}.blog.index"> 电影 </a>
                      </li>
                      <li class="menu-nav">
                        <a class="menu-nav-link" href="{$url_base}/index.php?go={$appName}.blog.index"> 音乐(口吅品㗊) </a>
                      </li>
                      <li class="menu-nav">
                        <a class="menu-nav-link" href="{$url_base}/index.php?go={$appName}.blog.index"> 科技 </a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="unit-list row">
              </div>
              <div class="btn-load-more row">
                <button type="button" name="button">加载更多</button>
              </div>
            </div>
          </div>
        </div>
      </div> 
      <div class="clearfix"></div>
    </div>
  </div>

  {include file="$templateDir/layout/normal/footer.tpl"}
  <script type="text/javascript">
    var template_url = "{$template_url}";
  </script>

  {literal}
  <!-- template begin -->
  <script id="unitTmpl" type="text/x-jsrender">
    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
      <div class="unit-box">
        <div class="img-box">
          <img src="{{:template_url}}resources/images/beauty.jpg" onload="this.src='{{:img_src}}';" onerror="this.src='{{:template_url}}resources/images/beauty.jpg';" alt="{{:title}}">
        </div>
        <div class="title-box">
          <h2>{{:title}}</h2>
          <p>{{:category}}</p>
        </div>
        <div class="suffix-box">
          <ul>
            <li>{{:author}}</li>
            <li>{{:publishTime}}</li>
            <li><i class="fa fa-eye"></i>{{:countSee}}</li>
          </ul>
        </div>
      </div>
    </div>
  </script>
  <!-- /template end -->
  {/literal}
  <script src="{$template_url}js/core/blog.js"></script>
{/block}
