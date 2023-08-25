
<div class="container txt-style">
    </br>
    <?php
        if(isset($user)){
            echo '<div class="row">
                    <div class="col-sm-12">
                        <h2>Search cocktail by name:</h2>
                        <div class="row">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-4">
                                <select class="form-control selectpicker" id="select-country" data-live-search="true" style="background-color: black !important;">';
                                        foreach($recipes as $recipe){
                                            echo '<option id="'.$recipe->Name.'" class="findMe"><div type="button" id="ingr" >'.$recipe->Name.'</div></option>';
                                        }
                        echo    '</select>
                        </div>
                        <div class="col-sm-4"></div>
                        </div>
                        </div>
                 </div></br>';
        }
    ?>
    <div class = "row">
      <div class = "col-sm-3">
        <div class="dropdown">

          <button class="btn btn-dark dropdown-toggle" style ="background-color: purple" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Sort results by
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" id ="sortRating" href="#">Rating</a>
            <a class="dropdown-item" id = "sortAlpha"href="#">Alphabetical</a>
            <a class="dropdown-item" id = "sortNum" href="#">Number of ingredients</a>
            <a class="dropdown-item" id = "sortPrepTime"href="#">Prep time</a>
            <a class="dropdown-item" id = "sortStrength"href="#">Strength</a>
            <a class="dropdown-item" id = "sortCreation"href="#">Creation date</a>
          </div>
        </div>
      </div>
      <div class ="col-sm-9 ">
          <div class = "float-start">
          <input type="radio" id="ascending" name="order" value="ascending" checked>
          <label for="ascending">Ascending</label>
          <input type="radio" id="descending" name="order" value="descending">
          <label for="descending">Descending</label>
          </div>
      </div>

    </div>
        <div class = "row">
            <div class = "col-sm-12">
                <h2>Cocktails you can make:</h2>

                <div class ="row" id = "makeable">
                
                </div>
            </div>
            <div class = "row">
            <div class = "col-sm-12">
                <h2>Results missing 3 or less ingredients:</h2>
                <div class ="row" id="makeableMissing">

                  </br>
                  </div>
            </div>
            </div>
            <?php
                if(isset($user)){
                    echo '<div class="row">
                            <div class="col-sm-12">
                                <h2>Missing ingredients? Check out stores near you</h2>
                                <div class="mapouter"><div class="gmap_canvas"><iframe width="100%" height="500" id="gmap_canvas" src="https://maps.google.com/maps?q=grocery%20stores%20near%20'.$address.'&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="yes" marginheight="0" marginwidth="0"></iframe></div></div>
                            </div>
                         </div>';
                }
            ?>
        </div>
</div>

    <script type ="text/javascript">
        $(document).ready(function(){
            var makeable = <?php echo json_encode($makeable) ?>;
            var makeableMissing = <?php echo json_encode($makeableMissing);?>;
            load_data(makeable, makeableMissing);
            $(document).on('click', '.findMe', function(){
                var nameRec = $(this).text();
                location.href = "<?php echo base_url($controller."/viewCocktail?rn=");?>"+nameRec;
            });
            function load_data(makeable, makeableMissing){
                var html = "";
                if(makeable!=null){
                    for(var recipe of makeable){
                        html+='<div class="col-sm-4">';
                        html+= '<a href="viewCocktail?rn='+recipe.Name+'"><img src="/assets/'+recipe.Name+'.jpg" style="width: 100%; border-radius: 5px;"></a>';
                        html+='<p>'+recipe.Name+'</p></div>';
                    }
                }   
                else{
                         html+="<h4>No results to show.</h4>";
                }
                $("#makeable").html(html);
                var html = "";
                if(makeableMissing!=null){
                    for(var recipe of makeableMissing){
                        html+='<div class="col-sm-4">';
                        html+= '<a href="viewCocktail?rn='+recipe.Name+'"><img src="/assets/'+recipe.Name+'.jpg" style="width: 100%; border-radius: 5px;"></a>';
                        html+='<p>'+recipe.Name+'</p></div>';
                    }
                }   
                else{
                         html+="<h4>No results to show.</h4>";
                }
                $("#makeableMissing").html(html);
            }
            $("#sortRating").click(function(){
                if(makeable!=null)
                    var n = Object.keys(makeable).length;
                if(makeableMissing!=null)
                    var m = Object.keys(makeableMissing).length;
                var order = $("input[name='order']:checked").val();
                for(let i = 0; i<n; i++){
                    for(let j = i+1; j<n; j++){
                        if((makeable[i].Rating<makeable[j].Rating && order=="descending")||(makeable[i].Rating>makeable[j].Rating && order=="ascending")){
                            let temp = makeable[i];
                            makeable[i] = makeable[j];
                            makeable[j] = temp;
                        }
                    }
                }
                for(let i = 0; i<m; i++){
                    for(let j = i+1; j<m; j++){
                        if((makeableMissing[i].Rating<makeableMissing[j].Rating && order=="descending")||(makeableMissing[i].Rating>makeableMissing[j].Rating && order=="ascending")){
                            let temp = makeableMissing[i];
                            makeableMissing[i] = makeableMissing[j];
                            makeableMissing[j] = temp;
                        }
                    }
                }
                load_data(makeable, makeableMissing);
            });
            $("#sortAlpha").click(function(){
                if(makeable!=null)
                    var n = Object.keys(makeable).length;
                if(makeableMissing!=null)
                    var m = Object.keys(makeableMissing).length;
                var order = $("input[name='order']:checked").val();
                for(let i = 0; i<n; i++){
                    for(let j = i+1; j<n; j++){
                        if((makeable[i].Name.localeCompare(makeable[j].Name)>0 && order=="ascending")||(makeable[i].Name.localeCompare(makeable[j].Name)<0  && order=="descending")){
                            let temp = makeable[i];
                            makeable[i] = makeable[j];
                            makeable[j] = temp;
                        }
                    }
                }
                for(let i = 0; i<m; i++){
                    for(let j = i+1; j<m; j++){
                        if((makeableMissing[i].Name.localeCompare(makeableMissing[j].Name)>0 && order=="ascending")||(makeableMissing[i].Name.localeCompare(makeableMissing[j].Name)<0  && order=="descending")){
                            let temp = makeableMissing[i];
                            makeableMissing[i] = makeableMissing[j];
                            makeableMissing[j] = temp;
                        }
                    }
                }
                load_data(makeable, makeableMissing);
            });
            $("#sortNum").click(function(){
                $.ajax({
                    url: "<?php echo base_url("User/sortByNum"); ?>",
                    dataType: 'json',
                    data: {makeable: JSON.stringify(makeable), order: $("input[name='order']:checked").val(), makeableMissing: JSON.stringify(makeableMissing)},
                    method: "GET",
                    success: function(data){
                        
                        load_data(data.makeable, data.makeableMissing);
                    }
                });
            });
            $("#sortCreation").click(function(){
               if(makeable!=null)
                    var n = Object.keys(makeable).length;
                if(makeableMissing!=null)
                    var m = Object.keys(makeableMissing).length;
                var order = $("input[name='order']:checked").val();
                for(let i = 0; i<n; i++){
                    for(let j = i+1; j<n; j++){
                        let dateI = new Date(makeable[i].CreationDate);
                        let dateJ = new Date(makeable[j].CreationDate);
                        if((dateI.getTime()>dateJ.getTime() && order=="ascending")||(dateI.getTime()<dateJ.getTime() && order=="descending")){
                            let temp = makeable[i];
                            makeable[i] = makeable[j];
                            makeable[j] = temp;
                        }
                    }
                }
                for(let i = 0; i<m; i++){
                    for(let j = i+1; j<m; j++){
                        let dateI = new Date(makeableMissing[i].CreationDate);
                        let dateJ = new Date(makeableMissing[j].CreationDate);
                        if((dateI.getTime()>dateJ.getTime() && order=="ascending")||(dateI.getTime()<dateJ.getTime() && order=="descending")){
                            let temp = makeableMissing[i];
                            makeableMissing[i] = makeableMissing[j];
                            makeableMissing[j] = temp;
                        }
                    }
                }
                load_data(makeable, makeableMissing);
            });
            $("#sortPrepTime").click(function(){
                if(makeable!=null)
                    var n = Object.keys(makeable).length;
                if(makeableMissing!=null)
                    var m = Object.keys(makeableMissing).length;
                var order = $("input[name='order']:checked").val();
                for(let i = 0; i<n; i++){
                    for(let j = i+1; j<n; j++){
                        if((makeable[i].PrepTime<makeable[j].PrepTime && order=="ascending")||(makeable[i].PrepTime>makeable[j].PrepTime && order=="descending")){
                            let temp = makeable[i];
                            makeable[i] = makeable[j];
                            makeable[j] = temp;
                        }
                    }
                }
                for(let i = 0; i<m; i++){
                    for(let j = i+1; j<m; j++){
                        if((makeableMissing[i].PrepTime<makeableMissing[j].PrepTime && order=="ascending")||(makeableMissing[i].PrepTime>makeableMissing[j].PrepTime && order=="descending")){
                            let temp = makeableMissing[i];
                            makeableMissing[i] = makeableMissing[j];
                            makeableMissing[j] = temp;
                        }
                    }
                }
                load_data(makeable, makeableMissing);
            });
            $("#sortStrength").click(function(){
                if(makeable!=null)
                    var n = Object.keys(makeable).length;
                if(makeableMissing!=null)
                    var m = Object.keys(makeableMissing).length;
                var order = $("input[name='order']:checked").val();
                for(let i = 0; i<n; i++){
                    for(let j = i+1; j<n; j++){
                        if((makeable[i].Strength<makeable[j].Strength && order=="descending")||(makeable[i].Strength>makeable[j].Strength && order=="ascending")){
                            let temp = makeable[i];
                            makeable[i] = makeable[j];
                            makeable[j] = temp;
                        }
                    }
                }
                for(let i = 0; i<m; i++){
                    for(let j = i+1; j<m; j++){
                        if((makeableMissing[i].Strength<makeableMissing[j].Strength && order=="descending")||(makeableMissing[i].Strength>makeableMissing[j].Strength && order=="ascending")){
                            let temp = makeableMissing[i];
                            makeableMissing[i] = makeableMissing[j];
                            makeableMissing[j] = temp;
                        }
                    }
                }
                load_data(makeable, makeableMissing);
            });
        });
    </script>

