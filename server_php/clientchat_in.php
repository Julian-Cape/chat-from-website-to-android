<?php

      $servername="localhost";
      $username="";//insert your value! 
      $password="";//insert your value!
      $databasename="";//insert your value!
      $newtable="mobileapp_service";  


      $textclient_in = "";
      $closeconn = false;

      //connect to DB
      $conn = new mysqli($servername, $username, $password, $databasename);        
      if($conn->connect_error){
         die("error17: please contact the admin");
      }  

      $sql ="SELECT textmobile FROM ".$newtable." LIMIT 1 OFFSET 0";
      $result = $conn->query($sql);       

      if($result->num_rows > 0 )
      {
        while($row=$result->fetch_assoc())
        {
          $textclient_in = $row["textmobile"];
        }

        $sql = "UPDATE ".$newtable." SET textmobile='',newtextmobile=false WHERE serialnum=1";
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
        echo $textclient_in."\nCLIENT - close connection";
      }
      else
      {
        echo $textclient_in;
      }

      $conn->close();        


?>