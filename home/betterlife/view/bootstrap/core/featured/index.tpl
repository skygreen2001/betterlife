{extends file="$template_dir/layout/normal/layout.tpl"}
{block name=body}

  <div class="page-container featured">
    <div class="page-content">
      <div id="sidebar" class="sidebar page-sidebar"></div>
      <div id="main-content-container" class="content-wrapper">
        <div class="main-content">
          <div class="container-fluid">
            <div class="main-header row">
              <div class="head-img">
                <div class="img" style="background-image: url('{$template_url}resources/images/beauty.jpg')"></div>
              </div>
              <div class="filter-bar">
                <ul>
                  <li id="orderBy" class="recommend dropdown">
                    <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <span>排序方式</span><br>
                      <span class="option" name="zui" value="">最热推荐</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dLabel">
                      <a href="{$url_base}/index.php?go={$app_name}.featured.index" style="text-decoration:none">
                        <li onclick="reverts4()" style="color: #ff224f;background-color: #ffffff">最热推荐</li>
                      </a>
                      <a href="{$url_base}/index.php?go={$app_name}.featured.index" style="text-decoration:none">
                        <li id="zui" class="zu">按人气排序</li>
                      </a>
                    </ul>
                  </li>
                  <li id="cityFilter" class="city dropdown">
                    <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <span>所在城市</span><br>
                      <span class="option" name="city" value="">任何城市</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dLabel">
                      <a href="{$url_base}/index.php?go={$app_name}.featured.index" style="text-decoration:none">
                        <li onclick="reverts()" style="color: #ff224f;background-color: #ffffff">任何城市</li>
                      </a>
                      <a href="{$url_base}/index.php?go={$app_name}.featured.index" style="text-decoration:none">
                        <li id="city_2" class="ci">北京</li>
                      </a>
                      <a href="{$url_base}/index.php?go={$app_name}.featured.index" style="text-decoration:none">
                        <li id="city_3" class="ci">安徽</li>
                      </a>
                      <a href="{$url_base}/index.php?go={$app_name}.featured.index" style="text-decoration:none">
                        <li id="city_16" class="ci">江苏</li>
                      </a>
                      <a href="{$url_base}/index.php?go={$app_name}.featured.index" style="text-decoration:none">
                        <li id="city_17" class="ci">江西</li>
                      </a>
                      <a href="{$url_base}/index.php?go={$app_name}.featured.index" style="text-decoration:none">
                        <li id="city_25" class="ci">上海</li>
                      </a>
                      <a href="{$url_base}/index.php?go={$app_name}.featured.index" style="text-decoration:none">
                        <li id="city_31" class="ci">浙江</li>
                      </a>
                    </ul>
                  </li>
                  <li id="typeFilter" class="cate dropdown">
                    <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <span>分门别类</span><br>
                      <span class="option" name="cate" value="">任何类别</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dLabel">
                      <a href="{$url_base}/index.php?go={$app_name}.featured.index" style="text-decoration:none">
                        <li style="color: #ff224f;background-color: #ffffff">任何类别</li>
                      </a>
                      <a href="{$url_base}/index.php?go={$app_name}.featured.index" style="text-decoration: none">
                        <li id="cate_19" class="ca">闯关寻宝</li>
                      </a>
                      <a href="{$url_base}/index.php?go={$app_name}.featured.index" style="text-decoration: none">
                        <li id="cate_12" class="ca">探险之旅</li>
                      </a>
                      <a href="{$url_base}/index.php?go={$app_name}.featured.index" style="text-decoration: none">
                        <li id="cate_13" class="ca">玄幻奇缘</li>
                      </a>
                      <a href="{$url_base}/index.php?go={$app_name}.featured.index" style="text-decoration: none">
                        <li id="cate_14" class="ca">历史武侠</li>
                      </a>
                      <a href="{$url_base}/index.php?go={$app_name}.featured.index" style="text-decoration: none">
                        <li id="cate_15" class="ca">自然之谜</li>
                      </a>
                      <a href="{$url_base}/index.php?go={$app_name}.featured.index" style="text-decoration: none">
                        <li id="cate_16" class="ca">都市故事</li>
                      </a>
                      <a href="{$url_base}/index.php?go={$app_name}.featured.index" style="text-decoration: none">
                        <li id="cate_17" class="ca">文艺街巷</li>
                      </a>
                      <a href="{$url_base}/index.php?go={$app_name}.featured.index" style="text-decoration: none">
                        <li id="cate_20" class="ca">名人轶事</li>
                      </a>
                    </ul>
                  </li>
                  <li id="renqunFilter" class="renqun dropdown last">
                    <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <span>适合人群</span><br>
                      <span class="option" name="renshu" value="">任何人群</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dLabel">
                      <a href="index.html" style="text-decoration:none">
                        <li onclick="reverts3()" style="color: #ff224f;background-color: #ffffff">适合人群</li>
                      </a>
                      <a style="text-decoration: none" href="{$url_base}/index.php?go={$app_name}.featured.index">
                        <li id="ren_23" class="re">中国人</li>
                      </a>
                      <a style="text-decoration: none" href="{$url_base}/index.php?go={$app_name}.featured.index">
                        <li id="ren_24" class="re">美国人</li>
                      </a>
                      <a style="text-decoration: none" href="{$url_base}/index.php?go={$app_name}.featured.index">
                        <li id="ren_25" class="re">职业人群</li>
                      </a>
                      <a style="text-decoration: none" href="{$url_base}/index.php?go={$app_name}.featured.index">
                        <li id="ren_26" class="re">娱乐大众</li>
                      </a>
                      <a style="text-decoration: none" href="{$url_base}/index.php?go={$app_name}.featured.index">
                        <li id="ren_28" class="re">文化人</li>
                      </a>
                    </ul>
                  </li>
                  <form method="post">
                    <input id="searchtrip" type="text" value="" name="search" placeholder="搜你所想">
                    <button type="submit">
                      <i class="fa fa-search fa-2x" aria-hidden="true"></i>
                    </button>
                  </form>
                </ul>
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

      <div class="clearfix"></div>
    </div>
  </div>

  {include file="$template_dir/layout/normal/footer.tpl"}
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
  <script src="{$template_url}js/core/featured.js"></script>
{/block}
