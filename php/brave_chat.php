<!DOCTYPE html>
<html lang="en-US">

<head>

<meta charset="UTF-8" />

<link href="https://fonts.googleapis.com/css?family=Khula|Poppins:300" rel="stylesheet">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<style type="text/css">
  body{
    background: none !important;
    font-family: 'Poppins', sans-serif;
  }
</style>

</head>
<body style="overflow-x: hidden; overflow-y: hidden;">

  <div id="brave_chat_active" style="display: block;height:510px;border:none;" >       
                

    <div style="width:100%;background:#EFEFEF;padding: 0px;border-radius: 0px;border:none;">

      <textarea readonly type="textfield" id="textchatin" name="textin" class="form-control brave-butt"  style="resize: none;display:block;width: 90%;height: 400px;margin-left:4%;margin-right:6%;margin-bottom:0px;background:#FFFFFF;box-shadow: none;outline: none;border:1px solid #FF6600;padding: 4px;border-radius: 10px;"></textarea>
      <span style="width: 92%;margin-left:4%;margin-right:4%;background:none;color: #FF6600;font-size:13px;font-weight:bold;font-family: 'Khula',sans-serif;">Reply here:</span>
      <textarea type="textfield" id="textchatout" name="textout" class="form-control brave-butt" maxlength="300" style="resize: none;display:block;width: 90%;height: 40px;margin-left:4%;margin-right:6%;margin-top:5px;background:#FFFFFF;box-shadow: none;outline: none;border:1px solid #FF6600;padding: 4px;border-radius: 10px;overflow-y: hidden;"></textarea>
      <button id="chatsendbutton" style="display:block;margin-left:45%;height: 36px;margin-top:5px;background: #EFEFEF; color: #FF6600; border: 1px solid #FF6600;font-weight: bold; box-shadow: none; outline: none;border-radius: 15px;text-align: center;padding: 5px 10px 5px 10px;font-family: 'Khula',sans-serif;cursor: pointer;">SEND</button>

    </div>


  <script>

    var chattext_global = "";

    function sendtomobileapp(){

      var textchatout = $("#textchatout");
      var chattext_temp = textchatout[0].value;
      chattext_global += "\nME -";
      chattext_global += chattext_temp;
      textchatout[0].value = '';
      var textchatin = $("#textchatin");
      textchatin.html(chattext_global);
      textchatin.scrollTop(textchatin[0].scrollHeight);

      $.ajax({
        type: "POST",
        url: "/php/clientchat_out.php",
        data: "textclient_out="+chattext_temp

      });

    }

   
    var check_newdata = -1;
    $(document).ready(

        function(){

         check_newdata = setInterval(function_newdata , 4000); 
       }

    );


    function function_newdata(){
            
        $.ajax({
        type: "POST",
        url: "/php/clientchat_in.php",
        success: function(datareturned){
          if(datareturned != "")
          {
            chattext_global += "\nOPERATOR -";
            chattext_global += datareturned;
            $("#textchatin").html(chattext_global);
          }
        }
      });

    }

   $("#textchatout").bind("keypress", function (e) {
      if (e.keyCode == 13) {
          sendtomobileapp();
          return false;
        }
      });


   $("#chatsendbutton").hover( function(){
      $("#chatsendbutton").css("background", "#FFD1B3");},
     function(){
      $("#chatsendbutton").css("background", "none");}
   );

   $("#chatsendbutton").click(function(){
     sendtomobileapp();
   })

   


  </script>


</body>
</html>