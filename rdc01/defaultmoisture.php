<?php 

include "config.php";
	  $response=array();
      $time;
   $itemname="";
   $product = array("WATER", "CEMOPC", "CEMPPC", "FLYASH", "GGBS", "ULTFNE","ICE"
);
      $newdate="";
       

      if($_REQUEST['o_id'] != null && $_REQUEST['moistureper']!=null && $_REQUEST['uid']!=null  && $_REQUEST['timenow']!=null){
        $value=$_REQUEST['o_id'];
        $moistureper=$_REQUEST['moistureper'];
        $uid=$_REQUEST['uid'];
        $date = $_REQUEST['timenow'];
            
        $sql12345=sqlsrv_query($connection,"SELECT po_details.ITEM_NAME from cu_orders inner 
        join po_details on .po_details.id=cu_orders.PO_ID where cu_orders.id=$value", array(), array( "Scrollable" => 'static' ));
    $data1= sqlsrv_fetch_array($sql12345,SQLSRV_FETCH_ASSOC);
    $itemname=$data1['ITEM_NAME'];

    if (in_array($itemname, $product)){
        $sql1=sqlsrv_query($connection,"UPDATE cu_orders set ACCEPT_BY=$uid,MOISTURE_PER=$moistureper, MOISTURE_CHECK='1' where  id=$value AND MOISTURE_CHECK !=1");
       
        if($sql1){
            $response['status']="1";
            $response['message']="moisture updated !";
        }
        else{
            $response['status']="0";
            $response['message']=$newdate;
        }  
    }
    else{
        $sql=sqlsrv_query($connection,"SELECT actiontime from cu_statuses inner join cu_orders on
        cu_orders.id=cu_statuses.order_id where cu_statuses.order_id=$value and cu_statuses.reach_site=1 and cu_orders.MOISTURE_CHECK!=1
       ", array(), array( "Scrollable" => 'static' ));
       $num=sqlsrv_num_rows($sql);

           if($num>0){
            $data= sqlsrv_fetch_array($sql,SQLSRV_FETCH_ASSOC);
            $datevalneedby=$data['actiontime'];
            $time = date_format($datevalneedby, 'Y-m-d H:i:s');
           $newdate= date('Y-m-d H:i:s',strtotime('+10 minutes',strtotime($time)));
       



           if ( $newdate < $date) {
             
               $sql1=sqlsrv_query($connection,"	 
               Update cu_orders set ACCEPT_BY=$uid,MOISTURE_PER=$moistureper, MOISTURE_CHECK='1' where  id=$value AND MOISTURE_CHECK !=1");
              
               if($sql1){
                   $response['status']="1";
                   $response['message']="moisture updated !";
               }
               else{
                   $response['status']="0";
                   $response['message']="$newdate";
               }  
   

           }
           else{
               $response['status']="0";
               $response['message']="$newdate";
           }
           
           }
           else{
               $response['status']="1";
               $response['message']="moisture updated !";
           }
      
    }

        
   

    
      echo json_encode($response);
      sqlsrv_close($connection);
      }

?>