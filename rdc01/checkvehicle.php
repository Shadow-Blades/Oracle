<?php 

include "config.php";
	  $response=array();
      $roles=array();
   

      if($_REQUEST['vehicleno'] != null){
        $value=$_REQUEST['vehicleno'];
            
        $sql=sqlsrv_query($connection,"SELECT od.id as 'order_id',po_details.BILL_TO from cu_orders as od inner join  cu_statuses 
        on cu_statuses.order_id=od.id 
		left join po_details on po_details.id=od.PO_ID
        where cu_statuses.complate !=1 and cu_statuses.denied !=1 AND od.VEHICLE_NUMBER='$value'" , array(), array( "Scrollable" => 'static' ));
		$num=sqlsrv_num_rows($sql);



        if($num >0){
            while($data= sqlsrv_fetch_array($sql,SQLSRV_FETCH_ASSOC)){
            $response['status']=1;
            $response['message']="there is transaction active";
            $response['sitename']=$data['BILL_TO'];
            }
            // $response['sitename']=;
        }
        else{
            $response['status']=0;
            $response['message']="ready for transacion";
            $response['sitename']="";
        }  
      echo json_encode($response);
      sqlsrv_close($connection);
      }

?>
