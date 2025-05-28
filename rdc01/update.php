<?php
  include("config.php");
    $response=array();
   

    if(isset($_REQUEST['o_id'])&& isset($_REQUEST['c_id'])) 
    {
		$var=$_REQUEST['o_id'];
		$var1=$_REQUEST['c_id'];
		$var4=$_REQUEST['lastaction'];
		$var2=$_REQUEST['picked'];
		$var3=$_REQUEST['denied'];
		if($var2=="true"){
       
		$sql=sqlsrv_query($connection,"UPDATE cu_statuses SET pick=1,denied=0,actiontime='$var4' WHERE order_id=$var");
		if(!$sql){
			$response['status']=0;
            $response['message']="Please try again";
		}
		else{
		$response['status']=1;
            $response['message']="success";
		}
        
      
    }
	else{
		$sql=sqlsrv_query($connection,"UPDATE cu_statuses SET pick=0 , denied=1,actiontime=DATEADD(MINUTE, 330,GETUTCDATE()) WHERE order_id=$var ");
		if(!$sql){
			$response['status']=0;
            $response['message']="Please try again";
		}
		else{
			$sql121555=sqlsrv_query($connection,"UPDATE TransactionData SET Status='Void',OnlineSyncTime=DATEADD(MINUTE, 330,GETUTCDATE()) WHERE Cu_orderID=$var");
			$sql1215555151=sqlsrv_query($connection,"UPDATE cu_orders set ACCEPT_BY='$var1' where id=$var");
		$response['status']=1;
        $response['message']="success";
		}
	}
echo json_encode($response);
sqlsrv_close($connection);
	}

?>