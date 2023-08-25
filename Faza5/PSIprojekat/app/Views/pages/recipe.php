<div class="container" style="color: antiquewhite; ">
        <div class="row">
            <div class="col-sm-12">
                <br><br>
              <?php echo " <h1>$recipe->Name</h1>" ?> <!-- NAZIV KOKTELA -->
            </div>
        </div>
        <div class="row">
            <div class="col-sm-5">
                <img src="/assets/<?php echo $recipe->Name?>.jpg" style="position: center; width: 100%; border-radius: 7px" alt="">
            </div>
            <div class="col-sm-7">
                <?php echo " <p>$recipe->Description</p>" ?> 
               <p><i class="fa fa-user-circle-o" aria-hidden="true"></i> &nbsp; Serves <?php echo $recipe->NumOfServings ?></p> 
                <p name="prepare"><i class="fa fa-clock-o" aria-hidden="true"></i> &nbsp; <?php echo $recipe->PrepTime ?> mins to prepare</p> 
                <p><i class="fa fa-thermometer-full" aria-hidden="true"></i>  &nbsp; Strength: <?php echo $recipe->Strength ?> /10 </p> 
                <p>Published <?php echo $recipe->CreationDate." by ".$bartenderUsername ?></p> 
                <?php 
                if(isset($_SESSION['user'])){
                    echo "<p><h2>Rate this cocktail:</h2></p>";
                    echo' <div class="rating-css">';
                    echo' <div class="star-icon">';
                    echo'<input type="radio" class = "ratings"name="rating1" id="rating1">';
                    echo'<label for="rating1" id="rating1star" class="fa fa-star"></label>';
                    echo'<input type="radio" name="rating1" class="ratings" id="rating2">';
                    echo'<label for="rating2" id="rating2star" class="fa fa-star" ></label>';
                    echo'<input type="radio" name="rating1" class = "ratings" id="rating3">';
                    echo'<label for="rating3" id="rating3star" class="fa fa-star"></label>';
                    echo'<input type="radio" name="rating1" class = "ratings" id="rating4">';
                    echo'<label for="rating4" id="rating4star" class="fa fa-star"></label>';
                    echo'<input type="radio" name="rating1"class = "ratings" id ="rating5" >';
                    echo'<label for="rating5" id="rating5star" class="fa fa-star"></label>';
                    echo'</div>';
                    echo'<p id = "avgRating"></p>'; 
                    echo'<p id = "noOfRatings"></p>';
                    echo'</div>';
                }
                  ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 margin-left">
                <br>
                <h2>Ingredients</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 margin-left">
                <ul>
                    <?php
                        $i = 0;
                        foreach($ingredients as $ingredient){
                            echo "<li>".$imo[$i]->Quantity." ".$ingredient->Name."</li>";
                            $i++;
                        }
                    ?>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 margin-left">
                <h2>Method</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 margin-left">
                <ol>
                    <?php echo $recipe->Method ?>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 margin-left">
                <h2>Comments</h2>
                <br>
            </div>
        </div>
        <div class="row d-flex margin-left">
            <div class="col-md-12">
                <div class="card px-2 p-3 purple row">
                    <div class="d-flex justify-content-between align-items-center col-md-12">
                        <?php 
                            if(isset($_SESSION['user'])){
                                echo '<input type="text" id = "commentContent"placeholder="Leave a comment..." style="border-radius: 4px; width:200%">';
                            }
                            else{
                                echo 'Log in to post your comments';
                            }
                        ?>
                        <?php 
                            if(isset($_SESSION['user'])){
                                echo '<input type="submit"   id="postComment" value="Post">';
                            }
                            else{
                                echo '<a href = "login"><input type = "submit" class = "btn btn-dark" value = "Sign in"> </a>';
                            }
                        ?>

                    </div>
                </div>
                <div id ="comments"></div>
                
            </div>
        </div>
    </div>

<script type ="text/javascript">
    $(document).ready(function(){

    var IdRecipe = <?php echo $recipe->IdR ?>;
        function load_rating(){
            $.ajax({
                url: "<?php echo base_url("User/loadRating"); ?>",
                dataType: 'json',
                data: {IdRecipe: IdRecipe},
                method: "GET",
                success: function(data){
                    var recipeRating = data[0].recipeRating;
                    var numOfRatings = data[0].recipeNo;
                    $("#avgRating").text("Average rating: "+ recipeRating);
                    $("#noOfRatings").text("Based on "+ numOfRatings + " ratings");
                    
                }
                
            });
        }

        function load_comments(){
            $.ajax({
                url: "<?php echo base_url("User/loadComments"); ?>",
                dataType: 'json',
                data: {IdRecipe: IdRecipe},
                method: "GET",
                success: function(result){
                    $("#comments").html(" ");
                result.data.forEach(function(element){
                    var comment = element.comment;
                    var remove = "";
                    if(element.username == result.myUsername)
                        remove = '<p style = "float:right;"><div><input type="submit" class="deleteComment"  id="removeComment" value="Delete"></div><div style = "display:none">'+comment.IdC+'</div></p>';
                    var html = $("#comments").html();
                    html += '<div class="card p-3 mt-2 purple">';
                    html +='<div class="d-flex justify-content-between align-items-center">';
                    html += '<div class="user d-flex flex-row align-items-center"> ';
                    html += ' <span><small class="font-weight-bold" style="color:antiquewhite"> &nbsp;<b>' + element.username + '</b></small>';
                    html +='<small > <p>&nbsp'+ comment.Content + '</p></small></span> </div> <small><p>'+ comment.CreationDate+'</p>' +remove + '</small></div>';
                    $("#comments").html(html);
                });
                
                }
            });
        }
        load_rating();
        load_comments();
         $(document).on('click', '#postComment', function(){
            var content = $("#commentContent").val();
            var IdR = <?php echo $recipe->IdR ?>;
            $.ajax({
                url: "<?php echo base_url("User/postComment");?>",
                method:"GET",
                data: {content:content, IdR: IdR},
                success:function(){
                    load_comments();
                }
            });
         });
          $(document).on('click', '.deleteComment', function(){
            var IdC = $(this).parent().next().html();
            $.ajax({
                url: "<?php echo base_url("User/deleteComment");?>",
                method:"GET",
                data: {IdC: IdC},
                success:function(){
                    load_comments();
                }
            });
         });
         $(document).on('click', '.ratings', function(){
           let id = $(this).attr('id');
           let rating;
           switch(id){
                case "rating1": rating = 1; break;
                case "rating2": rating = 2; break;
                case "rating3": rating = 3; break;
                case "rating4": rating = 4; break;
                case "rating5": rating = 5; break;
                default: rating = 0; break;
           }
           let IdR = "<?php echo $recipe->IdR?>";
           $.ajax({
               url: "<?php echo base_url("User/rateRecipe");?>",
               method:"GET",
               data: {rating: rating, IdR: IdR},
               success: function(){
                   load_rating();
               }
           });
           
        });
    });
</script>
