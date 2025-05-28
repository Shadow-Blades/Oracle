<?php
	$servername="YOUR_SQL_SERVER"; // Example: .\SQLSERVER or server_address
	$connectionifnfo=array( 
		"Database"=>"YOUR_DATABASE_NAME", 
		"UID"=>"YOUR_DATABASE_USER", 
		"PWD"=>"YOUR_DATABASE_PASSWORD"
	);
	$connection=sqlsrv_connect($servername,$connectionifnfo);
	
	if($connection){
		// Connection successful
	}
	else{
		echo "could not connect";
		die(
			print_r(sqlsrv_errors(),true)
		);
	}
?> 