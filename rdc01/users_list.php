<?php 

include "config.php";
	  $response=array();
    $sapid;
   
      $users=array();

      if($_REQUEST['role'] != null && $_REQUEST['location'] != null){
        $rolename=$_REQUEST['role'];
        $location=$_REQUEST['location'];
            if($rolename=="DRIVER" || $rolename=="VENDOR"){
              $sql=sqlsrv_query($connection,"SELECT distinct user_fullname,users.id,users.user_email,cu_userroles.role_name,
              users.user_join_date,users.user_mobile,po_details.AP_SEGMENT1 from cu_users as users 
              inner join cu_userroles on cu_userroles.id=users.role_id 
              left join po_details on po_details.V_ID=users.id
                  left join LocationMaster as loc on loc.id=users.location_id where (cu_userroles.role_name='$rolename' OR cu_userroles.role_name='VENDOR')
                  AND loc.Sitename='$location' AND users.IsActive='1' ", array(), array( "Scrollable" => 'static' ));
          $num=sqlsrv_num_rows($sql);
          
              if($num >0){
               
            while($data= sqlsrv_fetch_array($sql,SQLSRV_FETCH_ASSOC)){
                if($data['AP_SEGMENT1']==null){
                  $sapid="0";
                }
                else{
                  $sapid=$data['AP_SEGMENT1'];
                }
              $datevalneedby=$data['user_join_date']; 
              $date_creationtime = date_format($datevalneedby, 'd-m-Y H:i:s');
              array_push($users,array("username"=>$data['user_fullname'],"userid"=>$data['id'],"vendorid"=>"$sapid","usermobile"=>$data['user_mobile']
              ,"joindate"=>$date_creationtime ,"useremail"=>$data['user_email'],"userrole"=>$data['role_name']
            
            ));
              }
       
            }
            $response=$users;
            echo json_encode($response);
          sqlsrv_close($connection);
            }
            else{
              $sql=sqlsrv_query($connection,"	SELECT  cu_users.user_fullname,cu_users.id,cu_users.user_email,cu_userroles.role_name,
              cu_users.user_join_date,cu_users.user_mobile from cu_users inner join cu_userroles on cu_userroles.id=cu_users.role_id 
              left join LocationMaster on LocationMaster.id=cu_users.location_id 
              where   cu_userroles.role_name='$rolename' and  LocationMaster.Sitename='$location' ", array(), array( "Scrollable" => 'static' ));
          $num=sqlsrv_num_rows($sql);
          
              if($num >0){
               
            while($data= sqlsrv_fetch_array($sql,SQLSRV_FETCH_ASSOC)){
          
                  $sapid="0";
      
            
              $datevalneedby=$data['user_join_date']; 
              $date_creationtime = date_format($datevalneedby, 'd-m-Y H:i:s');
              array_push($users,array("username"=>$data['user_fullname'],"userid"=>$data['id'],"vendorid"=>"$sapid","usermobile"=>$data['user_mobile']
              ,"joindate"=>$date_creationtime ,"useremail"=>$data['user_email'],"userrole"=>$data['role_name']
            
            ));
              }
       
            }
            $response=$users;
            echo json_encode($response);
          sqlsrv_close($connection);
            }

      }

?>