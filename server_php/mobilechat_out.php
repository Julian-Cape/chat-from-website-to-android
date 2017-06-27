<?php

      date_default_timezone_set('UTC');

      $servername="localhost";
      $username=""; //insert your value!
      $password="";//insert your value!
      $databasename="";//insert your value!
      $newtable="mobileapp_service";  


      $textmobile_out = $_POST['textmobile_out'];
      $closeconn = $_POST['closeconn'];

      if(isset($_POST['textmobile_out']))
      {     
        
        $newtextmobile = false;
        $textmobile_prev = "";

        $textmobile_out = test_input_text($textmobile_out);

        //connect to DB
        $conn = new mysqli($servername, $username, $password, $databasename);        

        if($conn->connect_error){
           die("error1: please contact the admin");
        }  

        $sql ="SELECT newtextmobile FROM ".$newtable." LIMIT 1 OFFSET 0";
        $result = $conn->query($sql);        

        if($result->num_rows > 0 )
        {
          while($row=$result->fetch_assoc())
          {
            $newtextmobile = $row["newtextmobile"];
          }
        }
        else
        {
          die("error2: please contact the admin");
        }

        if($newtextmobile == false)
        {
           $sql = "UPDATE ".$newtable." SET textmobile='".$textmobile_out."',newtextmobile=true WHERE serialnum=1";
           $conn->query($sql);
        }
        else
        {
          $sql ="SELECT textmobile FROM ".$newtable." LIMIT 1 OFFSET 0";
          $result = $conn->query($sql);          

          if($result->num_rows > 0 )
          {
            while($row=$result->fetch_assoc())
            {
              $textmobile_prev = $row["textmobile"];
            }
          }
          $textmobile_prev = $textmobile_prev."\n".$textmobile_out;

          $sql = "UPDATE ".$newtable." SET textmobile='".$textmobile_prev."' WHERE serialnum=1";
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
            echo "error3: please contact the admin";
          }


          $conn->close();
        }
      }
      else
      {
        die("error4: please contact the admin");
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