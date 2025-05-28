<?php 

include "config.php";
	  $response=array();
      $roles=array();
      $count=0;
      $fastahg="";
      $camera="";

      if($_REQUEST['location'] != null){
     
   
            
        $location= $_REQUEST['location'];
        $sql=sqlsrv_query($connection," SELECT Weight as 'weight' from WeightBridge where LocationName='$location'" , array(), array( "Scrollable" => 'static' ));
	    	$num=sqlsrv_num_rows($sql);
        if($num >0){
            
			while($data1= sqlsrv_fetch_array($sql,SQLSRV_FETCH_ASSOC)){
                $response['weight']=(int)$data1['weight'];
        }
 
      }
      else {
        $response['weight']="00.0000";
        $response['VPN']="1";
      }

      $sql1=sqlsrv_query($connection," SELECT * from cu_users where id=1" , array(), array( "Scrollable" => 'static' ));
      $num1=sqlsrv_num_rows($sql1);
      if($num >0){
          
    while($data1= sqlsrv_fetch_array($sql1,SQLSRV_FETCH_ASSOC)){
        $response['VPN']=(int)$data1['IsActive'];
      }

    }
     
    $sql123=sqlsrv_query($connection,"SELECT UhfMaster.IpAddress as 'ipaddress' from UhfMaster inner join WeightBridge on WeightBridge.WeightBridgeID=UhfMaster.WeightBridgeID
    where ( WeightBridge.LocationName='$location'  AND UhfMaster.IsRunning=0) AND UhfMaster.IsActive=1", array(), array( "Scrollable" => 'static' ));
$num123=sqlsrv_num_rows($sql123);

if($num123 >0){
  $sql111=sqlsrv_query($connection,"UPDATE  WeightBridge SET Weight='00.00'  WHERE LocationName='$location'" , array(), array( "Scrollable" => 'static' ));
    while($data= sqlsrv_fetch_array($sql123,SQLSRV_FETCH_ASSOC)){
           
     $response['status']="0";
     $response['message']="Fastag Not Working ".$data['ipaddress'];
     $count++;

  }
  }
else{
    $response['status']="1";
    $response['message']="";
}

$sql12=sqlsrv_query($connection,"		SELECT Camera.IPAddress as 'ipaddress'  from Camera inner join WeightBridge on WeightBridge.WeightBridgeID=Camera.WeightBridgeID
where  (WeightBridge.LocationName='$location' AND Camera.IsRunning=0 ) and camera.Active=1 ", array(), array( "Scrollable" => 'static' ));
$num1123=sqlsrv_num_rows($sql12);

    if($num1123 >0 ){
   
        while($data= sqlsrv_fetch_array($sql12,SQLSRV_FETCH_ASSOC)){
               
         $response['status']="0";
         $response['camera']="Camera Not Working:".$data['ipaddress'];

      }
      }
      else{

        $response['status']="0";
        $response['camera']="";
      }




      $sql4444=sqlsrv_query($connection,"SELECT TOP(1) ReaderData as 'rd' from UhfMaster inner join WeightBridge on WeightBridge.WeightBridgeID=UhfMaster.WeightbridgeID 
      where WeightBridge.LocationName='$location' AND UhfMaster.IsRunning=1" , array(), array( "Scrollable" => 'static' ));


while($data= sqlsrv_fetch_array($sql4444,SQLSRV_FETCH_ASSOC)){
  $response['readerData']=$data['rd'];

}
    //   $response=$roles;
      echo json_encode($response);
    sqlsrv_close($connection);
      }

?>