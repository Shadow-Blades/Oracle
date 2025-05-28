<?php
 include('config.php');


	$d=array();
  $qry="select * from cu_users"; 
		
  $res=mysqli_query($conn,$qry);		 
  
  while($data=mysqli_fetch_assoc($res)){
	  array_push($d,$data);
  }
  
 echo json_encode($d);
 	mysqli_close($connection);
  
?>