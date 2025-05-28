<?php 

include "config.php";
	  $response=array();
      $siteid="";
      $pass;

      if($_REQUEST['uname'] != null && $_REQUEST['umobile'] != null &&  $_REQUEST['email'] != null &&  $_REQUEST['siteid'] != null &&  $sitename=$_REQUEST['roleid'] ){
	  $today = date("Y-m-d H:i:s");
        $email= $_REQUEST['email'];
        $umobile= $_REQUEST['umobile'];
        $u_name=$_REQUEST['uname'];
        $sitename=$_REQUEST['siteid'];
        $roleid=$_REQUEST['roleid'];
        if(isset($_REQUEST['pass'])){
          $pass=$_REQUEST['pass'];
        }
        else{
          $pass="2002";
        }
 
 


      
        
        $sql1=sqlsrv_query($connection,"select * from cu_users where  user_email='$email' and role_id=$roleid" , array(), array( "Scrollable" => 'static' ));
		$num=sqlsrv_num_rows($sql1);
        if($num >0){

         //   $sql2=sqlsrv_query($connection, "update cu_users set location_id ='$sitename' ,IsActive='1' where user_mobile='$umobile'" , array(), array( "Scrollable" => 'static' ));
		  //  // $num2=sqlsrv_num_rows($sql1);
      //      if($num2 >0){
      //       $response['status']="1";
      //       $response['mesage']="Record Updated";
      //      }
      //      else{
            $response['status']="0";
            $response['message']="Duplicate Not Allowd";
          //  }
           
        }
        else{
                        $sql=sqlsrv_query($connection,"INSERT INTO cu_users(user_fullname,user_email,user_mobile,user_password,user_address,user_image,user_join_date,user_sex,role_id,location_id,IsActive)VALUES('$u_name','$email','$umobile','$pass' ,'2002',' ','$today','male',$roleid,$sitename,'1')");

        
            if(!$sql){
              $response['status']="0";  
	      $response['message']=$var;
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