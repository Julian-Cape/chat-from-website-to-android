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

       //send notification to Client App !!!
       $sql ="SELECT tokenmobile FROM ".$newtable." LIMIT 1 OFFSET 0";
       $result = $conn->query($sql);        

       if($result->num_rows > 0 )
       {
         while($row=$result->fetch_assoc())
         {
           $tokenmobileval = $row["tokenmobile"];
         } 
       }

       //START send notification to Client App !!!
       $url_fcm = "https://fcm.googleapis.com/fcm/send";  
       $brave_server = /*insert here your server key*/;

       $headr = array();
       $headr[] = 'Content-type: application/json';
       $headr[] = 'Authorization: key='.$brave_server;

       $notification_in = array
          (
          'body'  =>  'New chat opened!',
          'title' =>  'Brave Chat',
          'icon'  =>  'brave_launcher.png',
          'sound' =>  'default'
          );
       $data_payload = array
          (
          'newchat'  =>  '1',
          );


       $data_in = array(
            'notification'  =>  $notification_in,
            'data'          =>  $data_payload,
            'to'            =>  $tokenmobileval,
            'priority'      => 'high'
            
        );

       $curl_content = json_encode($data_in);  

       $curl_in = curl_init();
       curl_setopt($curl_in, CURLOPT_URL, $url_fcm);
       curl_setopt($curl_in, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($curl_in, CURLOPT_POST, 1);
       curl_setopt($curl_in, CURLOPT_HTTPHEADER, $headr);
       curl_setopt($curl_in, CURLOPT_POSTFIELDS, $curl_content);       

       $json_response = curl_exec($curl_in);       

       $curl_status = curl_getinfo($curl_in, CURLINFO_HTTP_CODE);       

       if ( $curl_status != 201 ) {
           echo("Error: call to URL $url failed with status $curl_status, response $json_response, curl_error " . curl_error($curl_in) . ", curl_errno " . curl_errno($curl_in));
       }       
       
       curl_close($curl_in);       


       $response = json_decode($json_response, true);
       echo $response;

       //END send notification to Client App !!!

     }
     else
     {
       echo "error14: please contact the admin";
     }

     $conn->close();

?>