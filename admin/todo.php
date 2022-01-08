<!DOCTYPE html>
<html lang="zh-CN" id="index">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <?php
    require_once '../init.php';
    $url_base = Gc::$url_base;
    $app_name = Gc::$appName;
    $isAds    = true;
    ?>

    <meta http-equiv="Lang" content="zh_CN" />
    <meta name="author" content="skygreen" />
    <meta http-equiv="Reply-to" content="skygreen2001@gmail.com" />
    <meta name="keywords" content="Betterlife" />
    <meta name="description" content="Betterlife" />
    <meta name="creation-date" content="12/01/2010" />
    <meta name="revisit-after" content="15 days" />
    <title>Betterlife</title>
    <link rel="icon" href="<?php echo $url_base?>favicon.ico" mce_href="<?php echo $url_base?>favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo $url_base?>home/betterlife/view/bootstrap/resources/css/common.css">
    <script src="<?php echo $url_base?>home/betterlife/view/bootstrap/js/common/base.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo $url_base?>home/betterlife/view/bootstrap/resources/css/public.css" />
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style type="text/css">
      html, .us {
        height: 100%;
      }
      .index .section#page1 .bb-icon{
        cursor: pointer;
      }
      .index .section#page1 .section-header-container{
        position: relative;
      }
      .index .section#page1 {
        padding: 0;
        height: auto;
      }
      .us .contact-wrap{
        padding: 50px 0;
      }
      .bb-lead-core{
        margin: 50px 0;
        padding-top: 5px
      }
      footer{
        width: 100%;
        z-index: 1000;
      }
      @media (max-width: 450px) {
        .index #page1 .section-header-container{
          position: relative;
          padding: 40px;
        }
      }
      @media (min-width: 320px) {
        .index #page1 .lead .btn{
          font-size: 16px;
        }
      }
      @media (min-height: 1000px) {
        .bb-lead-core{
          position: absolute;
          margin: auto 0;
          padding: 0;
          top: 0;
          bottom: 0;
          left: 0;
          right: 0;
        }
        .index #page1 {
          height: 100%;
        }
        .index #page1 .section-header-container{
          position: absolute;
          padding: 40px;
        }
        #header,#contact{
          height: 50% !important;
          position: relative !important;
        }
        #header{
          padding-top: 0;
        }
        footer{
          position: absolute;
          bottom: 0px;
        }
      }
      .us.pure #header, .us.pure #page1{
         height: 100% !important;
      }
      .us.pure footer{
        position: absolute;
        bottom: 0px;
      }
      .us.pure #contact{
         display: none;
         height: 0px;
      }
      .us.pure .bb-lead-core{
        position: absolute;
        margin: auto 0;
        padding: 0;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
      }
      .us.pure .lead{
        width: 80%;
      }
    </style>
  </head>

  <body class="index">
    <div class="us pure">
      <div id="main-content-container" class="container-fluid">
        <div class="section page" id="page1">
          <div class="container section-header-container" id="header">
            <div class="bb-lead-core">
              <a href="<?php echo $url_base?>index.php?go=<?php echo $app_name?>.index.index"><span class="bb-icon">BB</span></a>
              <p class="lead" id="lead-txt">尚在建设中</p>
              <p class="lead" id="lead-link"><a href="https://github.com/skygreen2001/betterlife/archive/master.zip" target="_blank" class="btn btn-outline-inverse btn-lg">下载 Betterlife Framework</a></p>
            </div>
          </div>
          <!-- Contact content -->
          <section class="contact-wrap" id="contact">
              <div class="container">
                  <div class="row">
                      <div class="fade-text animated">
                          <h3><img src="<?php echo $url_base?>home/<?php echo $app_name?>/view/bootstrap/resources/images/qrcode.png" width="152" height="152" alt="微信号skygreen2001"></h3>
                          <div class="line1"></div>
                          <p><span class="yahei">只为更好让我们喜欢卷起衣袖，开始一个崭新的项目。让我们一起做一些Better的事情。<br></span>
                          There’s nothing we like more than rolling up our sleeves and starting a brand new project. Let’s make something better together.</p>
                      </div>
                      <div class="space80"></div>
                      <div class="col-md-12 no-padding">
                          <div class="col-md-4 contact-info animated">
                              <h5><i class="fa fa-phone"></i> Telephone</h5>
                              <p>+86-139-1732-0293</p>
                          </div>
                          <div class="col-md-4 contact-info animated">
                              <h5><i class="fa fa-map-marker"></i> Address</h5>
                              <p><span class="yahei">上海静安区静安公园静安寺666号8单元108室(虚拟)</span></p>
                          </div>
                          <div class="col-md-4 contact-info animated">
                              <h5><i class="fa fa-envelope"></i> Email</h5>
                              <p>skygreen2001@gmail.com </p>
                          </div>
                      </div>
                  </div>
              </div>
          </section>
          <!-- Contact content -->
        </div>

        <footer class="text-muted">
            <div id="footer-inner" class="container clr">
                <div id="copyright" class="clr" role="contentinfo">
                  © 2017-2020 Betterlife - All Rights Reserved.&nbsp;
                  <div id="link" class="clr" role="linkinfo"><a title="License" href="https://github.com/skygreen2001/betterlife/blob/master/LICENSE" target="_blank">License</a>&nbsp;| <a title="Help" href="https://github.com/skygreen2001/betterlife" target="_blank">Help</a></div>
                </div>
            </div>
        </footer>

        <div class="return-top">
            <span class="icon-stack fa-3x">
              <i class="fa fa-circle fa-stack-2x"></i>
              <i class="fa fa-minus fa-inverse fa-stack-1x up"></i>
              <i class="fa fa-arrow-up fa-inverse fa-stack-1x down"></i>
            </span>
        </div>

        <script type="text/javascript" src="<?php echo $url_base?>home/admin/view/default/js/common/bower/bower.min.js"></script>
        <script type="text/javascript" src="<?php echo $url_base?>home/admin/view/default/js/normal/common.js"></script>
        <script type="text/javascript" src="<?php echo $url_base?>home/admin/view/default/js/normal/layout.js"></script>
        <script type="text/javascript" src="<?php echo $url_base?>home/<?php echo $app_name?>/view/bootstrap/js/common/bower/index.bower.min.js"></script>
      </div>
    </div>

    <script type="text/javascript">
    $(function() {
      var isAds = false;
      <?php if ($isAds) { ?> isAds = true; <?php } ?>
      var height = $(".bb-icon").height()+$("#lead-txt").height()+$("#lead-link").height()+2*$(".lead").height();
      $(".bb-lead-core").css({"height":height});
      if (isAds ) $(".us").removeClass("pure");
      $(window).scroll(function() {
        $('.contact-info').each(function() {
          var imagePos = $(this).offset().top;
          var topOfWindow = $(window).scrollTop();
          if (imagePos < topOfWindow+550) {
            $(this).addClass("flipInX");
          }
        });
      });
    });
    </script>

  </body>
</html>
