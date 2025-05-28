<?php
	  include("config.php");
 
	  $response=array();

	if($_REQUEST['oid'] != null && $_REQUEST['uid'] != null ){
			
     $oid=$_REQUEST['oid']; 
     $uid=$_REQUEST['uid'];
                	
       if($_REQUEST["status"] == "0" || $_REQUEST["status"] == "1"){
        $status=$_REQUEST["status"];
    
            
        $sql12=sqlsrv_query($connection,"UPDATE cu_statuses SET reach_site='$status', actiontime=DATEADD(MINUTE, 330,GETUTCDATE()) where order_id=$oid");
        if(!$sql12){
               $response['status']="0";
        }
        else{
            $response['status']="1";
        }

       }

    
         
                $sql1=sqlsrv_query($connection,"select reach_site from cu_statuses where order_id=$oid", array(), array( "Scrollable" => 'static' ));
                $num1=sqlsrv_num_rows($sql1);
                
                if($num1 > 0){
                    $data12= sqlsrv_fetch_array($sql1,SQLSRV_FETCH_ASSOC);

                    if($data12['reach_site'] =="0"){
                        $response['reachplant']="no";
                        $response['status']="0";
                    }
                    else{
                        $response['reachplant']="yes";
                        $response['status']="1";
                    }
                }

            
     

	
       echo json_encode($response);
		sqlsrv_close($connection);
	}
	
	
	
	?>