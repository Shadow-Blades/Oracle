<?php
	  include("config.php");
 
	  $response=array();



	if($_REQUEST['id'] != null){
			
     $id=$_REQUEST['id']; 
	   $address=$_REQUEST['address']; 
     preg_match('/Lati:([0-9.-]+)Longi:([0-9.-]+)/', $address, $matches);

     // Extracting latitude and longitude into variables
     $latitude = $matches[1];
     $longitude = $matches[2];
     
        $sql=sqlsrv_query($connection,"UPDATE LocationMaster SET long = '$longitude',lat='$latitude'  WHERE Sitename = '$id'");

		
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
            $response['message']="You Dont have Any request";
			
        }

       echo json_encode($response);
		sqlsrv_close($connection);
	}
	
	
	
	?>
