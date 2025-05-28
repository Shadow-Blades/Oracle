<?php
 
	  $response=array();
	 
   include "config.php";

	if($_REQUEST['order_id'] != null){
			
				$order_id=$_REQUEST['order_id'];
				$partyname=$_REQUEST['party_name'];
                $party_address=$_REQUEST['party_address'];
                $partycontact=$_REQUEST['party_contact'];
                $total_weight=$_REQUEST['total_weight'];
                $total_volume=$_REQUEST['total_volume'];
                $total_charge=$_REQUEST['totalchartge'];
                $amounttenderes=$_REQUEST['payment'];
                $total_items=$_REQUEST['total_items'];
                
	

                

			$qry="UPDATE cu_orders SET to_address='$party_address', to_mobile='$partycontact',to_name='$partyname',grand_weight='$total_weight',grand_volume='$total_volume'
            ,grand_total='$total_charge',payment='$amounttenderes',no_of_pkg='$total_items' where id='$order_id'";

			$res=mysqli_query($connection,$qry);
			mysqli_query($connection,"update cu_statuses SET complate=1 WHERE order_id=$order_id ");
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
	}
mysqli_close($connection);
?>
 
	
