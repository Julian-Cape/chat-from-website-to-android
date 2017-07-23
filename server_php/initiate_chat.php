<?php 

     $servername="localhost";
     $username="";//insert your sql username
     $password="";//insert your sql password
     $databasename="mydb_sqli";
     $newtable="create_chat";

     //connect to DB
     $conn = new mysqli($servername, $username, $password, $databasename);     

     if($conn->connect_error){
        echo "error13: please contact the admin";
     }  


     $sql = "UPDATE ".$newtable." SET chatongoing = true WHERE serialnum = 1";
     if($conn->query($sql)===true)
     {
       echo "new connection";
     }
     else
     {
       echo "error14: please contact the admin";
     }


     $conn->close();

?>
