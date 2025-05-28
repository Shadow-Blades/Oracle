<?php
  include("config.php");
	  $response=array();
	  $order=array();


	if($_REQUEST['id'] != null){
	
     $id=$_REQUEST['id']; 
 
        $sql=sqlsrv_query($connection,"SELECT DISTINCT cu_statuses.actiontime,cu_orders.id,cu_orders.contact_number,cu_orders.o_from,cu_orders.collector_id,cu_statuses.pick from ((cu_orders INNER JOIN cu_users ON cu_orders.collector_id=$id)INNER JOIN cu_statuses ON cu_statuses.order_id=cu_orders.id) WHERE cu_statuses.complate=0 AND cu_statuses.denied=1", array(), array( "Scrollable" => 'static' ));
		$num=sqlsrv_num_rows($sql);
		
        if($num >0)
        {
            //$response['status']=1;
            //$response['message']="success";
			while($data=sqlsrv_fetch_array( $sql, SQLSRV_FETCH_ASSOC)){
			array_push($order,array("from"=>$data['o_from'],"last"=>$data['actiontime'],"pick"=>$data['pick'],"contact"=>$data['contact_number'],"collector_id"=>$data['collector_id'],"order_id"=>$data['id']));
			//$order=$data;
			}
		
        }
        else
        {
            $response['status']=0;
            $response['message']="You Dont have Any request";
			
        }
	$response['order']=$order;
       echo json_encode($order);
	}
	
    sqlsrv_close($connection);
	
	?>
