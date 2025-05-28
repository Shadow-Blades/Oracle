<?php 

include "config.php";
	  $response=array();
      $roles=array();
   

      if($_REQUEST['id'] != null && $_REQUEST['newpass'] != null){




            $id=$_REQUEST['id'];
           
            $newpass=$_REQUEST['newpass'];


            $username="";
            $sql121222=sqlsrv_query($connection,"SELECT user_fullname from cu_users where id=$id"  , array(), array( "Scrollable" => 'static' ));
            $num121211=sqlsrv_num_rows($sql121222);
               
            while($data121= sqlsrv_fetch_array($sql121222,SQLSRV_FETCH_ASSOC)){
            $username=$data121['user_fullname'];
            
            }

    
        $sql=sqlsrv_query($connection,"UPDATE cu_users set user_password='$newpass' ,user_image=' ',user_join_date=DATEADD(MINUTE, 330,GETUTCDATE()) where id=$id");
	
    $rows_affected = sqlsrv_rows_affected( $sql);


    
        if($rows_affected >0){
        

            $response['status']="1";
            $response['message']="Update successfully";
 
      }
      else{
        $response['status']="0";
        $response['message']="Old Password Not match ! ";
        
      }
      
      echo json_encode($response);
    sqlsrv_close($connection);
      }

?>