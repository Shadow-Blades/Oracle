<?php

include "config.php";
$response=array();

$date = date('m/d/Y h:i:s a', time());
$lastid ;


     $sql=mysqli_query($connection,"INSERT INTO `cu_orders` (`id`, `created_at`, `o_from`, `collector_id`, `o_update_from`, `contact_number`, `to_address`, `to_mobile`, `to_name`, `grand_weight`, `grand_volume`, `grand_total`, `payment`, `no_of_pkg`) VALUES (NULL, '2022-02-11 10:10:55.000000', 'gergegefge', '1', '2', 'egedgdrgdrgdr', 'gdgedgedgedged', 'gegedgedgedg', 'wfegfedgedge', '500', '12', '200', 'done', '1'");
			
		$lastid = mysqli_insert_id($connection);
   
        $sql1=mysqli_query($connection,"INSERT INTO cu_statuses(collected,collector_id,complate,denied,order_id,pick,placed,actiontime)VALUES(0,4,0,0,$lastid,0,1,'2021-07-25 11:30:00')");	
      


		
        if($sql)

        {
		  				
            $response['status']=1;
            $response['message']="success";
			
        }
        else
        {
            $response['status']=0;
            $response['message']=$sql;

        }
  echo json_encode($response);
 
	mysqli_close($connection);
    
?>