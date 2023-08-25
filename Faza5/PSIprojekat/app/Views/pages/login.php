<div class="login-wrap">
        <div class="login-html">
            <label class="tab" style="color: white">Log In</label>
            <form class="login-form" action="<?= site_url("Guest/loginSubmit") ?>" method="post">
                <div class="sign-in-htm">
                    <div class="group">
                        <label for="user" class="label">Username</label>
                        <input id="user" type="text" class="input" name="username">
                    </div>
                    <div class="group">
                        <label for="pass" class="label">Password</label>
                        <input id="pass" type="password" name="pass" class="input" data-type="password">
                    </div>
                    <div class="group">

                        <input type="submit" class="button" id="Login" value="Log In">
                        <?php if(isset($poruka)) echo "<p style = 'color: antiquewhite;font-size: 15px;' >$poruka</p><br>"; ?>
                    </div>
                    <div class="hr"></div>
                    <div class="foot-lnk">
                        <a href="signupChoice" id="signup" style = "color: antiquewhite; font-style: italic;">Don't have an account? Sign up.</a>
                    </div>
                </div>
            </form>
        </div>
    </div> 
