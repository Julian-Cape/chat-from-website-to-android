<!DOCTYPE html>
<html lang="en-US">

<head>

<meta charset="UTF-8" />

<link href="https://fonts.googleapis.com/css?family=Khula|Poppins:300" rel="stylesheet">
<script src="../js/jquery-3.1.1.min.js"></script>

<style type="text/css">
  body{
    background: none !important;
    font-family: 'Poppins', sans-serif;
  }
</style>

</head>
<body style="overflow-x: hidden; overflow-y: hidden;">

  <div class="panel-body" >       
                
     <textarea readonly type="textfield" id="textchatin" name="textin" class="form-control brave-butt" rows="10"  onfocus="changeBgColorOnFocus(this)"  style="resize: none;"></textarea>

     <textarea type="textfield" id="textchatout" name="textout" class="form-control brave-butt" rows="1" maxlength="300" onfocus="changeBgColorOnFocus(this)" onchange="sendtomobileapp(this)"  style="resize: none;"></textarea>     

  </div>


  <script>
    function changeBgColorOnFocus(idElem){
      idElem.style.border="2px solid #FF0080";
      idElem.style.boxShadow="0px";
    }
    function changeBgColorOnBlur(idElem){
      idElem.style.border="1px solid #FF0080";
    }

    var chattext_global = "";

    function sendtomobileapp(textareaid){

      var chattext_temp = $(textareaid)[0].value;
      chattext_global += "\nCLIENT -";
      chattext_global += chattext_temp;
      $(textareaid)[0].value = '';
      $("#textchatin").html(chattext_global);

      $.ajax({
        type: "POST",
        url: "/php/clientchat_out.php",
        data: "textclient_out="+chattext_temp//,
        //success: function(datareturned){
        //  if(datareturned != "")
       //   {
       //   }
        //}
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
            chattext_global += "\nHOST -";
            chattext_global += datareturned;
            $("#textchatin").html(chattext_global);
          }
        }
      });

    }




  </script>


</body>
</html>