<div id="picture2">
        <div id="login-boxBar" class="inputStyle">
         <form class="left" action="<?= site_url("Guest/regSubmit") ?>" method="post">
            <h1>Sign up</h1>
            <input type="text" name="name" placeholder="Name" required/>
            <input type="text" name="surname" placeholder="Surname" required/>
            <input type="text" name="username" placeholder="Username" required/>
            <input type="text" name="e_mail" placeholder="E-mail" required />
            <input type="password" name="pass" placeholder="Password" required/>
            <input type="password" name="pass2" placeholder="Retype password" required/>
            <input type="text" name="address" placeholder="Address"/>
            <input type="hidden" name ="category" value ="bartender"/>
            
           <input type="submit" class = "btn btn-dark" id="regUser" value="Sign up">
           <?php if(isset($poruka)) echo "<p style = 'color: antiquewhite;font-size: 15px;' >$poruka</p><br>"; ?>
          </form> 
        </div>
      </div>