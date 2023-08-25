<div class="row">
<div class="col"></div>
<div class = "col-sm-9" style="padding-left: 20px; ">
                <div class = "row bar-text" style="border-radius: 20px;">
                    <div class = "col-sm-12">
                       <?php if(!isset($user)){
                            echo  '<h3>Choose your ingredients:</h3>  
                                    <div class="row">
                                        <div class="col-sm-4"></div>
                                           <div class="col-sm-4">
                                            <select class="form-control selectpicker" id="select-country" data-live-search="true">';
                                                     foreach($ingredients as $ingredient){
                                                         echo '<option id="'.$ingredient->IdI.'" class="addIng"><p type="button" id="'.$ingredient->IdI.'">'.$ingredient->Name.'</p></option>';
                                                     }
                             

                                            echo '</select><span><button class="searchByIng btn btn-purple" style="color: antiquewhite"><i class="fa fa-search" aria-hidden="true"></i></button></span>

                                         </div>

                           


                                         <div class="row" id="add" style="margin-top: 5px">

                                         </div> 
                                     </div>';
                       }
                      ?>
                        
                        
                    </div>
                    </br>
                    </br>
                    <h1><b>OR</b></h1>
                    </br>
                    </br>
                    <div class="row">
                    <div class = "col-sm-12">
                        
                                <h3>Search cocktail by name:</h3>
                                
                                <div class="row">
                                
                            <div class="col-sm-4"></div>    
                                <div class="col-sm-4">
                                <select class="form-control selectpicker" id="select-country" data-live-search="true">
                                     <?php
                                        foreach($recipes as $recipe){
                                            echo '<option id="'.$recipe->Name.'" class="findMe"><div type="button" id="ingr">'.$recipe->Name.'</div></option>';
                                        }
                                     ?>
                                </select></div>
                                
                            <div class="col-sm-4"></div>
                        </div>
                    </div>
                    </div>
                </div>
        </div>
<div class="col"></div>
</div>
<script type="text/javascript">
    var ingredients = new Array();
    var i = 0;
    $(document).ready(function(){
        $(document).on('click', '.findMe', function(){
        var nameRec = $(this).text();
        location.href = "<?php echo base_url("Guest/viewCocktail?rn=");?>"+nameRec;
    });
    $(document).on('click', '.addIng', function(){
        var nameIng = $(this).text();
        var flag = 0;
        for(var j = 0; j < i; j++){
            if(ingredients[j] === nameIng){
                flag = 1;
                break;
            }
        }
        if(!flag){
            ingredients[i++] = nameIng;
            $('#add').append('<div class="col-sm-2 flexCenter hoverRemove btn_delete " id="" type="button" style="margin-top: 3px"><img src="/assets/'+nameIng+'.png" style="max-width: 90px; max-height: 90px">\n\
            <p class="table_data" data-row_id="" data-column_name="Name" style="vertical-align: text-bottom">'+nameIng+'</p>\n\
            <div type="button" name="delete_btn"  class="btn_delete fa fa-times removeIng" style="color: antiquewhite"></div></div>');
        }
    });
    $(document).on('click', '.btn_delete', function(){
        var nameIng = $(this).text();
        $(this).remove();
        for(var j = 0; j < i; j++)
            if (ingredients[j] === nameIng) 
                ingredients.splice(j, 1);
        i--;
    });
    $(document).on('click', '.searchByIng', function(){
       if(i)
        location.href="<?php echo base_url('Guest/search?myIng='); ?>"+ingredients;
        else alert('No ingredients were selected.');
    });
    });
</script>