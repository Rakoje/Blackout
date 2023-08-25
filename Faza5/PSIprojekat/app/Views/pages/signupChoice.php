<!DOCTYPE html>
<div class = "login-wrap">
    <div class ="login-html" style = "text-align: center">
        <label class="tab" style="color: white; text-align: center;">Pick a category:</label>
         <form class="login-form" action="<?= site_url("Guest/signupCategory") ?>" method="post">

            
                    <div class="group">
                        <input type="button" onclick='location.href="signupUser"' class="button" id="User" value="User">
                    </div>
                    <div class="group">
                        <input type="button" onclick='location.href="signupBartender"' class="button" id="Bartender" value="Bartender">
                    </div>
                    <div class="group">
                        <input type="button" onclick='location.href="signupAdmin"' class="button" id="Admin" value="Admin">
                    </div>
                    <div class="group">
                        <input type="button" onclick='location.href="signupStore"' class="button" id="Store" value="Store">
                    </div>
            <input type ="hidden" name = "category" id = "category">    
         </form>
    </div>
    
</div>
