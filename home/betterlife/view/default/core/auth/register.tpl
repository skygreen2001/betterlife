{extends file="$template_dir/layout/normal/layout.tpl"}
{block name=body}
    <form method="POST">
    <div class="login-container">
        <h2></h2>
        <div>
            <div class="modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">请注册您的账户</h3>
                        </div>
                        <div class="modal-body" style="height:180px;">
                           <label class="login-label">用户名</label><input class="inputNormal inputRegister" type="text" name="username" /><br/><br/>
                           <label class="login-label">密&nbsp;&nbsp;码</label><input class="inputNormal inputRegister" type="password" name="password" /><br/>
                           <br/>
                           <label class="login-label">邮&nbsp;&nbsp;箱</label><input class="inputNormal inputRegister" type="text" name="email" />
                           <p class="message">{$message}</p>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" name="Submit" value="注册" class="btnSubmit" />
                            <button type="button" class="btn btn-login inputNormal" onclick="javascript:window.location.href='{$url_base}index.php?go={$app_name}.auth.login'">登录</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>



{/block}
