<?php 

include "config.php";
	  $response=array();
    $sapid;
   
      $sitecodes=array();

      if($_REQUEST['id'] != null){
       $id=$_REQUEST['id'];
            
        $sql=sqlsrv_query($connection," SELECT * FROM SITECODE WHERE IsActive=1", array(), array( "Scrollable" => 'static' ));
		$num=sqlsrv_num_rows($sql);
        if($num >0){
         
			while($data= sqlsrv_fetch_array($sql,SQLSRV_FETCH_ASSOC)){

            array_push($sitecodes,array("code"=>$data['SiteCodeName'],"cid"=>$data['SiteCodeID']));
        }
 
      }
      $response=$sitecodes;
      echo json_encode($response);
    sqlsrv_close($connection);
      }

?>