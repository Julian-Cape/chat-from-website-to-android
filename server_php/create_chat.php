<?php 

     date_default_timezone_set('UTC');

     $servername="localhost";
     $username=""; //insert your value!
     $password="";//insert your value!
     $databasename="";//insert your value!
     $newtable="create_chat";

     $chatongoing = false;

     //connect to DB
     $conn = new mysqli($servername, $username, $password, $databasename);     

     if($conn->connect_error){
        die("error9: please contact the admin");
     }  

     $sql ="SELECT chatongoing FROM ".$newtable." LIMIT 1 OFFSET 0";
     $result = $conn->query($sql);        
     if($result->num_rows > 0 )
     {
       while($row=$result->fetch_assoc())
       {
         $chatongoing = $row["chatongoing"];
       }
     }
     else
     {
       die("error10: please contact the admin");
     }


     if($chatongoing == false)
     {
       echo "0";
     }
     else
     {
       
       $ipclientval;
       (isset( $_SERVER['REMOTE_ADDR'])) ? $ipclientval = $_SERVER['REMOTE_ADDR']: $ipclientval = "unkwnown";
       $ipclientval2; 
       (isset( $_SERVER['HTTP_X_FORWARDED_FOR'])) ? $ipclientval2 = $_SERVER['HTTP_X_FORWARDED_FOR']: $ipclientval2 = "unkwnown";
    
       $todaydate = date("Y/m/d");
       $oneyeardate = date_create(NULL);
       $oneyeardate = date_add($oneyeardate , date_interval_create_from_date_string("+1 year"));
       $oneyeardate = date_format($oneyeardate, "Y/m/d");

       //delete existing table named "mobileapp_service"
       $sql = "DROP TABLE mobileapp_service";      
       $conn->query($sql);
 

       $sql = "CREATE TABLE mobileapp_service (
         serialnum INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
         ipclient VARCHAR(255) NOT NULL,
         ipclient2 VARCHAR(255) NOT NULL,
         textmobile MEDIUMTEXT,
         newtextmobile BOOL,
         closeconn BOOL,
         startdate DATE,
         enddate DATE
       )";    

       $conn->query($sql);

       $sql = "INSERT INTO mobileapp_service (ipclient,ipclient2,textmobile,newtextmobile,closeconn,startdate,enddate) VALUES ('".$ipclientval."','".$ipclientval2."','',false,false,'".$todaydate."','".$oneyeardate."')";
       $conn->query($sql);

       //delete existing table named "clientapp_service"
       $sql = "DROP TABLE clientapp_service";      
       $conn->query($sql);
 
       $sql = "CREATE TABLE clientapp_service (
         serialnum INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
         ipclient VARCHAR(255) NOT NULL,
         ipclient2 VARCHAR(255) NOT NULL,
         textclient MEDIUMTEXT,
         newtextclient BOOL,
         closeconn BOOL,
         startdate DATE,
         enddate DATE
       )";   

       $conn->query($sql);
 

       $sql = "INSERT INTO clientapp_service (ipclient,ipclient2,textclient,newtextclient,closeconn,startdate,enddate) VALUES ('".$ipclientval."','".$ipclientval2."','',false,false,'".$todaydate."','".$oneyeardate."')";
       $conn->query($sql);

       echo "1";
     }

     $conn->close();

?>