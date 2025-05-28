<?php 

include "config.php";
	  $response=array();
      $roles=array();
   

      if($_REQUEST['moisture'] != null && $_REQUEST['oid'] != null  && $_REQUEST['vehiclecond'] != null  && $_REQUEST['userid'] != null ){

        $moistureper=$_REQUEST['moisture'];
        $oid=$_REQUEST['oid'];
        $vehiclecond=$_REQUEST['vehiclecond'];
        $uid=$_REQUEST['userid'];

        $sql=sqlsrv_query($connection,"	 
        update cu_orders set ACCEPT_BY=$uid,MOISTURE_PER=$moistureper, MOISTURE_CHECK='1' ,VEHICLE_CONDITION='$vehiclecond' where  id=$oid");
		
            if(!$sql){
                $response['status']="0";
                $response['message']="not inserted succesfully";
            }
            else{
                $response['status']="1";
                $response['message']="inserted successfully";
            }

      echo json_encode($response);                              
    sqlsrv_close($connection);
      }

?>