<?php
		
require_once 'config.php';

// Create or reuse the singleton database instance
$dbInstance = DatabaseConnection::getInstance();
$connection = $dbInstance->getConnection();
	  $response=array();


	if($_REQUEST['name'] != null && $_REQUEST['password'] != null){
		
		 $name=$_REQUEST['name'];
	$pass=$_REQUEST['password'];
     $response=array();
	 $order=array();
	  

        $sql=sqlsrv_query($connection,"SELECT DISTINCT cu_users.id,cu_users.user_address,cu_users.user_fullname,cu_users.user_email,cu_users.user_mobile,cu_users.user_image,cu_orders.driver_id,cu_statuses.pick from ((cu_orders INNER JOIN cu_users ON cu_orders.driver_id=5)INNER JOIN cu_statuses ON cu_statuses.order_id=cu_orders.id)WHERE cu_users.user_email='$name' AND cu_users.user_password='$pass'", array(), array( "Scrollable" => 'static' ));
		$num=sqlsrv_num_rows($sql);
		
        if($num >0)
        {
            $response['status']=1;
            $response['message']="success";
			while($data= sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)){
			$response['name']=$data['user_fullname'];
			$response['mobile']=$data['user_mobile'];
			$response['image']=$data['user_image'];
			$response['address']=$data['user_address'];
			$response['email']=$data['user_email'];
			$response['id']=$data['id'];
			array_push($order,array("pick"=>$data['pick']));
			
				
			}
		
        }
        else
        {
            $response['status']=0;
            $response['message']="Invalid User & Password !";
			
        }
		$complate=sqlsrv_query($connection,"SELECT COUNT(*)  AS NumberOfcomplate from cu_statuses WHERE collector_id=1 AND complate=1", array(), array( "Scrollable" => 'static' ));
		$comp_count=sqlsrv_num_rows($complate);
		if($comp_count>0){
			while($data=sqlsrv_fetch_array($complate, SQLSRV_FETCH_ASSOC)){
				$response['complate']=$data['NumberOfcomplate'];
			}
		}
		
		$pending=sqlsrv_query($connection,"SELECT COUNT(*) AS NumberOfcomplate from cu_statuses WHERE collector_id=1 AND pick =0", array(), array( "Scrollable" => 'static' ));
		$pending_count=sqlsrv_num_rows($pending);
		if($pending_count>0){
			while($data=sqlsrv_fetch_array($pending, SQLSRV_FETCH_ASSOC)){
				$response['pending']=$data['NumberOfcomplate'];
			}
		}
		$denies=sqlsrv_query($connection,"SELECT COUNT(*) AS NumberOfcomplate from cu_statuses WHERE collector_id=1 AND denied=1", array(), array( "Scrollable" => 'static' ));
		$deni_count=sqlsrv_num_rows($denies);
		if($deni_count>0){
			while($data=sqlsrv_fetch_array($denies, SQLSRV_FETCH_ASSOC)){
				$response['denied']=$data['NumberOfcomplate'];
			}
		}
	$response['order']=$order;
       echo json_encode($response);
	   sqlsrv_close($connection);
	}

?>