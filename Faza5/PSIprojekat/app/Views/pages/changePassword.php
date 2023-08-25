<div class="login-wrap">
        <div class="login-html">
            <label class="tab" style="color: white">Change Password</label>
            <form class="login-form" action="<?= site_url($controller.'/changePassSubmit')?>" method="post">
                <div class="sign-in-htm">
                    <div class="group">
                        <label for="pass" class="label">Old Password</label>
                        <input id="pass" type="password" class="input" name="old" data-type="password">
                    </div>
                    <div class="group">
                        <label for="pass" class="label">New Password</label>
                        <input id="pass" type="password" class="input" data-type="password" name="new">
                    </div>

                    </br>
                    <div class="group">
                        <button type="submit" class="button"  value="Change">CHANGE</button>
                        <?php if(isset($message)) echo "<p style = 'color: antiquewhite;font-size: 15px;' >- $message</p>"; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>