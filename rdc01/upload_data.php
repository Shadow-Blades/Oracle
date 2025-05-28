<?php
 
	  $response=array();
	 
	include "config.php";

	if($_REQUEST['order_id'] != null && $_REQUEST['challano'] != null){


				
				$driver_name=$_REQUEST['drivername']; 
				$driver_id=$_REQUEST['driver_id'];
				$vehicle_no=$_REQUEST['vehicle_no'];
				$sitename=$_REQUEST['sitename'];
				$netweight=$_REQUEST['netweight'];
				$gross=$_REQUEST['gross'];
				$tare=$_REQUEST['tare'];
				$challan_no=$_REQUEST['challano'];
				$reciept_img=$_REQUEST['reciept_img'];
				// $pkg_details=$_REQUEST['item_boxtype'];
	

                   $filename="IMG".rand().".jpg";

				   
	   file_put_contents("images/".$filename,base64_decode($reciept_img));
			$qry="INSERT INTO cu_order (`collector_id`, `accept_time`, `form_location`, `weight`, `volume`, 
			`width`, `image`, `item_name`, `item_description`, `order_id`,`box_type`) 
			VALUES ( '$collector_id','$timedate','$location','$wegiht','$lbh','-','$filename','$itemname','$item_dec','$order_id','$pkg_details')";
			

			$res=mysqli_query($connection,$qry);
			
			if($res){
				
				
			$response['status']=1;
            $response['message']="success";
			}
			else
			{
			$response['status']=0;
            $response['message']="sorry!";
			}
			echo json_encode($response);
			mysqli_close($connection);
	}
?>
 
	
