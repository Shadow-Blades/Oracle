<?php 

include "config.php";
	  $response=array();
      $roles=array();
   

      if($_REQUEST['d_id'] != null){
        $value=$_REQUEST['d_id'];
            
        $sql=sqlsrv_query($connection," SELECT od.id as 'order_id' from cu_orders as od inner join  cu_statuses 
        on cu_statuses.order_id=od.id 
        where cu_statuses.complate !=1 and cu_statuses.denied !=1 AND od.driver_id=$value" , array(), array( "Scrollable" => 'static' ));
		$num=sqlsrv_num_rows($sql);
        if($num >0){
            $response['status']=0;
            $response['message']="there is transaction active";
        }
        else{
            $response['status']=0;
            $response['message']="ready for transacion";
        }  
      echo json_encode($response);
      sqlsrv_close($connection);
      }

?>

