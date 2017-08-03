<?php 

     $servername="localhost";
      $username="";//insert your sql username
      $password="";//insert your sql password
     $databasename="mydb_sqli";
     $newtable="create_chat";


     $tokenmobileval = $_POST['textmobile_out'];

      if(isset($_POST['textmobile_out']))
      {     

       //connect to DB
       $conn = new mysqli($servername, $username, $password, $databasename);       

       if($conn->connect_error){
          echo "error20: please contact the admin";
       }    
  

       $sql = "UPDATE ".$newtable." SET tokenmobile='".$tokenmobileval."' WHERE serialnum=1";

       if($conn->query($sql)===true)
       {
         echo "Data Insert";
       }
       else
       {
         echo "error21: please contact the admin";
       }  

       $conn->close();
     }

?>