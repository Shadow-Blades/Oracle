<?php 

include "config.php";
	  $response=array();
      $roles=array();
   

      if($_REQUEST['location'] != null && $_REQUEST['oid']!=null){
        $id=$_REQUEST['oid'];
        $sql1=sqlsrv_query($connection,"select MOISTURE_CHECK from cu_orders where id=$id" , array(), array( "Scrollable" => 'static' ));
        $num1=sqlsrv_num_rows($sql1);
        $data= sqlsrv_fetch_array($sql1,SQLSRV_FETCH_ASSOC);
        if($data['MOISTURE_CHECK']!="0"){
  
			
        array_push($roles,array("weight"=>"0"));
      
        }
        else{
          array_push($roles,array("weight"=>"Moisture isn't Check"));
        }


      $response=$roles;
      echo json_encode($response);
    sqlsrv_close($connection);
      }

?>