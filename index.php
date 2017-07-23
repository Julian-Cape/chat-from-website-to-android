    <?php   


      $servername="localhost";
      $username="";//insert here sql username 
      $password="";//insert here sql password
      $databasename="mydb_sqli";
      $newtable="create_chat";  

      $chat_already_on = false;  

       //connect to DB
       $conn = new mysqli($servername, $username, $password, $databasename);       

       if($conn->connect_error){
          echo "error9: please contact the admin";
       }    

       $sql ="SELECT chatongoing FROM ".$newtable." LIMIT 1 OFFSET 0";
       $result = $conn->query($sql);        
       if($result->num_rows > 0 )
       {
         while($row=$result->fetch_assoc())
         {
           $chat_already_on = $row["chatongoing"];
         }
       }
       else
       {
         echo "error10: please contact the admin";
       }


    ?>

    <div id="brave_chat_frame" style="<?php if($chat_already_on == true){echo 'display:none;';}?>position: fixed;right: 5px;background:#EFEFEF;border:none;border-top:3px solid #FF6600;z-index: 101;padding: 0px">

      <div id="brave_chat_header"  style="background:#EFEFEF;border:none;border-left: 1px solid #FF6600;border-radius: 0px;">

        <span id="chathidebutton" class="glyphicon glyphicon-menu-right" style="display:inline-block; float:left;margin-top:5px;margin-left:4%;background: #EFEFEF;color: #FF6600;border: 1px solid #FF6600;box-shadow: none;outline: none;text-align: center;cursor: pointer;font-size: 16px;padding: 6px;"></span>  

        <button id="chatclosebutton" style="float:right;margin-top:2px;background: none; color: #FF6600; border: none;font-weight: bold;font-size: 15px; box-shadow: none; outline: none;font-family: 'Khula',sans-serif;cursor: pointer;text-decoration: underline;">Close</button>
        <span id="chatopenbutton_frame" style="background: none;color: #FF6600;border:none;box-shadow: none;outline: none;text-align: left;font-weight:bold;cursor: pointer;font-size: 16px;"><span id="chatopenbutton" style="text-decoration: underline;">CHAT HERE</span><span id="chatmoreinfo"> FOR MORE INFO</span></span>

      </div>

      <iframe id="brave_chat" src="/php/brave_chat.php" width="100%" name="brave_chat" frameborder="0" scrolling="auto" style="display: none;border:none;border-left:1px solid #FF6600;"></iframe>




    </div>
    
    
    <script type="text/javascript">

   function closemobileapp(){

    $.ajax({
        type: "POST",
        url: "/php/create_tables_chat.php",
      });

    $("#brave_chat_frame").remove();

   }

   $("#chatclosebutton").click(function(){
     closemobileapp();

   });

   var chathidden = false;
   var chatopened = false;
   $("#chathidebutton").click(function(){
     if(chathidden)
     {

       $("#chatclosebutton").show();
       $("#chatopenbutton_frame").show();

       $("#brave_chat_header").css({"background":"#EFEFEF","border-top":"3px solid #FF6600","border-left":"1px solid #FF6600"});
       $("#brave_chat_frame").css({"background":"#EFEFEF"});
       $("#chathidebutton").css({"padding":"6px","float":"left"});
       $("#chathidebutton").removeClass("glyphicon-menu-left");
       $("#chathidebutton").addClass("glyphicon-menu-right");

       if(chatopened)
       {
         var actualchatpos = document.body.scrollTop;
         //actualchatpos += $("#brave_chat_frame")[0].offsetTop;
         actualchatpos += 20;
         var actualchatpos_str = actualchatpos.toString() + 'px';
         $("#brave_chat_frame").css({"position":"absolute" , "top" : actualchatpos_str});
         $("#brave_chat").css("display","inline");
       }
       
       chathidden = false;
     }
     else
     {
       $("#chatclosebutton").hide();
       $("#chatopenbutton_frame").hide();
       $("#brave_chat_header").css({"background":"none","border":"none"});

       $("#brave_chat_frame").css({"background":"none","border":"none"});
       $("#chathidebutton").css({"padding":"10px","float":"right"});
       $("#chathidebutton").removeClass("glyphicon-menu-right");
       $("#chathidebutton").addClass("glyphicon-menu-left");

       if(chatopened)
       {
         $("#brave_chat_frame").css({"position":"fixed","top" : "5%"});
         $("#brave_chat").css("display","none");
       }
       
       chathidden = true;
     }
   });
 
   $("#chatopenbutton").click(function(){
    var actualchatpos = document.body.scrollTop;
    //actualchatpos += $("#brave_chat_frame")[0].offsetTop;
    actualchatpos += 20;
    var actualchatpos_str = actualchatpos.toString() + 'px';
    $("#brave_chat_frame").css({"position":"absolute" , "top" : actualchatpos_str, "height":"100px"});
    
    $("#chatclosebutton").show();

    $("#brave_chat").css("display","inline");
    $("#chatclosebutton").css({"position":"absolute" , "margin-left" : "200px"});
    $("#chatopenbutton").unbind("click");

    $.ajax({
        type: "POST",
        url: "/php/initiate_chat.php"
      });


    chatopened = true;
   });


   $("#chathidebutton").hover( function(){
      $("#chathidebutton").css("background", "#FFD1B3");},
     function(){
      $("#chathidebutton").css("background", "#EFEFEF");}
   );


   function closingCode(){
      $.ajax({
        type: "POST",
        url: "/php/create_tables_chat.php"
      });
      return null;
   }
     
   <?php if($chat_already_on == false){echo 'window.onbeforeunload = closingCode;';}?> 
 

</script>
