<?php 

include "config.php";
	  $response=array();
      $roleid='5';
      $siteid="";

      if($_REQUEST['uname'] != null && $_REQUEST['umobile'] != null &&  $_REQUEST['email'] != null &&  $_REQUEST['siteid'] != null &&  $_REQUEST['password'] != null){

        $email= $_REQUEST['email'];
        $umobile= $_REQUEST['umobile'];
        $u_name=strtoupper($_REQUEST['uname']);
        $sitename=$_REQUEST['siteid'];
        $password=$_REQUEST['password'];
 

        $sql3=sqlsrv_query($connection,"SELECT id from LocationMaster where Sitename='$sitename'" , array(), array( "Scrollable" => 'static' ));
		$num=sqlsrv_num_rows($sql3);

        if ($num >0){
            while($data= sqlsrv_fetch_array($sql3,SQLSRV_FETCH_ASSOC)){
            $siteid=$data['id'];
            }

        }

        
  
        
        $sql1=sqlsrv_query($connection,"select * from cu_users where user_fullname='$u_name' and location_id=$siteid and user_email='$email'" , array(), array( "Scrollable" => 'static' ));
		$num=sqlsrv_num_rows($sql1);
        if($num >0){

            $sql2=sqlsrv_query($connection, "UPDATE cu_users set location_id ='$siteid' ,user_mobile='$umobile',user_fullname='$u_name',IsActive='1',user_password='$password',user_image='First' where user_fullname='$u_name' " , array(), array( "Scrollable" => 'static' ));
        
           if($num >0){
            $response['status']="1";
            $response['mesage']="Record Updated";
           }
           else{
            $response['status']="0";
            $response['mesage']="Duplicate Not Allowd";
           }
           
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
            ,'$password'
            ,'2002'
            ,'First'
            ,CURRENT_TIMESTAMP
            ,'male'
            ,'$roleid'
            ,'$siteid',
            '1') ; SELECT @@IDENTITY as id; ");
                $next_result = sqlsrv_next_result($sql); 
                $row = sqlsrv_fetch_array($sql); 
                $id=$row["id"];
        
            if(!$sql){
              $response['status']="0";
              $response['mesage']="Record does not inserted";
              $response["createdid"]="0";
           
            }
            else{
                $response['status']="1";
                $response['mesage']="Record inserted successfully";
                $response["createdid"]="$id";
            }
        }

       
 
      
      
      echo json_encode($response);
    sqlsrv_close($connection);
      }

?>