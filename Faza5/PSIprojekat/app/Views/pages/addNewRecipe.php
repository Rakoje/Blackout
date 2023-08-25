<div class="row recipe-background">
      <div class="col-sm-4 recipe-form mt-4 text-center" style="color:antiquewhite; margin-bottom: 10px;">
          <h1 class="mt-2">Add a new recipe</h1>
          <input type="text" name="name" id="name" placeholder="Name of the cocktail" style="margin-bottom: 10px;" />
          <br>
          <!--Choose an image for the cocktail(.jpg only)<input type="file" class ="custom-file-input" name="image" accept =".jpg" id="image" style="margin-bottom: 10px;">
          <br>-->
          <textarea name="description"  id="description"  placeholder="Write a description" style = "border-radius: 10px; color: antiquewhite" cols="30" rows="10" ></textarea>
          <br>
          <input type="text" id="numOfServings" placeholder="Number of servings" style="margin-bottom: 10px;" />
          <br>
          <input type="text" id="preparationTime" placeholder="Preparation time (in minutes)" style="margin-bottom: 10px;"  />
          <br>
          <input type="text" id="strength" placeholder="Strength (up to 10)" style="margin-bottom: 10px;" >
          <br>
          <p>Choose ingredients for your cocktail:</p>
          <div class='container col-sm-8'>
          <p><select class="form-control selectpicker"  id="select-country" data-live-search="true">
          <?php foreach($ingredients as $ingredient){
                echo '<option id="'.$ingredient->IdI.'" class="addIng" ><p   id="'.$ingredient->IdI.'">'.$ingredient->Name.'</p></option>';
            }  ?>           
              </select></p>
              
              <p><input type='text' id='quantity' placeholder="Quantity"></p>
          </div>
          <p>Selected ingredients (click to remove):</p>
          <ul id="add">
             
          </ul>
          <p>Missing ingredients?&nbsp;<button class="btn btn-dark" style = "background-color: purple"onclick="location.href='<?= site_url("Bartender/addNewIngredient") ?>'">Click here</button></p>
          <textarea id="method" style = 'color: antiquewhite'  placeholder="Method" id="" cols="30" rows="10" required></textarea>
          <br>
          <button id="submit" class="btn btn-dark addCocktail" style="margin-bottom: 10px;">SUBMIT</button>
          <p style = 'color: antiquewhite;font-size: 15px;'  id="errorMessage"></p><br>
        </div>
        </div>

<script type="text/javascript">
    var ingredients = new Array();
    var quantity = new Array();
    var i = 0;
    $(document).ready(function(){
     $(document).on('click', '.addIng', function(){
        var nameIng = $(this).text();
        var quantityOfIng = $('#quantity').val();
        if(quantityOfIng != 0){
            var flag = 0;
            for(var j = 0; j < i; j++){
                if(ingredients[j] === nameIng){
                    flag = 1;
                    break;
                }
            }
            if(!flag){
                ingredients[i] = nameIng;
                quantity[i++] = quantityOfIng;
                $('#add').append('<li type="button" class="removeMe">'+nameIng+' '+quantityOfIng+'</li>');
            }
        }else{
            alert('Please specify the quantity of the ingredient before you choose.');
        }
    });
    $(document).on('click', '.removeMe', function(){
        var nameIng = $(this).text();
        $(this).remove();
        for(var j = 0; j < i; j++)
            if (ingredients[j] === nameIng){
                ingredients.splice(j, 1);
                quantity.splice(j, 1);
            }
        i--;
    });
    $(document).on('click', '#submit', function(){
        var name = $('#name').val();
        var description = $('#description').val();
        var numOfServings = $('#numOfServings').val();
        var prepTime = $('#preparationTime').val();
        var strength = $('#strength').val();
        var method = $('#method').val();
        alert(numOfServings)
        $.ajax({
            url: "<?php echo base_url('Bartender/addRecipe'); ?>",
            dataType: 'json',
            data: {name:name, description:description, numOfServings:numOfServings, ingredients:ingredients,
                   prepTime:prepTime, strength:strength, method:method, quantity:quantity},
            method: 'GET',
            success: function(data){
                if(data==="success")
                    window.location.href = "<?php echo base_url('Bartender/profile'); ?>"
                else
                    $("#errorMessage").html(data);
            }
        });
    });
   });
</script>