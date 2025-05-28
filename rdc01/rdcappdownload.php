<?php

include "config.php";
$file = ''; //not public folder
	
$q=sqlsrv_query($connection,"SELECT update_at,v_code,v_file FROM version_codes WHERE v_code = (SELECT MAX(v_code) FROM version_codes) order by v_code asc ");
	
if($q){
        while($data=sqlsrv_fetch_array($q,SQLSRV_FETCH_ASSOC)){
          $file=$data['v_file'];          
          

        }
}




if (file_exists($file)) {
    echo "hii";
    header('Content-Description: File Transfer');
    header('Content-Type: application/vnd.android.package-archive');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
    exit;
}



