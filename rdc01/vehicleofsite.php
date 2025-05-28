<?php 

include "config.php";
	  $response=array();
    $sapid;
   
      $vehiclelist=array();

      if($_REQUEST['sitecode'] != null){
       $id=$_REQUEST['sitecode'];
            
        $sql=sqlsrv_query($connection,"SELECT distinct VEHICLE_NUMBER,vendor.user_fullname from cu_orders inner join  cu_users  as vendor on vendor.id=cu_orders.vendor_id 
        left join po_details on po_details.ID=cu_orders.PO_ID
        left join cu_users as driver on driver.id=cu_orders.driver_id
        left join cu_statuses as stat on stat.order_id=cu_orders.id where po_details.SITENAME='$id'", array(), array( "Scrollable" => 'static' ));
		$num=sqlsrv_num_rows($sql);
        if($num >0){
         
			while($data= sqlsrv_fetch_array($sql,SQLSRV_FETCH_ASSOC)){

            array_push($vehiclelist,array("vno"=>$data['VEHICLE_NUMBER'],"vname"=>$data['user_fullname']));
        }
 
      }
      $response=$vehiclelist;
      echo json_encode($response);
    sqlsrv_close($connection);
      }

?>