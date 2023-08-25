
    <div class="row">
        <div class="col-sm-2">
            
            <div class="animateGlassLeft"><img src="/assets/Martini glass.png" style="max-height: 700px;"></div>
        </div>
          <div class="col-sm-8" style="text-align: center; color:antiquewhite;">
               <h1 style="font-family: 'Great Vibes', cursive; font-size: 70px;">Welcome back <?php echo $user->Name?></h1>
               
               <div class="row store-text" style="border-radius: 20px;">
                   <div class="col-sm-12">
                      <h1 style="background: rgba(30, 0, 30, 0.7); border-radius: 5px"><b>MY GROCERIES</b></h1>
                   </div>
                   <div class="row" style="text-align: center">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-4"><h3>Add:</h3>        
                        <select class="form-control selectpicker" id="select-country" data-live-search="true" >
                            <?php
                                foreach($ingredients as $ingredient){
                                    echo '<option id="'.$ingredient->Name.'" class="findMe "><p type="button"  id="'.$ingredient->Name.'">'.$ingredient->Name.'</p></option>';
                                }?>
                    </select>
                            
                            <div style="display: block; align-self: center; padding-top: 5px"><input type="text" id="price" placeholder="Price (in euros)"></div>
                        
                        <p id="messagePriceNew"></p></div>

                            <div class="col-sm-4"><button class="btn btn-purple" id="change" style="color: antiquewhite">Change price view</button></div>
                   </div>
                   <div class="row">
                       
                        <h3>Your groceries:</h3> 
                        <div id="possession"></div>
                        <div id="changePrice"></div>
                        <p id="messagePrice"></p>
                   </div>
               </div>
               </br>
                
          </div>
        <div class="col-sm-2">
            <div class="animateGlassRight"><img src="/assets/Martini glass.png" style="max-height: 700px;"></div>
        </div> 
    </div>
<script type="text/javascript">
    var id;
    $(document).ready(function(){
        function load_data(){
            $.ajax({
                url: "<?php echo base_url("Store/loadIngredients");?>",
                dataType: 'json',
                success: function(data){
                    var 
                        html = '<div class="row">'
                        for(var count = 0; count < data[0].length; count++)
                        {
                          html += '<div class="col-sm-2 flexCenter hoverRemove btn_delete" id="'+data[0][count].IdI+'" type="button" style="margin-top: 3px"><img src="/assets/'+data[0][count].Name+'.png" style="max-width: 90px; max-height: 90px">\n\
                                    <p class="table_data" data-row_id="'+data[0][count].IdI+'" data-column_name="Name" style="vertical-align: text-bottom">'+data[0][count].Name+' '+data[1][count]+'€</p>\n\
                                    <div type="button" name="delete_btn"  class="btn_delete fa fa-times removeIng" style="color: antiquewhite"></div></div>';
                        }
                        html += '</div>';
                    $('#possession').html(html);
                }
            });
        }
        load_data();
        $(document).on('click', '.btn_delete', function(){
          var id = $(this).attr('id');
          $.ajax({
            url:"<?php echo base_url('Store/removeIngredient'); ?>",
            method: "POST",
            data: {idIng:id},
            success:function(data){
              load_data();
            }
          })
    });
    
    $(document).on('click', '.findMe', function(){
        var nameIng = $(this).text();
        var price = $('#price').val();
        $.ajax({
          url: "<?php echo base_url("Store/addIngredient");?>",
          method:"POST",
          dataType: 'json',
          data: {name:nameIng, price:price},
          success:function(data){
            if(data!=''){
                $('#messagePriceNew').html(data);
            }else{
                $('#messagePriceNew').html('');
            }
            load_data();
          }
        })
    });
    $(document).on('click', '#change', function(){
        $(this).html('Remove grocery view');
        $(this).attr("id", "remove");
        $('.btn_delete').addClass("hoverChange");
        $('.btn_delete').removeClass("hoverRemove");
        $('.btn_delete').addClass("btn_change");
        $('.btn_delete').removeClass("btn_delete");
        $('.fa-times').addClass("fa-minus");
        $('.fa-times').removeClass("fa-times");
    });
    function change(){
        $('#remove').html('Change price view');
        $('#remove').attr("id", "change"); 
        $('.btn_change').addClass("hoverRemove");
        $('.btn_change').removeClass("hoverChange");
        $('.btn_change').addClass("btn_delete");
        $('.btn_change').removeClass("btn_change");
        $('.fa-minus').addClass("fa-times");
        $('.fa-minus').removeClass("fa-minus");
    }
    $(document).on('click', '#remove', function(){
        change();
    });
    $(document).on('click', '.btn_change', function(){
        id = $(this).attr('id');
        $("#changePrice").html('<input type="text" class="inputPrice" placeholder="Specify the new price">\n\
                                <button class="myNewPrice btn btn-purple">Submit</button>');
    });
    $(document).on('click', '.myNewPrice', function(){
       var priceChange = $('.inputPrice').val();
       $.ajax({
           url: "<?php echo base_url("Store/changePrice");?>",
           method:"GET",
           dataType: 'json',
           data: {id:id, priceChange:priceChange},
           success:function(data){
            if(data==""){
                $("#changePrice").html('');
                $("#messagePrice").html('');
                change();
                load_data();
            }
            else{
                $("#messagePrice").html(data);
            }
          }
       })
    });
    })
</script>

  