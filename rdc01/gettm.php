<?php 

include "config.php";
	  $response=array();
   
      $users=array();

      if($_REQUEST['id'] != null){
        $userif=$_REQUEST['id'];
            
        $sql=sqlsrv_query($connection,"SELECT VehicleNumber from vehicle where IsTM =1", array(), array( "Scrollable" => 'static' ));
	    	$num=sqlsrv_num_rows($sql);
        if($num >0){
         
			while($data= sqlsrv_fetch_array($sql,SQLSRV_FETCH_ASSOC)){
        array_push($users,array("status"=>1,"vehiclenumber"=>strtoupper($data['VehicleNumber'])));
        }
 
      }
      else{ 
        array_push($users,array("status"=>0,"vehiclenumber"=>"noAny"));
      }
      $response=$users;
      echo json_encode($response);
    sqlsrv_close($connection);
      }

?>

