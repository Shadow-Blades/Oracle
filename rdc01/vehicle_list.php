<?php 

include "config.php";
	  $response=array();
   
      $users=array();

      if($_REQUEST['id'] != null){
        $userif=$_REQUEST['id'];
    
        $sql=sqlsrv_query($connection,"SELECT cu_orders.VEHICLE_NUMBER, cu_orders.id, cu_orders.driver_id ,cu_users.user_fullname,cu_users.user_mobile from cu_orders inner join cu_users on cu_users.id=cu_orders.driver_id
        where  cu_orders.vendor_id=$userif  and  cu_orders.id  in (select  max(id) from cu_orders
        group by VEHICLE_NUMBER)", array(), array( "Scrollable" => 'static' ));
	    	$num=sqlsrv_num_rows($sql);
        if($num >0){
         
			while($data= sqlsrv_fetch_array($sql,SQLSRV_FETCH_ASSOC)){
        array_push($users,array("status"=>1,"vehiclenumber"=>$data['VEHICLE_NUMBER'],"driverrid"=>$data['driver_id'],"drivername"=>$data['user_fullname'],"drivermobile"=>$data['user_mobile']));
        }
 
      }
      else{ 
        array_push($users,array("status"=>0,"vehiclenumber"=>"noAny"));
      }
      $response=$users;
      echo json_encode($response);
    sqlsrv_close($connection);
      }

?>

