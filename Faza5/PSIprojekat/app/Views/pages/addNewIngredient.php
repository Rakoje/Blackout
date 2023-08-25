<div id="full">
    <div id="picture5">
      <div id="login-boxIng">
        <div  style="color:antiquewhite">
            <h4>ADD INGREDIENT</h4>
            <p> <input type="text" name="name" id="name" placeholder="Ingredient name" required/></p>
            <p><label for="img">Select image:</label></p>
            <p><input type="file" id="image" name="image" class ="custom-file-input" accept=".png" style="background-color: purple"></p>
            <p><button id="submit" class="btn btn-dark">submit</button></p>
        </div>
      </div>
    </div>
</div>
  
<script type="text/javascript">
    $(document).ready(function(){
       $("#submit").on('click', function(){
        var file_data = $("#image").prop("files")[0];   
        var form_data = new FormData();
        form_data.append("file", file_data);
        form_data.append("name", $('#name').val());
        $.ajax({
            url: "<?php echo base_url('Bartender/addIngredientNew'); ?>",
            dataType: 'script',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            method: 'POST',
            type: 'post',
            success: function(){
                window.location.href = "<?php echo base_url('Bartender/addNewRecipe'); ?>";
                
            }
        });
      }); 
    });
</script>  

