<?php

      date_default_timezone_set('UTC');

      $servername="localhost";
      $username=""; //insert your value!
      $password="";//insert your value!
      $databasename="";//insert your value!
      $newtable="clientapp_service";  


      $textclient_out = $_POST['textclient_out'];
      $closeconn = $_POST['closeconn'];

      if(isset($_POST['textclient_out']))
      {     
        
        $newtextclient = false;
        $textclient_prev = "";

        $textclient_out = test_input_text($textclient_out);

        //connect to DB
        $conn = new mysqli($servername, $username, $password, $databasename);        

        if($conn->connect_error){
           die("error5: please contact the admin");
        }  

        $sql ="SELECT newtextclient FROM ".$newtable." LIMIT 1 OFFSET 0";
        $result = $conn->query($sql);        

        if($result->num_rows > 0 )
        {
          while($row=$result->fetch_assoc())
          {
            $newtextclient = $row["newtextclient"];
          }
        }
        else
        {
          die("error6: please contact the admin");
        }

        echo $newtextclient;
        if($newtextclient == false)
        {
           $sql = "UPDATE ".$newtable." SET textclient='".$textclient_out."',newtextclient=true WHERE serialnum=1";
           $conn->query($sql);
        }
        else
        {
          $sql ="SELECT textclient FROM ".$newtable." LIMIT 1 OFFSET 0";
          $result = $conn->query($sql);          

          if($result->num_rows > 0 )
          {
            while($row=$result->fetch_assoc())
            {
              $textclient_prev = $row["textclient"];
            }
          }
          $textclient_prev = $textclient_prev."\n".$textclient_out;

          $sql = "UPDATE ".$newtable." SET textclient='".$textclient_prev."' WHERE serialnum=1";
          $conn->query($sql);

        }

       $conn->close();        


      }
      else if(isset($_POST['closeconn']))
      {     

        if($closeconn == true)
        {
          //connect to DB
          $conn = new mysqli($servername, $username, $password, $databasename);  

          $sql = "UPDATE ".$newtable." SET closeconn=true WHERE serialnum=1";
          
          if($conn->query($sql) ===true)
          {
            echo "connection closed";
          }
          else
          {
            echo "error7: please contact the admin";
          }


          $conn->close();
        }
      }
      else
      {
        die("error8: please contact the admin");
      }


  function test_input_text($data_tocheck){
     $data_tocheck = trim($data_tocheck);
     $data_tocheck = trim($data_tocheck,"/");
     $data_tocheck = strip_tags($data_tocheck);
     $data_tocheck = stripslashes($data_tocheck);
     $data_tocheck = stripcslashes($data_tocheck);
     $data_tocheck = htmlspecialchars($data_tocheck);
     $data_tocheck = filter_var($data_tocheck, FILTER_SANITIZE_STRING);
     return $data_tocheck;
  }


?>