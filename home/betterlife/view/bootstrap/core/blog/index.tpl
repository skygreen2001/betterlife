{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}

  <div class="page-container blog">
    <div class="page-content">
      <div id="main-content-container" class="content-wrapper">
        <div class="main-content">
          <div class="container-fluid">
            <div class="main-header row">
              <div class="head-img">
                <img src="https://lorempixel.com/900/500?r=1" alt="美图">
              </div>
              <div class="filter-bar">
                <ul>
                  <li id="orderBy" class="recommend dropdown">
                    <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <span>排序方式</span><br>
                      <span class="option" name="zui" value="">最热推荐</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dLabel">
                      <a href="index.html" style="text-decoration:none">
                        <li onclick="reverts4()" style="color: #ff224f;background-color: #ffffff">最热推荐</li>
                      </a>
                      <a href="index.html&amp;zui=renqi" style="text-decoration:none">
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
                      <a href="index.html" style="text-decoration:none">
                        <li onclick="reverts()" style="color: #ff224f;background-color: #ffffff">任何城市</li>
                      </a>
                      <a href="index.html&amp;city=2" style="text-decoration:none">
                        <li id="city_2" class="ci">北京</li>
                      </a>
                      <a href="index.html&amp;city=3" style="text-decoration:none">
                        <li id="city_3" class="ci">安徽</li>
                      </a>
                      <a href="index.html&amp;city=16" style="text-decoration:none">
                        <li id="city_16" class="ci">江苏</li>
                      </a>
                      <a href="index.html&amp;city=17" style="text-decoration:none">
                        <li id="city_17" class="ci">江西</li>
                      </a>
                      <a href="index.html&amp;city=25" style="text-decoration:none">
                        <li id="city_25" class="ci">上海</li>
                      </a>
                      <a href="index.html&amp;city=31" style="text-decoration:none">
                        <li id="city_31" class="ci">浙江</li>
                      </a>
                    </ul>
                  </li>
                  <li id="typeFilter" class="cate dropdown">
                    <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <span>博客类别</span><br>
                      <span class="option" name="cate" value="">任何类别</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dLabel">
                      <a href="index.html" style="text-decoration:none">
                        <li onclick="reverts2()" style="color: #ff224f;background-color: #ffffff">任何类别</li>
                      </a>
                      <a href="index.html&amp;cate=19" style="text-decoration: none">
                        <li id="cate_19" class="ca">闯关寻宝</li>
                      </a>
                      <a href="index.html&amp;cate=12" style="text-decoration: none">
                        <li id="cate_12" class="ca">探险之旅</li>
                      </a>
                      <a href="index.html&amp;cate=13" style="text-decoration: none">
                        <li id="cate_13" class="ca">玄幻奇缘</li>
                      </a>
                      <a href="index.html&amp;cate=14" style="text-decoration: none">
                        <li id="cate_14" class="ca">历史武侠</li>
                      </a>
                      <a href="index.html&amp;cate=15" style="text-decoration: none">
                        <li id="cate_15" class="ca">自然之谜</li>
                      </a>
                      <a href="index.html&amp;cate=16" style="text-decoration: none">
                        <li id="cate_16" class="ca">都市故事</li>
                      </a>
                      <a href="index.html&amp;cate=17" style="text-decoration: none">
                        <li id="cate_17" class="ca">文艺街巷</li>
                      </a>
                      <a href="index.html&amp;cate=20" style="text-decoration: none">
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
                      <a style="text-decoration: none" href="index.html&amp;ren=23">
                        <li id="ren_23" class="re">0~100人</li>
                      </a>
                      <a style="text-decoration: none" href="index.html&amp;ren=24">
                        <li id="ren_24" class="re">100~200人</li>
                      </a>
                      <a style="text-decoration: none" href="index.html&amp;ren=25">
                        <li id="ren_25" class="re">200~300人</li>
                      </a>
                      <a style="text-decoration: none" href="index.html&amp;ren=26">
                        <li id="ren_26" class="re">300~500人</li>
                      </a>
                      <a style="text-decoration: none" href="index.html&amp;ren=28">
                        <li id="ren_28" class="re">不限人数</li>
                      </a>
                    </ul>
                  </li>
                  <form method="post">
                    <input id="searchtrip" type="text" value="" name="search" placeholder="搜你所想">
                    <button type="submit">
                      <i class="fa fa-search fa-2x" aria-hidden="true"></i>
                      <!-- <img src="http://www.itasktour.com/home/ittrweb/view/default//resources/images/invalid-name@2x.png"> -->
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

  {include file="$templateDir/layout/normal/footer.tpl"}

  {literal}
  <!-- template begin -->
  <script id="unitTmpl" type="text/x-jsrender">
    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
      <div class="unit-box">
        <div class="img-box">
          <img src="{{:img_src}}" alt="{{:title}}">
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
