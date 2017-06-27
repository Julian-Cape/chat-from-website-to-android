<?php 

     $servername="localhost";
     $username=""; //insert your value!
     $password="";//insert your value!
     $databasename="";//insert your value!
     $newtable="create_chat";

     //connect to DB
     $conn = new mysqli($servername, $username, $password, $databasename);     

     if($conn->connect_error){
        die("error13: please contact the admin");
     }  


     $sql = "UPDATE ".$newtable." SET chatongoing = true WHERE serialnum = 1";
     if($conn->query($sql)===true)
     {
       echo "new connection";
     }
     else
     {
       die("error14: please contact the admin");
     }


     $conn->close();

?>