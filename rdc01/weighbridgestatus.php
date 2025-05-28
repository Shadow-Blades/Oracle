<?php 

include "config.php";
	  $response=array();
      $roles=array();
   $count=0;

      if($_REQUEST['loction'] != null){


        $location=$_REQUEST['loction'] ;
      $sql12112=sqlsrv_query($connection,"UPDATE UhfMaster set ReaderData='' where ID=(	SELECT TOP(1) UhfMaster.ID as 'ID' from UhfMaster inner join WeightBridge on WeightBridge.WeightBridgeID=UhfMaster.WeightbridgeID 
                    where WeightBridge.LocationName='$location' AND UhfMaster.IsRunning=1)" , array(), array( "Scrollable" => 'static' ));
        $sql=sqlsrv_query($connection,"SELECT UhfMaster.IpAddress as 'ipaddress' from UhfMaster inner join WeightBridge on WeightBridge.WeightBridgeID=UhfMaster.WeightBridgeID
                where ( WeightBridge.LocationName='$location'  AND UhfMaster.IsRunning=0) AND UhfMaster.IsActive=1", array(), array( "Scrollable" => 'static' ));
		$num=sqlsrv_num_rows($sql);
 
            if($num >0){
              $sql1=sqlsrv_query($connection,"UPDATE  WeightBridge SET Weight=00.00  WHERE LocationName='$location'" , array(), array( "Scrollable" => 'static' ));
                while($data= sqlsrv_fetch_array($sql,SQLSRV_FETCH_ASSOC)){
                       
                 $response['status']="0";
                 $response['message']="Fastag Not Working ".$data['ipaddress'];
                 $count++;

              }
              }
            else{
                $response['status']="1";
                $response['message']="";
            }

            $sql12=sqlsrv_query($connection,"SELECT Camera.IPAddress as 'ipaddress'  from Camera inner join WeightBridge on WeightBridge.WeightBridgeID=Camera.WeightBridgeID
            where  (WeightBridge.LocationName='$location' AND Camera.IsRunning=0 ) and camera.Active=1 ", array(), array( "Scrollable" => 'static' ));
            $num1=sqlsrv_num_rows($sql12);
     
                if($num1 >0 && $count==0){
               
                    while($data= sqlsrv_fetch_array($sql12,SQLSRV_FETCH_ASSOC)){
                           
                     $response['status']="0";
                     $response['message'].="Camera Not Working:".$data['ipaddress'];
    
                  }
                  }
    
            

      echo json_encode($response);                              
    sqlsrv_close($connection);
      }

?>