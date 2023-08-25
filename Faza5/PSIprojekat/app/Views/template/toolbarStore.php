<!DOCTYPE html>
<!--Dusan Terzic-->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>  
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <script src="/script/script.js"></script>
    <link rel="stylesheet" href="/css/blackout.css">
    <link rel="stylesheet" href="/css/blackoutLogin.css">
    <link rel="stylesheet" href="/css/style.css">
    <link rel ="stylesheet" href ="/css/messages.css">

    <script type="text/javascript">
    window.onload = function()
    {
        timedHide(document.getElementById('cngps'), 2);
    }

    function timedHide(element, seconds)
    {
        if (element) {
            setTimeout(function() {
                element.style.display = 'none';
            }, seconds*1000);
        }
    }
    $(function () {
         h1Top = $("#fixToTop").position().top;
        $(window).scroll(function () {
    if ($(window).scrollTop() > h1Top)
      $("#fixToTop").addClass("fixTop");
    else
      $("#fixToTop").removeClass("fixTop");
  });
  });
</script>
    <title>Blackout</title>
</head>
<body class="dark-purple-drinks">
    <div class="row purple">
        <div class="col-sm-12">
            <img class="center" src="/assets/logo.png" width="18%" >
        </div>
    </div>
    <div class="row purple-gradient" id="fixToTop" style='color: antiquewhite'>    
        <div class="col navbarHover" type="button" onclick="location.href='<?= site_url($controller) ?>'" style='text-align: center;  padding: 20px;'>
            <a href="<?= site_url($controller) ?>"><i class="fa fa-home" aria-hidden="true"></i></a>
        </div>
        <div class="col navbarHover" type="button" onclick="location.href='<?= site_url($controller."/messages") ?>'"style='text-align: center;  padding: 20px;'>
            <a href="<?= site_url($controller."/messages") ?>"><i class="fa fa-envelope" aria-hidden="true"></i></a>
        </div>
        <div class="col navbarHover" type="button" onclick="location.href='<?= site_url($controller."/profile") ?>'"style='text-align: center; padding: 20px;'>
            <a href="<?= site_url($controller."/profile") ?>"><i class="fa fa-user" aria-hidden="true"></i></a>
        </div>
        <div class="col navbarHover" type="button" onclick="location.href='<?= site_url($controller."/logout") ?>'"style='text-align: center; padding: 20px;'>
            <a href="<?= site_url($controller."/logout") ?>"><i class="fa fa-sign-out" aria-hidden="true"></i></a>
        </div>
    </div>
        </div>
    <nav>