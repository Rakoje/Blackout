$(document).ready(function(){
    var user = false;
    var admin = false;
    var store = false;
    var bartender = false;

    $("#User").click(function(){
        $("#cat").html('User');
        user = true;
        admin = false;
        store = false;
        bartender = false;
    })

    $("#Admin").click(function(){
        $("#cat").html('Admin');
        user = false;
        admin = true;
        store = false;
        bartender = false;
    })

    $("#Bartender").click(function(){
        $("#cat").html('Bartender');
        user = false;
        admin = false;
        store = false;
        bartender = true;
    })

    $("#Store").click(function(){
        $("#cat").html('Store');
        user = false;
        admin = false;
        store = true;
        bartender = false;
    })

    $("#Login").click(function(){
        if(user){
            window.open("../html/indexUser.html", "_self");
        }   
        else if(admin){
            window.open("../html/indexAdmin.html", "_self");
        }    
        else if(store){
            window.open("../html/indexStore.html", "_self");
        }  
        else if(bartender){
            window.open("../html/indexBartender.html", "_self");
        }  
        else{
            alert("Pick a category");
        }
    })

    $("#signup").click(function(){
        if(user){
            window.open("../html/regUser.html", "_self");
        }   
        else if(admin){
            window.open("../html/regAdmin.html", "_self");
        }    
        else if(store){
            window.open("../html/regStore.html", "_self");
        }  
        else if(bartender){
            window.open("../html/regBartender.html", "_self");
        }  
        else{
            alert("Pick a category first!");
        } 
    })

    $("#regAdmin").click(function(){
        alert("Keep using the system while we review your application. If you are deemed unsuitable for the position, your account will be removed");
        window.open("../html/indexAdmin.html");
    })

    $("#regUser").click(function(){
        window.open("../html/indexUser.html");
    })

    $("#regStore").click(function(){
        alert("Keep using the system while we review your application. If you are deemed unsuitable for the position, your account will be removed");
        window.open("../html/indexStore.html");
    })

    $("#regBar").click(function(){
        window.open("../html/indexBartender.html");
    })
})

