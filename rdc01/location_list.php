<?php 

include "config.php";
	  $response=array();
      $roles=array();
   

      if($_REQUEST['id'] != null){

            
        $sql=sqlsrv_query($connection,"select Sitename,id from LocationMaster order by Sitename" , array(), array( "Scrollable" => 'static' ));
		$num=sqlsrv_num_rows($sql);
        if($num >0){
            
			while($data= sqlsrv_fetch_array($sql,SQLSRV_FETCH_ASSOC)){
        array_push($roles,array("locationname"=>$data['Sitename'],"locationid"=>$data['id']));
        }
 
      }
      $response=$roles;
      echo json_encode($response);
    sqlsrv_close($connection);
      }

?>