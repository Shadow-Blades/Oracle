<?php 

include "config.php";
	  $response=array();
      $roles=array();
   
      if($_REQUEST['oid'] != null && $_REQUEST['weight']!=null && $_REQUEST['receipt']!=null ){
        $id=$_REQUEST['oid'];
        $weight=$_REQUEST['weight'];
        $receiptticketid=$_REQUEST['receipt'];
        $last=$_REQUEST['last'];
        $pono=$_REQUEST['ponum'];

        $sitename=$_REQUEST['sitename'];
        $weightbridgeID='';
        $sql1234565789=sqlsrv_query($connection,"UPDATE cu_users set IsActive = 0 where id in (select cu_users.id from cu_users 
        left join cu_statuses on cu_statuses.collector_id=cu_users.id 
        where cu_statuses.actiontime < DATEADD(MINUTE, 330,GETUTCDATE())-30  and cu_users.role_id not in(1,2,3,5,6,7)
		and cu_users.IsActive!=0 and cu_users.id not in (select collector_id as id from cu_statuses where complate!=1 and denied!=1))" , array(), array( "Scrollable" => 'static' ));

        $sql1=sqlsrv_query($connection,"SELECT WeightBridgeID from Weightbridge where LocationName='$sitename'" , array(), array( "Scrollable" => 'static' ));
	    	$num1=sqlsrv_num_rows($sql1);
        if($num1 >0){

            while($data= sqlsrv_fetch_array($sql1,SQLSRV_FETCH_ASSOC)){
                   $weightbridgeID=$data['WeightBridgeID'];
      
          }
          }
          $sql121112=sqlsrv_query($connection," SELECT * from TransactionCauptureWeight where ReceiptTicketID='$receiptticketid'" , array(), array( "Scrollable" => 'static' ));
          $num1num=sqlsrv_num_rows($sql121112);

          if($num1num > 0){

            $response['status']="0";
            $response['message']="not inserted succesfully";
         
    
      }
      else{
        $sql=sqlsrv_query($connection,"IF NOT EXISTS (SELECT * FROM TransactionCauptureWeight WHERE ReceiptTicketID = '$receiptticketid')
        BEGIN
        INSERT INTO TransactionCauptureWeight
        (ReceiptTicketID,CaptureWeight,CaptureDate,IsActive,Cu_orderID,WeightBridgeID)
        VALUES('$receiptticketid',$weight,DATEADD(MINUTE, 330,GETUTCDATE()),1,$id,$weightbridgeID)
        END");
       
	
            if(!$sql){
                $response['status']="0";
                $response['message']="not inserted succesfully";
            }
            else{
                     $sql1211121215=sqlsrv_query($connection,"UPDATE cu_orders set GROSS='$weight' where id = $id" , array(), array( "Scrollable" => 'static' ));

                $response['status']="1";
                $response['message']="inserted successfully";
            }
        if($last=="1"){
            $sql11=sqlsrv_query($connection,"UPDATE cu_statuses set complate=1,actiontime=DATEADD(MINUTE, 330,GETUTCDATE()) where order_id=$id");

            $sql1215566=sqlsrv_query($connection,"INSERT INTO ApiTransactionCauptureWeight
            (ReceiptTicketID,CaptureWeight,CaptureDate,IsActive,Cu_orderID,WeightBridgeID,TransactionType)
            VALUES('$receiptticketid',$weight,DATEADD(MINUTE, 330,GETUTCDATE()),1,$id,$weightbridgeID,'TareWeight')");
        }
        else{

          $sql1215566=sqlsrv_query($connection,"INSERT INTO ApiTransactionCauptureWeight
          (ReceiptTicketID,CaptureWeight,CaptureDate,IsActive,Cu_orderID,WeightBridgeID,TransactionType)
          VALUES('$receiptticketid',$weight,DATEADD(MINUTE, 330,GETUTCDATE()),1,$id,$weightbridgeID,'GrossWeight')");
        }
      }
      echo json_encode($response);                              
    sqlsrv_close($connection);
      }

?>