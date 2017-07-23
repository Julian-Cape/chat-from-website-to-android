<?php

      $servername="localhost";
      $username="";//insert your sql username 
      $password="";//insert your sql password 
      $databasename="mydb_sqli";
      $newtable="clientapp_service";  

      $textmobile_in = "";
      $closeconn = false;

      //connect to DB
      $conn = new mysqli($servername, $username, $password, $databasename);        
      if($conn->connect_error){
         die("error19: please contact the admin");
      }  

      $sql ="SELECT textclient FROM ".$newtable." LIMIT 1 OFFSET 0";
      $result = $conn->query($sql);       

      if($result->num_rows > 0 )
      {
        while($row=$result->fetch_assoc())
        {
          $textmobile_in = $row["textclient"];
        }

          $sql = "UPDATE ".$newtable." SET textclient='',newtextclient = false WHERE serialnum = 1";
          $conn->query($sql);
      }
      else
      {
        //die("error18: please contact the admin");
      }


      $sql ="SELECT closeconn FROM ".$newtable." LIMIT 1 OFFSET 0";
      $result = $conn->query($sql);

      
      if($result->num_rows > 0 )
      {
        while($row=$result->fetch_assoc())
        {
          $closeconn = $row["closeconn"];
        }
      }

      if($closeconn === true)
      {
        echo $textmobile_in."\nHOST - close connection";
      }
      else
      {
        echo $textmobile_in;
      }

      $conn->close();        


?>
