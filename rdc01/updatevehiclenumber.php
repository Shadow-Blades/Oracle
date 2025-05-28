<?php
	  include("config.php");
 
	  $response=array();



	if($_REQUEST['o_id'] != null && $_REQUEST['vno'] != null ){
			
     $id=$_REQUEST['o_id']; 
	   $address=$_REQUEST['vno']; 

        $sql=sqlsrv_query($connection,"UPDATE cu_orders SET VEHICLE_NUMBER = '$address' WHERE id = $id");

		
        if($sql)
        {
            //$response['status']=1;
            //$response['message']="success";
	
				$response['status']=1;
				$response['message']="Update successfully";
	
		
        }
        else
        {
            $response['status']=0;
            $response['message']="Not Updated !";
			
        }

       echo json_encode($response);
		sqlsrv_close($connection);
	}
	
	
	
	?>
