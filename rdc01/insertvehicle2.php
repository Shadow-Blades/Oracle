<?php 

include "config.php";
	  $response=array();
      $roles=array();
   

      if($_REQUEST['vehicleno'] != null && $_REQUEST['rfifno'] != null && $_REQUEST['RFID']!=null){

        $rfid=$_REQUEST['rfifno'];
        $rfidno=$_REQUEST['RFID'];
        $vno=strtoupper($_REQUEST['vehicleno']);

        $sql1=sqlsrv_query($connection,"SELECT * from vehicle where  RFID like '%$rfid%' or RFID2 like '%$rfid' ", array(), array( "Scrollable" => 'static' ));
		$num=sqlsrv_num_rows($sql1);

    if($num>0){ 
          //sql11=sqlsrv_query($connection,"update vehicle set RFID='$rfid' where VehicleNumber='$vno'");
        
          
      while($data= sqlsrv_fetch_array($sql1,SQLSRV_FETCH_ASSOC)){
        $vehiclenumber=$data['VehicleNumber'];
        $response['status']="0";
        $response['message']="duplication Not Allowed".$vehiclenumber;
        $response['createdid']=$vehiclenumber;

        }
         
    }
    else{
      $sql=sqlsrv_query($connection,"UPDATE vehicle SET $rfidno= '$rfid' ,OnlineSyncTime=DATEADD(MINUTE, 330,GETUTCDATE()) where VehicleNumber='$vno'");
  
          if(!$sql){
              $response['status']="0";
              $response['message']="not Updated succesfully";
          }
          else{
              $response['status']="1";
              $response['message']="Updated successfully";
          }
    }


 

      echo json_encode($response);                              
    sqlsrv_close($connection);
      }

?>