<div class="row">
<div class="main-section col-sm-12">
        <div class="head-section">
                <div class="headLeft-section">
                        <div class="headLeft-sub">
                            <select class="btn btn-purple form-control selectpicker" id="select-country" data-live-search="true" >
                                     <?php
                                        foreach($users as $user){
                                            echo '<option id="'.$user->Username.'" class="findMe"><p "type="button" id="'.$user->Username.'">'.$user->Username.'</p></option>';
                                        }
                                     ?>
                                </select>
                        </div>
                </div>
                <div class="headRight-section">
                        <div class="headRight-sub">
                                <h3 id="currentChat"></h3>
                        </div>
                </div>
        </div>
        <div class="body-section">
                <div class="left-section" id ="messageArea" data-mcs-theme="minimal-dark">
                    <ul>
                        <div id = "allChatsList">
                            
                        </div>
                    </ul>
                </div>
                <div class="right-section">
                        <div class="message " id ="messageArea" data-mcs-theme="minimal-dark">
                                <ul>
                                        <div id = "CurrentMessagesArea">
                                            
							
                                        </div> 
                                </ul>
                        </div>
                        <div class="right-section-bottom">
                            <input type ="hidden" name = "idReciever" id="idReciever" value ="undefined">
                            <input type="text" name="content" id = "idContent" placeholder="type here...">
                            <input type="submit"  id="sendMessage" value="Send">
                        </div>
                </div>
        </div>
</div>
</div>
<script type ="text/javascript">
$(document).ready(function(){
    function load_messages(){
        $.ajax({
            url: "<?php echo base_url("User/loadMessages"); ?>",
            dataType: 'json',
            method: "GET",
            success:  function(data) {
                var chats = data.chats;
                var user = data.user;
                localStorage.setItem('myId', data.user.IdU);
                var newHtmlChatList = "";
                var newHtmlMessages = "";
                var messagesHtml = "";
                let i = 0;
               
                /* za pocetno stanje poruka*/
                if(chats.length>0){
                    var firstId;
                    chats[0].messages.forEach(function(message){
                            if(message.IdSender != data.user.IdU) {/*ja saljem poruku*/
                                   if(!message.IdReq){
                                       messagesHtml+='<li class="msg-left"><div class="msg-left-sub"><div class="msg-desc">';
                                       messagesHtml+=message.Content + '</div><small>' + message.CreationDate+ '</small></div></li>';
                                   }
                                   else{
                                       buttonsHtml = "<p><div><button  id = 'approve' class = 'btn'> Approve </button>&nbsp<button class = 'btn' id = 'deny'>Deny</button></p>";
                                       buttonsHtml+="<div id = 'thisrequestId' style = 'display:none'>"+ message.IdReq+"</div></div>";
                                       messagesHtml+='<li class="msg-left"><div class="msg-left-sub"><div class="msg-desc">';
                                       messagesHtml+=message.Content+ buttonsHtml + '</div><small>' ;
                                       messagesHtml+= message.CreationDate+ '</small></div></li>';
                                   }
                             }
                           else{ /* primam poruku*/
                               messagesHtml+='<li class="msg-right"><div class="msg-right-sub"><div class="msg-desc">';
                               messagesHtml+=message.Content + '</div><small>' + message.CreationDate+ '</small></div></li>'; 
                          }
                    });
                    $("#CurrentMessagesArea").html(messagesHtml);
                    $("#idReciever").val(chats[0].user.IdU);
                    chats.forEach(function(chat){
                        if(i == 0){
                            $("#currentChat").html(chat.user.Username);
                            i++;
                            firstId = chat.user.IdU;
                        }
                        var currUser = chat.user;
                        var lastMessageTime = chat.lastMessageTime;
                        var lastMessageContent = chat.lastMessageContent;
                        newHtmlChatList += " <li class='chatList'>";         
                        newHtmlChatList += "<div class='myUserId' style = 'display:none'>"+user.IdU +"</div> ";
                        newHtmlChatList += "<div class='thisUserId' style = 'display:none'>"+currUser.IdU +"</div> ";
                        newHtmlChatList += "<div class='thisUserName' style = 'display:none'>"+currUser.Username +"</div> ";
                        newHtmlChatList +="<div class='neededData' style = 'display:none'>" + JSON.stringify(chat)+"</div>";
                        newHtmlChatList+= "<div><div class='img'><img src='/assets/profile.jpg'  style='border-radius: 50%'></div>  <div class='desc' type = 'button' >";
                        newHtmlChatList+="<small class='time' style='font-size: 9px;'>" + lastMessageTime+ "</small><h5>" + currUser.Username+ "</h5> <small>"+lastMessageContent.slice(0, 15)+ "..."+"</small></div></div></li>";
                    });
                    $("#allChatsList").html(newHtmlChatList);
                    $(".thisUserId").each(function(){
                            if($(this).text() == firstId)
                               $(this).parent().click();
                }); 
                }
                
            }
        });
    }
    load_messages();
    $(document).on('click', '.chatList', function(){
        $(".chatList").css("background", "rgb(46,0,46)");
        $(this).css("background", "rgb(97, 45, 97)");
        var $this = $(this);
        myId = localStorage.getItem('myId');
        messagerId = $this.find('.thisUserId').text();
        $("#idReciever").val(messagerId);
        document.getElementById("idReciever").value = messagerId;
        messagerName = $this.find('.thisUserName').text();
        chat = JSON.parse($this.find('.neededData').text());
        var messagesHtml = "";
        $("#currentChat").html(messagerName);
        chat.messages.forEach(function(message){
             if(message.IdSender != myId) {/*ja saljem poruku*/
                 if(!message.IdReq){
                 messagesHtml+='<li class="msg-left"><div class="msg-left-sub"><div class="msg-desc">';
                 messagesHtml+=message.Content + '</div><small>' + message.CreationDate+ '</small></div></li>';
                }
                 else{
                 buttonsHtml = "<p><div><button class = 'btn' id = 'approve'> Approve </button>&nbsp<button id = 'deny' class = 'btn'>Deny</button></p>";
                 buttonsHtml+="<div id = 'thisrequestId' style = 'display:none'>"+ message.IdReq+"</div></div>";
                 messagesHtml+='<li class="msg-left"><div class="msg-left-sub"><div class="msg-desc">';
                 messagesHtml+=message.Content+ buttonsHtml + '</div><small>' ;
                messagesHtml+= message.CreationDate+ '</small></div></li>';
                  }
            }   
            else{ /* primam poruku*/
                 messagesHtml+='<li class="msg-right"><div class="msg-right-sub"><div class="msg-desc">';
                 messagesHtml+=message.Content + '</div><small>' + message.CreationDate+ '</small></div></li>'; 
            }
            $("#CurrentMessagesArea").html(messagesHtml);
        });
         });
    $(document).on('click', '#sendMessage', function(){
        var idReciever = $("#idReciever").val();
        var content = $("#idContent").val();
        let username = $("#currentChat").text();
        $.ajax({
            url: "<?php echo base_url("User/newMessage");?>",
            method:"POST",
            data: {content:content, idReciever:idReciever},
            success:function(){
                 load_messages();
          }
        });
        $("#currentChat").text(username);
    });
        $(document).on('click', '#approve', function(){
            var idReq = $(this).parent().find("#thisrequestId").text();
            idReq = parseInt(idReq, 10);
            $.ajax({
                url: "<?php echo base_url("Admin/approveRequest");?>",
                method: "GET",
                data: {idReq: idReq},
                success: function(){
                    load_messages();
                }
            });
        });
        $(document).on('click', '#deny', function(){
            let idReq = $(this).parent().find("#thisrequestId").text();
            $.ajax({
                url: "<?php echo base_url("Admin/denyRequest");?>",
                method: "GET",
                data: {idReq: idReq},
                success: function(){
                    load_messages();
                }
            });
        });
        $(document).on('click', '.findMe', function(){
            var usernamePicked = $(this).text();
            $("#currentChat").html(usernamePicked);
            $.ajax({
                url: "<?php echo base_url("User/messagesExist");?>",
                method: "GET",
                data: {username: usernamePicked},
                dataType: "json",
                success: function(data){
                    let myId = data.myId;
                    let otherId = data.userId;
                    if(data.messages.length>0){
                        $(".thisUserId").each(function(){
                            if($(this).text() == otherId)
                                $(this).parent().click();
                        });
                    }
                    else{
                        $("#CurrentMessagesArea").html(" ");
                        $("#idReciever").val(otherId);
                    }
                }
            });
    });
     
});
</script>
