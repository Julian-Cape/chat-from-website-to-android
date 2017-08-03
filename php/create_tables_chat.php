<?php

     $servername="localhost";
      $username="";//insert your sql username
      $password="";//insert your sql password
     $databasename="mydb_sqli";

     $newtable="create_chat";


     //connect to DB
     $conn = new mysqli($servername, $username, $password, $databasename);     

     if($conn->connect_error){
        echo "error9: please contact the admin";
     }  

     $sql = "UPDATE ".$newtable." SET chatongoing = false WHERE serialnum = 1";
     $conn->query($sql);

     $conn->close();

?>