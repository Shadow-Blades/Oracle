<?php 

include "config.php";
	  $response=array();
   
   

      if($_REQUEST['vendorid'] != null && $_REQUEST['poid'] != null &&  $_REQUEST['vname'] != null ){

        $v_id= $_REQUEST['vendorid'];
        $po_id= $_REQUEST['poid'];
        $v_name=$_REQUEST['vname'];
        $sql=sqlsrv_query($connection,"UPDATE po_details set VENDOR_NAME='$v_name', IS_MAPPED='true', V_ID=$v_id where AP_SEGMENT1='$po_id'");
	
        if(!$sql){
          $response['status']="0";
          $response['message']="Record does not uodated";
        }
        else{
            $response['status']="1";
            $response['message']="Record uodated successfully";
        }
 
      
      
      echo json_encode($response);
    sqlsrv_close($connection);
      }
