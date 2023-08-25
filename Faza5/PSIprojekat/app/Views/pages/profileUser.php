<div class = "container txt-style">
        <div class = "row" style="border-radius: 20px; background-color: rgba(183, 27, 173, 0.1); padding: 10px">
            <?php if(isset($message)) echo "</br><p id='cngps' >$message</p></br>"; ?>
            <div class = "col-sm-3">
                <div class = "row" style="border-radius: 20px; background-color: rgba(155, 15, 146, 0.2); padding: 10px">
                    <div class = "col-sm-12">
                        <img src = "/assets/profile.jpg" class = "margin-top"  style='border-radius: 50%; max-width: 150px'>
                        <h3><?php  echo $user->Username;?></h3>
                        <p><?php  echo $user->Name; ?>
                        <?php  echo $user->Surname; ?></p>
                        <p  style="opacity: 0.7"><i class="fa fa-envelope-o" aria-hidden="true"></i><?php  echo ' '.$user->E_mail; ?></p>
                        <p  style="opacity: 0.7"><i class="fa fa-map-marker" aria-hidden="true"></i>
                        <?php  echo ' '.$user->Address; ?></p>
                        <?php
                            if($category == 'store'){
                                echo '<p style="opacity: 0.7"><i class="fa fa-address-card-o" aria-hidden="true"></i> '.$store->Owner.'</p>
                                      <p style="opacity: 0.7"><i class="fa fa-clock-o" aria-hidden="true"></i> '.$store->OpeningHours.'-'.$store->ClosingHours.'</p>
                                      <p style="opacity: 0.7"><i class="fa fa-phone" aria-hidden="true"></i> '.$store->Phone.'</p>';
                            }
                        ?>
                        <a href="<?= site_url($controller."/changePassword")?>"><button class = "btn" style="background-color: rgba(155, 15, 146, 0.4); color: antiquewhite">Change password</button></a>
                        <?php
                            use App\Models\RecipeModel;
                            use App\Models\HasRatedModel;
                            if($category == 'bartender'){
                                $recipeModel = new RecipeModel();
                                $ratings = $recipeModel->where("IdU", $user->IdU)->find();
                                $sum = 0;
                                $num = 0;
                                $reviews = 0;
                                foreach($ratings as $rating){
                                    if($rating->Rating!=0){
                                        $sum+=$rating->Rating;
                                        $num++;
                                        $reviews+=$rating->NumberOfRatings;
                                    }
                                }
                                $avg = $sum/$num;
                                echo "<div>
                                                <span class='heading-rating'>User Rating: ".$avg."</span>
                                                <p style='opacity: 0.7'>based on $reviews reviews</p>
                                            </div>";

                            }
                        ?>
                    </div>
                </div>
            </div>
            <?php
                if($category != 'store'){
                    echo '<div class = "col-sm-9" style="padding-left: 20px; ">
                <div class = "row bar-text" style="border-radius: 20px;">
                    <div class = "col-sm-12">
                        <div class="row">
                            <h1 style="background: rgba(30, 0, 30, 0.7); border-radius: 5px"><b>MY BAR</b></h1>
                            <div class="col-sm-4"></div>
                            <div class="col-sm-4"><h3>Add</h3>
                               <select class="form-control selectpicker" id="select-country" data-live-search="true" style="background-color: black !important;">';
                                     
                                        foreach($ingredients as $ingredient){
                                            echo '<option id="'.$ingredient->Name.'" class="findMe"><p type="button"  id="'.$ingredient->Name.'">'.$ingredient->Name.'</p></option>';
                                        }
                            echo    '</select></div>
         
                            <div class="col-sm-4"></div></div>
                            <br>
                                <h3>My ingredients:</h3>
                                
                                <div id="possession"></div>
                                
                        </div>

                    </div>';
                }
                    else{
                        echo '<div class="col-sm-9" style="padding-left:15px; padding-top: 1px">
                            <div class="mapouter"><div class="gmap_canvas">
                            <iframe style="width:100%; height:515px; border-radius:20px" id="gmap_canvas" src="https://maps.google.com/maps?q='.$user->Address.'&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="yes" ></iframe></div></div>
                            </div>';
                    }
                        if($category == 'bartender'){
                            echo '<div class="row" style="padding-top: 5px;">
                                    <div class="col-sm-12">
                                        <div class="row recipe-prof-text" style="border-radius: 20px;">
                                        <h1 style="background: rgba(30, 0, 30, 0.7); border-radius: 5px"><b>MY RECIPES</b></h1>
                                        <div id="myRecipes"></div>

                                        </div>
                                    </div>
                                </div>';
                        }

                echo '</div>';
                
            ?>
                </div>
            
        </div>
</br>
<script type="text/javascript">
    $(document).ready(function(){
        function load_data(){
            $.ajax({
                url: "<?php echo base_url("User/loadIngredients");?>",
                dataType: 'json',
                success: function(data){
                    var 
                        html = '<div class="row">'
                        for(var count = 0; count < data.length; count++)
                        {
                          html += '<div class="col-sm-2 flexCenter hoverRemove btn_delete " id="'+data[count].IdI+'" type="button" style="margin-top: 3px"><img src="/assets/'+data[count].Name+'.png" style="max-width: 90px; max-height: 90px">\n\
                                    <p class="table_data" data-row_id="'+data[count].IdI+'" data-column_name="Name" style="vertical-align: text-bottom">'+data[count].Name+'</p>\n\
                                    <div type="button" name="delete_btn"  class="btn_delete fa fa-times removeIng" style="color: antiquewhite"></div></div>';
                        }
                        html += '</div>';
                    $('#possession').html(html);
                }
            });
        }
        function load_data2(){
            $.ajax({
                url: "<?php echo base_url("Bartender/loadRecipes");?>",
                dataType: 'json',
                success: function(data){
                    var 
                        html = '<div class="row">';
                        for(var count = 0; count < data.length; count++)
                        {
                          html += '<div class="col-sm-3 flexCenter goToRecipe" id="'+data[count].IdR+'" type="button" style="margin-top: 3px"><img src="/assets/'+data[count].Name+'.jpg" style="width:100%; border-radius: 10px">\n\
                                    <p class="table_data" data-row_id="'+data[count].IdR+'" data-column_name="Name" style="vertical-align: text-bottom">'+data[count].Name+'</p>\n\
                                    </div>';
                        }
                        html += '<div class="col-sm-3 addNewRecipe"  type="button" style="border: 2px dashed; border-radius: 10px; ">\n\
                                Add a new recipe</div></div>';
                    $('#myRecipes').html(html);
                }
            });
        }
        load_data();
        load_data2();
         $(document).on('click', '.btn_delete', function(){
          var id = $(this).attr('id');
          $.ajax({
            url:"<?php echo base_url('User/removeIngredient'); ?>",
            method: "POST",
            data: {idIng:id},
            success:function(data){
              load_data();
            }
          })
    });
    $(document).on('click', '.findMe', function(){
        var nameIng = $(this).text();
        $.ajax({
          url: "<?php echo base_url("User/addIngredient");?>",
          method:"POST",
          data: {name:nameIng},
          success:function(data){
            load_data();
          }
        })
    });
    $(document).on('click', '.goToRecipe', function(){
        var nameRec = $(this).text().trimStart();
        location.href = "<?php echo base_url("Bartender/viewCocktail?rn=");?>"+nameRec;
    });
    $(document).on('click', '.addNewRecipe', function(){
        location.href = "<?php echo base_url("Bartender/addNewRecipe");?>";
    });
    });
    
   
    


</script>

