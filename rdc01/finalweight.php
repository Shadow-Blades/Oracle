<?php 

include "config.php";
	  $response=array();
      $roles=array();
   

      if($_REQUEST['oid'] != null && $_REQUEST['weight']!=null && $_REQUEST['receipt']!=null && $_REQUEST['date']!=null ){
        $id=$_REQUEST['oid'];
        $weight=$_REQUEST['weight'];
        $receiptticketid=$_REQUEST['receipt'];
        $date=$_REQUEST['date'];
        $sql=sqlsrv_query($connection,"insert into TransactionCauptureWeight
        (ReceiptTicketID,CaptureWeight,CaptureDate,IsActive,Cu_orderID)
        values
        ('$receiptticketid',$weight,'$date',1,$id)");
       
		
            if(!$sql){
                $response['status']="0";
                $response['message']="not inserted succesfully";
            }
            else{
                echo 
                $response['status']="1";
                $response['message']="inserted successfully";
            }

      echo json_encode($response);                              
    sqlsrv_close($connection);
      }

?>
