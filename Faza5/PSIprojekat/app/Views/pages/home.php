
    <div class="row">
        <div class="col-sm-2">
            
            <div class="animateGlassLeft"><img src="/assets/Martini glass.png" style="max-height: 700px;"></div>
        </div>
          <div class="col-sm-8" style="text-align: center; color:antiquewhite;">
              <h1 style="font-family: 'Great Vibes', cursive; font-size: 70px;">Make your favourite cocktail at home</h1>
           

                <h1 style="font-family: 'Great Vibes', cursive; font-size: 50px;">Featured</h1>
                <div class="slideshow-container" style="font-family: 'Great Vibes', cursive; color:antiquewhite;">
                 <?php
                     echo '
                <div class="mySlides fade">
                  <a href="'.$controller.'/viewCocktail?rn=Sloe gin Negroni"><img src="/assets/Sloe gin Negroni.jpg" style="width:60%;  border-radius: 20px">
                  <div class="text-rec">Sloe gin Negroni</div></a>
                </div>

                <div class="mySlides fade">
                  <a href="'.$controller.'/viewCocktail?rn=Mojito"><img src="/assets/Mojito.jpg" style="width:60%; border-radius: 20px">
                  <div class="text-rec">Mojito</div></a>
                </div>

                <div class="mySlides fade">
                    <a href="'.$controller.'/viewCocktail?rn=Blush martini"><img src="/assets/Blush martini.jpg" style="width:60%;  border-radius: 20px">
                  <div class="text-rec">Blush martini</div></a>
                </div>

                <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                <a class="next" onclick="plusSlides(1)">&#10095;</a>
                

                </div>
                <br>

                <div style="text-align:center">
                  <span class="dot" onclick="currentSlide(1)"></span> 
                  <span class="dot" onclick="currentSlide(2)"></span> 
                  <span class="dot" onclick="currentSlide(3)"></span> 
                </div>';
                 
                 ?>
          </div>
        
        <div class="col-sm-2">
            <div class="animateGlassRight"><img src="/assets/Martini glass.png" style="max-height: 700px;"></div>
        </div>
        </div>

    </div>
<script type="text/javascript">
var slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1}    
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";  
  }
  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " active";
}
</script>
    
   
  