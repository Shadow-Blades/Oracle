<?php

  include("config.php");

if($_REQUEST['id'] != null && $_REQUEST['device_id'] != null){
	
	$c_v;
	$id=$_REQUEST['id'];
	$d_id=$_REQUEST['device_id'];
	$user_v=$_REQUEST['u_v'];
	devicecheck($connection,$d_id,$id,$user_v);
	
    $q=sqlsrv_query($connection,"SELECT update_at,v_code,v_file FROM version_codes WHERE v_code = (SELECT MAX(v_code) FROM version_codes) order by v_code asc ");
	
			if($q){
					while($data=sqlsrv_fetch_array($q,SQLSRV_FETCH_ASSOC)){
						$c_v=$data['v_code'];
						$response['current_v']=$data['v_code'];
						
						$datevalneedby=$data['update_at'];
						$date_creationtime = date_format($datevalneedby, 'd/m/Y H:i:s');

						$response['update_at']=$date_creationtime;
						$response['v_file']=$data['v_file'];
					}
			}
			else{
				echo "sorry";
			}
			if(checkup($user_v,$c_v)==1){
			//$q1=sqlsrv_query($connection,"UPDATE device_info SET version='$user_v' ,update_status='up to date',user_id='$id' WHERE device_id='$d_id'");
			
			}
			else{
			//$q1=sqlsrv_query($connection,"UPDATE device_info SET version='$user_v' ,update_status='no',user_id='$id' WHERE device_id='$d_id'");
			}
			
			echo json_encode($response);
	
	
	}

function devicecheck($connection,$d_id,$id,$user_v){
	
			$q2=sqlsrv_query($connection,"select * from device_info WHERE device_id='$d_id'", array(), array( "Scrollable" => 'static' ));
			$nu_d=sqlsrv_num_rows($q2);
				if($nu_d >0){
								$q1=sqlsrv_query($connection,"UPDATE device_info SET version='$user_v' ,update_status='No',user_id='$id' WHERE device_id='$d_id'");
				}
				else{
								$q1=sqlsrv_query($connection,"INSERT INTO `device_info` ( `device_id`, `version`, `update_status`, `user_id`) VALUES ( '$d_id', '$user_v', 'No', '$id')");
				}
		
}


function checkup($user_v,$c_v){
		if($c_v === $user_v){
			
			return true;
			
		}
		else{
			
			return false;
		}
}
sqlsrv_close($connection);
?>

