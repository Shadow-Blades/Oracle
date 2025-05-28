<?php
 
	  $response=array();

	  require_once 'config.php';

// Create or reuse the singleton database instance
$dbInstance = DatabaseConnection::getInstance();
$connection = $dbInstance->getConnection();

	if($_REQUEST['id'] != null && $_REQUEST['Mobile']!=null ){
	
     $id=$_REQUEST['id']; 

        $sql=sqlsrv_query($connection,"SELECT * FR  OM cu_order_summary WHERE collector_id=$id ORDER BY accept_time DESC ");
		$num=sqlsrv_num_rows($sql);
		$connection->set_charset("utf8");
        if($num >0)
        {
            //$response['status']=1;
            //$response['message']="success";
			while($data= sqlsrv_fetch_array( $sql, SQLSRV_FETCH_ASSOC)){
			array_push($response,array("order_id"=>$data['order_id'],"form_location"=>$data['form_location'],"weight"=>$data['weight'],"item_name"=>$data['item_name'],"accept_time"=>$data['accept_time']));
			//$order=$data;
			}
		
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
