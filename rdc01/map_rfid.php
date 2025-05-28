<?php

include "config.php";
$response = array();
$roles = array();
$myrfid = "nikunj";
$myrfid1 = "nikunj1";

if ($_REQUEST['vehiclenumber'] != null && $_REQUEST['location'] != null) {
    $vno = $_REQUEST['vehiclenumber'];
    $location = $_REQUEST['location'];



    $sql1 = sqlsrv_query($connection, "SELECT * from Vehicle where VehicleNumber='$vno'", array(), array("Scrollable" => 'static'));
    $num1 = sqlsrv_num_rows($sql1);
    $response['secondcheck'] = "NO";
    if ($num1 > 0) {
        while ($data12 = sqlsrv_fetch_array($sql1, SQLSRV_FETCH_ASSOC)) {
            $myrfid = $data12['RFID'];
            $myrfid1 = $data12['RFID2'];

            if ($myrfid1 == "" || $myrfid1 == null) {
                $myrfid1 = $myrfid;
            }
            $response['rfid1'] = $myrfid;
            $response['rfid2'] = $myrfid1;
        }


        $response['status'] = '1';
    } else {
        $response['status'] = '0';
    }


    $sql = sqlsrv_query($connection, "SELECT TOP(1) ReaderData as 'rd' from UhfMaster where Local_LocationName ='$location' AND IsRunning=1", array(), array("Scrollable" => 'static'));
    $num = sqlsrv_num_rows($sql);

    if ($num < 1) {
        $response['rfid'] = "Please Tap Fastag  card !";
    }

    // error_reporting(E_ERROR | E_PARSE);
    while ($data1 = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)) {

        if ($data1['rd'] == "") {
            $response['rfid'] = "Please Tap Fastag  card !";
        } else {
            $uhf = $data1['rd'];

            if (strpos($uhf, $myrfid) !== false || strpos($uhf, $myrfid1) !== false) {

                $response['secondcheck'] = "Yes";
            } else {
                $response['secondcheck'] = "No";
            }
            $response['rfid'] = $data1['rd'];
             
        }

    }



    echo json_encode($response);
    sqlsrv_close($connection);
}

?>