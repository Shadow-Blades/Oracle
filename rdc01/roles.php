<?php 

include "config.php";
	  $response=array();
      $roles=array();
   

      if($_REQUEST['id'] != null){

            
        $sql=sqlsrv_query($connection,"select distinct role_name ,id from cu_userroles where role_name!='ADMIN' and role_name !='USER' and role_name !='COLLECTOR'" , array(), array( "Scrollable" => 'static' ));
		$num=sqlsrv_num_rows($sql);
        if($num >0){
            
			while($data= sqlsrv_fetch_array($sql,SQLSRV_FETCH_ASSOC)){
        array_push($roles,array("rolename"=>$data['role_name'],"roleid"=>$data['id']));
        }
 
      }
      $response=$roles;
      echo json_encode($response);
    sqlsrv_close($connection);
      }

?>