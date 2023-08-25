<!DOCTYPE html>
<div id="picture4">
        <div id="login-boxStore" >
             <div class="leftStore inputStyle">
                <h1>Sign up</h1>
                 <form  action="<?= site_url("Guest/regSubmitStore") ?>" method="post">
                <input type="text" name="name" placeholder="Store name" required/>
                <input type="text" name="username" placeholder="Username" required/>
                <input type="text" name="e_mail" placeholder="E-mail" required />
                <input type="password" name="pass" placeholder="Password" required/>
                <input type="password" name="pass2" placeholder="Retype password" required/>
                <input type="text" name="address" placeholder="Store address" required/>
                 <text>Working hours of store:</text>
                 <div style="display: flex;">
                     <p>
                         <label for="appt" id="from">From</label >
                         <input type="time" style="border-radius:8px" id="appt" name="startHours">
                     </p>
                     <p>
                         <label for="appt" id="to">To</label>
                         <input type="time"  style="border-radius: 8px" id="appt" name="endHours" >
                     </p>
                  </div>
            </br>
              <textarea rows="3" cols = "28" style ="border-radius: 8px" name = "description"placeholder="Brief store description"></textarea>
              <input type="text" name="phone" placeholder="Phone number" />
              <input type="text" name="ownerName" placeholder="Owner name" id="imeV"required/>
              <input type="hidden" name ="category" value ="store"/>
              <input type="submit" class = "btn btn-dark" id="regStore" value="Sign up" style="">
              <?php if(isset($poruka)) echo "<p style = 'color: antiquewhite;font-size: 8px;' >$poruka</p><br>"; ?>

              </form>
        </div>
    </div>
</div>
