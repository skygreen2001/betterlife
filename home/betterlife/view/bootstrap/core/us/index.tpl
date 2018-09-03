{extends file="$templateDir/layout/normal/layout.tpl"}
{block name=body}
  <style type="text/css">
  .us .intro .cover {
      background: url(home/betterlife/view/bootstrap/resources/images/overlay.png) repeat scroll 0 0 rgba(0, 0, 0, 0);
  }

  .us .intro .intro-page {
      background: url(home/betterlife/view/bootstrap/resources/images/beauty.jpg) center no-repeat #313131;
  }
  </style>
  <div class="us">
    <div id="main-content-container" class="container-fluid">
        <!-- Banner content -->
        <section class="section section-header">
            <div class="parallax pattern-image">
                <img src="{$template_url}resources/images/beauty.jpg">
                <!-- <img src="https://lorempixel.com/1800/1000?r=1" /> -->
                <div class="container">
                    <div class="content">
                        <h1>Creative Tim</h1>
                        <div class="separator-container">
                            <div class="separator line-separator">∎</div>
                        </div>
                        <h5>Awesome Bootstrap freebies and templates to build better websites</h5>
                        <a href="" data-scroll="true" data-id="#whoWeAre" class="scroll-arrow">
                          <i class="fa fa-angle-down"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Intro top content -->
        <section class="intro" id="home">
            <div class="cover"></div>
            <div class="intro-body intro-page">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2 animated text-slide">
                            <div id="intro-slider" class="flexslider">
                                <ul class="slides">
                                    <li>
                                        <h3 class="brand-heading">We will design your future</h3>

                                        <div class="line"></div>
                                        <p class="intro-text yah">
                                            爱现场lovelive以“现场文化”为核心，致力于帮助热爱生活、热爱音乐的人群发现好的现场，发现兴趣相投的朋友。</p>
                                    </li>
                                    <li>
                                        <h3 class="brand-heading">WE CREATE Digital Experience</h3>

                                        <div class="line"></div>
                                        <p class="intro-text yah">通过产品创新和技术创新，让用户随时随地找到好玩的活动，认识更多与自己兴趣爱好一致的人。</p>
                                    </li>
                                    <li>
                                        <h3 class="brand-heading">We will design your ideas</h3>

                                        <div class="line"></div>
                                        <p class="intro-text yah">
                                            产品覆盖上海各大活动场馆，并逐渐扩大到全国范围，在让用户获取精准信息的基础上，逐步发展互动社交等功能，让用户来到现场，爱上现场，找到爱。</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Intro top content -->

        <!-- About content -->
        <section class="about-content" id="about">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 welcome-text animated">
                        <h3>关于我们</h3>

                        <div class="line1"></div>
                        <p style="text-align:left;">
                            <span class="yahei">爱现场LOVELIVE是爱嗨文化传播（上海）有限公司旗下以“青年文化推广”为核心的泛娱乐IP运营和媒体资讯服务品牌。
                            <br>品牌成立于2014年12月，长期和大型品牌、各大国际音乐演出厂牌保持深度合作关系，拥有强大的娱乐策划营销能力。
                            <br>目前覆盖上海地区超过30万现场娱乐用户，并逐渐扩大到全国范围，在让用户获取精准信息的基础上，打造青年人最喜爱的IP泛娱乐内容，让用户参与现场，爱上现场。
                        </span>
                            <!-- <a class="btn1" href="#">Read More</a> -->
                    </div>
                </div>
            </div>
        </section>
        <!-- About content -->

        <!-- Facts content -->
        <section class="facts-content" id="facts" data-slide="1" data-stellar-background-ratio="0.5">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 fact-info animated">
                        <h4>产品下载<span>Product download</span></h4>
                        <div class="downloads">
                            <ul class="row product-intro xs_eighty zoomzerosix">
                                <li class="col-xs-6 col-md-6 down-item ">
                                    <a href="">
                                        <i class="icon fa fa-apple fa-5x"></i>
                                        <p>iPhone</p>
                                    </a>
                                </li>
                                <li class="col-xs-6 col-md-6 down-item">
                                    <a href="">
                                        <i class="icon1 fa fa-android fa-5x"></i>
                                        <p>安卓</p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 fact-info animated zoomzeroseven">
                        <h4>功能介绍<span>Function introduction</span></h4>
                        <div class="product-intro textc">
                            <ul>
                                <li class="clearfix">
                                    <i class="icon fa fa-times-circle-o fa-4x"></i>
                                    <strong>精选 ——</strong>
                                    <span>根据你的喜好推荐活动<br/>做最懂你的APP</span>
                                </li>
                                <li class="clearfix">
                                    <i class="icon fa fa-calendar-check-o fa-3x"></i>
                                    <strong>今天 ——</strong>
                                    <span>提供最实时的活动数据<br/>发现此时此刻的现场</span>
                                </li>
                                <li class="clearfix">
                                    <i class="icon fa fa-weixin fa-3x"></i>
                                    <strong>社交 ——</strong>
                                    <span>丰富的兴趣群组社交功能<br/>发现隐藏在身边的现场达人</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Facts content -->

        <!-- Activity content -->
        <section class="activity-content" id="activity">
            <div class="container">
                <div class="row">
                    <div class="fade-text animated">
                        <h3>热门活动</h3>
                        <div class="line1"></div>
                    </div>
                    <div class="space90"></div>
                    <div class="col-md-12 no-padding">
                        <div class="col-md-3 staff-content animated">
                            <div class="staff-img">
                                <a href=""><img src="{$template_url}resources/images/beauty.jpg" class="img-responsive" alt="爵士庆典"/></a>
                            </div>
                            <h4>爵士庆典<span class="yah">2017.10.14</span></h4>
                        </div>
                        <div class="col-md-3 staff-content animated">
                            <div class="staff-img">
                                <a href=""><img src="{$template_url}resources/images/beauty.jpg" class="img-responsive" alt="混凝草音乐节"/></a>
                            </div>
                            <h4>混凝草音乐节<span class="yah">2017.9.16</span></h4>
                        </div>

                        <div class="col-md-3 staff-content animated">
                            <div class="staff-img">
                                <a href=""><img src="{$template_url}resources/images/beauty.jpg" class="img-responsive" alt="南丰城啤酒美食节"/></a>
                            </div>
                            <h4>南丰城啤酒美食节<span class="yah">2017.9.8</span></h4>
                        </div>
                        <div class="col-md-3 staff-content animated">
                            <div class="staff-img">
                                <a href="#"><img src="{$template_url}resources/images/beauty.jpg" class="img-responsive" alt="百威风暴电音节"/></a>
                            </div>
                            <h4>百威风暴电音节 <span class="yah">2017.9.23</span></h4>
                        </div>
                    </div>
                    <div class="space90"></div>
                </div>
            </div>
        </section>
        <!-- Activity content -->

        <!-- Portfolio content -->
        <section class="portfolio-line" id="">
            <div class="container"><div class="row"><div class="col-md-12 comment-content-border"></div></div></div>
        </section>

        <section class="portfolio-wrap" id="portfolio">
            <div class="fade-text animated">
                <h3>热门文章</h3>
            </div>
            <div class="line1"></div>
            <div class="space90"></div>
            <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8 no-padding portfolio-main" id="contain">
                <ul id="filters" class="clearfix">
                    <li><span class="filter" data-filter="all">All</span></li>
                    <li><span class="filter" data-filter=".sort1">Web Design</span></li>
                    <li><span class="filter" data-filter=".sort2">GRAPHIC DESIGN</span></li>
                    <li><span class="filter" data-filter=".sort3">Video</span></li>
                    <li><span class="filter" data-filter=".sort4">Branding</span></li>
                </ul>
                <div id="portfoliolist">
                    <div class="portfolio fact-info animated sort3">
                        <div class="portfolio-wrapper">
                            <img src="{$template_url}resources/images/beauty.jpg" alt="魔都你不知道的鬼市"/>
                            <div class="overlay1">
                                <a href="#">
                                    <div class="overlay-text">View Project</div>
                                </a>
                            </div>
                        </div>
                        <div class="project_intruduction"><div class="p1">魔都你不知道的鬼市</div><div class="p2">凌晨两点的“鬼市”，不一样的世界</div></div>
                    </div>
                    <div class="portfolio fact-info animated sort1">
                        <div class="portfolio-wrapper">
                            <img src="{$template_url}resources/images/beauty.jpg" alt="日本人的13年摇滚"/>

                            <div class="overlay1">
                                <a href="#">
                                    <div class="overlay-text">View Project</div>
                                </a>
                            </div>
                        </div>
                        <div class="project_intruduction"><div class="p1">日本人的13年摇滚</div><div class="p2">日本摇滚现代变奏与反思</div></div>
                    </div>
                    <div class="portfolio fact-info animated sort2">
                        <div class="portfolio-wrapper">
                            <img src="{$template_url}resources/images/beauty.jpg" alt="爱情是树不是花"/>

                            <div class="overlay1">
                                <a href="#">
                                    <div class="overlay-text">View Project</div>
                                </a>
                            </div>
                        </div>
                        <div class="project_intruduction"><div class="p1">爱情是树不是花!</div><div class="p2">最美不过青葱时!</div></div>
                    </div>
                    <div class="portfolio fact-info animated sort1">
                        <div class="portfolio-wrapper">
                            <img src="{$template_url}resources/images/beauty.jpg" alt="中国音乐地图"/>

                            <div class="overlay1">
                                <a href="#">
                                    <div class="overlay-text">View Project</div>
                                </a>
                            </div>
                        </div>
                        <div class="project_intruduction"><div class="p1">中国音乐地图</div><div class="p2">不容错过的精彩</div></div>

                    </div>
                    <div class="portfolio fact-info animated sort2">
                        <div class="portfolio-wrapper">
                            <img src="{$template_url}resources/images/beauty.jpg" alt="摇滚进化论——"/>

                            <div class="overlay1">
                                <a href="#">
                                    <div class="overlay-text">View Project</div>
                                </a>
                            </div>
                        </div>
                        <div class="project_intruduction"><div class="p1">摇滚进化论——"摇滚简史"</div><div class="p2">不容错过的精彩</div></div>
                    </div>
                    <div class="portfolio fact-info animated sort1">
                        <div class="portfolio-wrapper">
                            <img src="{$template_url}resources/images/beauty.jpg" alt="摇滚乐评人是怎样炼成的"/>

                            <div class="overlay1">
                                <a href="#">
                                    <div class="overlay-text">View Project</div>
                                </a>
                            </div>
                        </div>
                        <div class="project_intruduction"><div class="p1">摇滚乐评人是怎样炼成的</div><div class="p2">关于《几近成名》的音乐</div></div>
                    </div>
                    <div class="portfolio fact-info animated sort4">
                        <div class="portfolio-wrapper">
                            <img src="{$template_url}resources/images/beauty.jpg" alt="城市摇滚地图"/>

                            <div class="overlay1">
                                <a href="#">
                                    <div class="overlay-text">View Project</div>
                                </a>
                            </div>
                        </div>
                        <div class="project_intruduction"><div class="p1">城市摇滚地图</div><div class="p2">上海的专属地图</div></div>
                    </div>
                    <div class="portfolio fact-info animated sort1">
                        <div class="portfolio-wrapper">
                            <img src="{$template_url}resources/images/beauty.jpg" alt="请尊重艺术，谁说上海没有音乐！"/>

                            <div class="overlay1">
                                <a href="#">
                                    <div class="overlay-text">View Project</div>
                                </a>
                            </div>
                        </div>
                        <div class="project_intruduction"><div class="p1">请尊重艺术，谁说上海没有音乐！</div><div class="p2">尊重懂吗！</div></div>
                    </div>
                    <div class="portfolio fact-info animated sort4">
                        <div class="portfolio-wrapper">
                            <img src="{$template_url}resources/images/beauty.jpg" alt="直销直信通"/>

                            <div class="overlay1">
                                <a href="#">
                                    <div class="overlay-text">View Project</div>
                                </a>
                            </div>
                        </div>
                        <div class="project_intruduction"><div class="p1">伪摇滚-只听过《怒放的生命》？</div><div class="p2">汪峰-《怒放的生命》</div></div>
                    </div>
                    <div class="portfolio fact-info animated sort1">
                        <div class="portfolio-wrapper">
                            <img src="{$template_url}resources/images/beauty.jpg" alt="《直到世界尽头》再现"/>

                            <div class="overlay1">
                                <a href="#">
                                    <div class="overlay-text">View Project</div>
                                </a>
                            </div>
                        </div>
                        <div class="project_intruduction"><div class="p1">《直到世界尽头》再现</div><div class="p2">灌篮的感人会一直延续</div></div>
                    </div>
                    <div class="portfolio fact-info animated sort1">
                        <div class="portfolio-wrapper">
                            <img src="{$template_url}resources/images/beauty.jpg" alt=""/>

                            <div class="overlay1">
                                <a href="#">
                                    <div class="overlay-text">View Project</div>
                                </a>
                            </div>
                        </div>
                        <div class="project_intruduction"><div class="p1">《Je t'aime moi non plus》</div><div class="p2">你是浪潮，我赤裸的岛</div></div>
                    </div>
                    <div class="portfolio fact-info animated sort3">
                        <div class="portfolio-wrapper">
                            <img src="{$template_url}resources/images/beauty.jpg" alt="当电影遇上后摇"/>

                            <div class="overlay1">
                                <a href="#">
                                    <div class="overlay-text">View Project</div>
                                </a>
                            </div>
                        </div>
                        <div class="project_intruduction"><div class="p1">当电影遇上后摇</div><div class="p2">精彩内容不容错过</div></div>
                    </div>
                </div>
                <div class="col-md-2"></div>
            </div>
            <a class="btn1" style="opacity: 0"></a>
        </section>
        <!-- Portfolio content -->


        <!-- Business Enter -->
        <section class="business-wrap" id="business">
            <div class="fade-text animated">
                <h3>商家入口</h3>
            </div>
            <div class="line1"></div>
            <div class="space90"></div>

            <div class="container fade-text animated">
                <div class="partner row">
                    <div class="col-xs-4 col-sm-3 col-md-2"><img
                        src="https://dn-daowww-prod.qbox.me/images/96140fe7.docker.png"></div>
                    <div class="col-xs-4 col-sm-3 col-md-2"><img
                        src="https://dn-daowww-prod.qbox.me/images/1d661184.aliyun.png"></div>
                    <div class="col-xs-4 col-sm-3 col-md-2"><img
                        src="https://dn-daowww-prod.qbox.me/images/d3499aa5.qcloud.png"></div>
                    <div class="col-xs-4 col-sm-3 col-md-2"><img
                        src="https://dn-daowww-prod.qbox.me/images/846a9bcd.aws.png"></div>
                    <div class="col-xs-4 col-sm-3 col-md-2"><img
                        src="https://dn-daowww-prod.qbox.me/images/fe14a319.ucloud.png"></div>
                    <div class="col-xs-4 col-sm-3 col-md-2"><img
                        src="https://dn-daowww-prod.qbox.me/images/f933fa0f.azure.png"></div>
                    <div class="col-xs-4 col-sm-3 col-md-2"><img
                        src="https://dn-daowww-prod.qbox.me/images/dace51c5.qiniu.png"></div>
                    <div class="col-xs-4 col-sm-3 col-md-2"><img
                        src="https://dn-daowww-prod.qbox.me/images/2d469214.qingcloud.png"></div>
                    <div class="col-xs-4 col-sm-3 col-md-2"><img
                        src="https://dn-daowww-prod.qbox.me/images/1b9118a5.vianet.png"></div>
                    <div class="col-xs-4 col-sm-3 col-md-2"><img
                        src="https://dn-daowww-prod.qbox.me/images/9b9cf033.51idc.png"></div>
                    <div class="col-xs-4 col-sm-3 col-md-2"><img
                        src="https://dn-daowww-prod.qbox.me/images/5b7ffad2.coding1.png"></div>
                    <div class="col-xs-4 col-sm-3 col-md-2"><img
                        src="https://dn-daowww-prod.qbox.me/images/3607431d.cds.png"></div>
                    <div class="col-xs-4 col-sm-3 col-md-2"><img
                        src="https://dn-daowww-prod.qbox.me/images/b848e856.GitCafe.png"></div>
                    <div class="col-xs-4 col-sm-3 col-md-2"><img
                        src="https://dn-daowww-prod.qbox.me/images/22f95ddf.segmentfault.png"></div>
                    <div class="col-xs-4 col-sm-3 col-md-2"><img
                        src="https://dn-daowww-prod.qbox.me/images/c2c9b7e4.oschina.png"></div>
                    <div class="col-xs-4 col-sm-3 col-md-2"><img
                        src="https://dn-daowww-prod.qbox.me/images/7d73f84b.plcloud.png"></div>
                    <div class="col-xs-4 col-sm-3 col-md-2"><img
                        src="https://dn-daowww-prod.qbox.me/images/baef643d.pubu.png"></div>
                </div>
            </div>
        </section>
        <!-- Business Enter -->

        <!-- Contact content -->
        <section class="contact-wrap" id="business"  id="#contact">
            <div class="container">
                <div class="row">
                    <div class="fade-text animated">
                        <h3><img src="{$template_url}resources/images/qrcode.png" width="152" height="152" alt="微信号skygreen2001"></h3>
                        <div class="line1"></div>
                        <p><span class="yahei">没有什么让我们喜欢卷起衣袖，开始一个全新的项目。让我们一起做一些伟大的事情。<br></span>
                        There’s nothing we like more than rolling up our sleeves and starting a brand new project. Let’s make something great together.</p>
                    </div>
                    <div class="space80"></div>
                    <div class="col-md-12 no-padding">
                        <div class="col-md-4 contact-info animated flipInX">
                            <h5><i class="fa fa-phone"></i> Telephone</h5>
                            <p>+86-139-1732-0293</p>
                        </div>
                        <div class="col-md-4 contact-info animated flipInX">
                            <h5><i class="fa fa-map-marker"></i> Address</h5>
                            <p><span class="yahei">上海静安区延平路10弄云和花园6号楼3201</span></p>
                        </div>
                        <div class="col-md-4 contact-info animated flipInX">
                            <h5><i class="fa fa-envelope"></i> Email</h5>
                            <p>skygreen2001@gmail.com </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Contact content -->

      {include file="$templateDir/layout/normal/footer.tpl"}
    </div>

  </div>

  <script src="{$template_url}js/common/bower/index.bower.min.js"></script>
  <script src="{$template_url}js/core/us.js"></script>
{/block}
