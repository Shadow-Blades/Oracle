<?php 

include "config.php";
	  $response=array();
      $id='4';

      if($_REQUEST['orderid'] != null && $_REQUEST['vehicleno'] != null && $_REQUEST['driverid'] != null &&  $_REQUEST['challano'] != null && $_REQUEST['weight'] != null){

        $vehicleno= $_REQUEST['vehicleno'];
        $orderid= $_REQUEST['orderid'];
        $driverid= $_REQUEST['driverid'];
        $challano=$_REQUEST['challano'];
        $weight=$_REQUEST['weight'];
        $challanone=$_REQUEST['challano123'];
        $netone=$_REQUEST['weight12'];
   
     


            $sql=sqlsrv_query($connection,"update cu_orders set NET='$weight' ,VEHICLE_NUMBER='$vehicleno',driver_id=$driverid,CHALAN_NO='$challano',challan_no1='$challanone',NET1='$netone' where id=$orderid");
       
     //  echo "update cu_orders set NET='$weight' ,VEHICLE_NUMBER='$vehicleno',driver_id=$driverid,CHALAN_NO='$challano',challan_no1='$challanone',NET1='$netone' where id=$orderid";
        
            if(!$sql){
              $response['status']="0";
              $response['mesage']="not inserted";
            }
            else{
                $response['status']="1";
                $response['mesage']="record inserted successfully";

                $sql1=sqlsrv_query($connection,"UPDATE cu_statuses set placed=1 ,pick=1,collected=0,complate=0,denied=0,collector_id=$driverid where order_id=$orderid");
            }
        

       
 
      
      
      echo json_encode($response);
    sqlsrv_close($connection);
      }

?>