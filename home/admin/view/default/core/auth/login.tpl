{extends file="$templateDir/layout/layout_login.tpl"}
{block name=body}
<body class="login">
    <!--content begin-->
    <div class="page-container login-container">
      <main>
        <div class="content-wrapper">
          <div class="content">
            <form method="POST">
              <div class="panel panel-body login-form">
                <div class="bb-icon">B</div>

                <div class="text-center">
                  <div class="icon-object border-warning-400 text-warning-400"><i class="icon-people"></i></div>
                  <h5 class="content-group-lg">登录您的账号</h5>
                </div>

                <div class="form-group has-feedback has-feedback-left">
                  <input name="username" type="text" class="form-control" placeholder="用户名">
                  <div class="form-control-feedback">
                    <i class="icon-user text-muted"></i>
                  </div>
                </div>

                <div class="form-group has-feedback has-feedback-left">
                  <input name="password" type="password" class="form-control" placeholder="密码">
                  <div class="form-control-feedback">
                    <i class="icon-lock2 text-muted"></i>
                  </div>
                  <p class="message">{$message}</p>
                </div>

                <div class="form-group login-options">
                  <div class="row">
                    <div class="col-sm-6 has-login-remember">
                      <label class="checkbox-inline" for="login-remember">
                        <div class="checker"><span><input type="checkbox" id="login-remember" class="styled"></span></div>
                        记住密码
                      </label>
                    </div>

                    <div class="col-sm-6 text-right">
                      <a href="index.html">忘记密码? </a>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <button type="submit" class="btn bg-blue btn-block">登录 <i class="icon-circle-right2 position-right"></i></button>
                </div>

                <div class="content-divider text-muted form-group"><span>测试账户</span></div>
                <div class="test-info">
                  <div>[超级用户] 账户名: admin     密码: admin</div>
                  <div>[普通用户] 账户名: skygreen  密码: iloveu</div>
                </div>

              </div>
            </form>
          </div>
        </div>
      </main>

      <!--footer begin-->
      <footer>
        <div id="footer-inner" class="container clr">
          <div id="copyright" class="clr" role="contentinfo">© 2017-2020 Betterlife - All Rights Reserved.&nbsp;<a title="License" href="https://github.com/skygreen2001/betterlife.core/blob/master/LICENSE" target="_blank">License</a>&nbsp;| <a title="Help" href="https://github.com/skygreen2001/betterlife.core" target="_blank">Help</a></div>
        </div>
      </footer>
      <!--footer end-->
    </div>
    <!--content end-->


    <script src="{$template_url}js/common/bower/bower.min.js"></script>
    <script src="{$template_url}js/common/common.jquery.min.js"></script>

    <script type="text/javascript">
      $(function(){
        $(".checker").click(function(){
          if ($(this).find("span").hasClass("checked")){
            $(this).find("span").removeClass("checked");
            $(this).find("input").removeAttr("checked");
          }else{
            $(this).find("span").addClass("checked");
            $(this).find("input").attr("checked","checked");
          }
        });

        $(".login-container").css("min-height",$(window).height());

      });
    </script>
</body>
{/block}
