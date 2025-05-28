<?php
 include("config.php");
    $response=array();
   
	
	if($_REQUEST['id'] != null){
		    $id=$_REQUEST['id']; 
	$complate=sqlsrv_query($connection,"SELECT COUNT(*)  AS NumberOfcomplate from cu_statuses WHERE collector_id=$id AND complate=1", array(), array( "Scrollable" => 'static' ));
		$comp_count=sqlsrv_num_rows( $complate);
		
		if($comp_count>0){
			while($data=sqlsrv_fetch_array( $complate, SQLSRV_FETCH_ASSOC)){
				$response['complate']=$data['NumberOfcomplate'];
			}
		}
		
		$pending=sqlsrv_query($connection,"SELECT COUNT(*) AS NumberOfcomplate from cu_statuses WHERE collector_id=$id AND complate!=1 AND denied !=1", array(), array( "Scrollable" => 'static' ));
		$pending_count=sqlsrv_num_rows($pending);
		if($pending_count>0){
			while($data=sqlsrv_fetch_array( $pending, SQLSRV_FETCH_ASSOC)){
				$response['pending']=$data['NumberOfcomplate'];
			}
		}
		$denies=sqlsrv_query($connection,"SELECT COUNT(*) AS NumberOfcomplate from cu_statuses WHERE collector_id=$id AND denied=1", array(), array( "Scrollable" => 'static' ));
		$deni_count=sqlsrv_num_rows($denies);
		if($deni_count>0){
			while($data=sqlsrv_fetch_array( $denies, SQLSRV_FETCH_ASSOC)){
				$response['denied']=$data['NumberOfcomplate'];
			}
		}
	
       echo json_encode($response);
	}
	sqlsrv_close($connection);
?>