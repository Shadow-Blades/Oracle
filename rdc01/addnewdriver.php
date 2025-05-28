<?php 

include "config.php";
	  $response=array();
      $roleid='4';
      $siteid="";

      if($_REQUEST['uname'] != null && $_REQUEST['umobile'] != null &&  $_REQUEST['email'] != null &&  $_REQUEST['siteid'] != null ){

        $email= $_REQUEST['email'];
        $umobile= $_REQUEST['umobile'];
        $u_name=$_REQUEST['uname'];
        $sitename=$_REQUEST['siteid'];
 

        $sql3=sqlsrv_query($connection,"select id from LocationMaster where Sitename='$sitename'" , array(), array( "Scrollable" => 'static' ));
		$num=sqlsrv_num_rows($sql3);
  
        if ($num >0){
            while($data= sqlsrv_fetch_array($sql3,SQLSRV_FETCH_ASSOC)){
            $siteid=$data['id'];
            }

        }

      
        $sql1=sqlsrv_query($connection,"select * from cu_users where user_mobile='$umobile'" , array(), array( "Scrollable" => 'static' ));
        $sql111=sqlsrv_query($connection,"update cu_users set location_id=$siteid,IsActive='1' where user_mobile='$umobile' ");
		$num=sqlsrv_num_rows($sql1);



        if($num >0){
          $data=sqlsrv_fetch_array($sql1,SQLSRV_FETCH_ASSOC);
         
            $response['status']="2";
            $response['message']="Driver Name is : ".$data['user_fullname'];
        
         
        }
        else{
            $sql=sqlsrv_query($connection,"INSERT INTO cu_users
            (user_fullname
            ,user_email
            ,user_mobile
            ,user_password
            ,user_address
            ,user_image
            ,user_join_date
            ,user_sex
            ,role_id
            ,location_id
            ,IsActive)
      VALUES
            ('$u_name'
            ,'$email'
            ,'$umobile'
            ,'2002'
            ,'2002'
            ,' '
            ,CURRENT_TIMESTAMP
            ,'male'
            ,'$roleid'
            ,'$siteid',
            '1')  ");
        
            if(!$sql){
              $response['status']="0";
              $response['message']="Record does not inserted";
            }
            else{
                $response['status']="1";
                $response['message']="Record inserted successfully";
            }
        }

       
 
      
      
      echo json_encode($response);
    sqlsrv_close($connection);
      }

?>