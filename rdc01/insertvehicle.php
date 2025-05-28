<?php

include "config.php";
$response = array();
$roles = array();
 $sql=""; 

if ($_REQUEST['vehicleno'] != null && $_REQUEST['rfifno'] != null) {

      $rfid = $_REQUEST['rfifno'];

      $vno = strtoupper($_REQUEST['vehicleno']);

      $sql1 = sqlsrv_query($connection, "SELECT * from vehicle where VehicleNumber='$vno' or RFID like '%$rfid%' ", array(), array("Scrollable" => 'static'));
      $num = sqlsrv_num_rows($sql1);

      if ($num > 0) {

            while ($data = sqlsrv_fetch_array($sql1, SQLSRV_FETCH_ASSOC)) {
                  $vehiclenumber = $data['VehicleNumber'];
                  $response['status'] = "0";
                  $response['message'] = "duplication Not Allowed  " . $vehiclenumber;
                  $response['createdid']=$vehiclenumber;
            }
            //sql11=sqlsrv_query($connection,"update vehicle set RFID='$rfid' where VehicleNumber='$vno'");

      } else {

            $size = strlen($rfid);
          
            $rfid1 = "";
            $rfid2 = "";
            $readerdata = str_split($rfid, $size / 2);
            if ($size > 36) {
                 


                  if ($readerdata[0] == $readerdata[1]) {
                        $rfid1 = $readerdata[0];
                        $sql11212121 = sqlsrv_query($connection, "SELECT * from vehicle where  RFID like '%$rfid1%' or RFID2 like '%$rfid1%' ", array(), array("Scrollable" => 'static'));
                        $num1213 = sqlsrv_num_rows($sql11212121);

                        if ($num1213 > 0) {
                              while ($data = sqlsrv_fetch_array($sql11212121, SQLSRV_FETCH_ASSOC)) {
                                    $vehiclenumber = $data['VehicleNumber'];
                                    $response['status'] = "0";
                                    $response['message'] = "duplication Not Allowed  " . $vehiclenumber;
                                    $response['createdid']=$vehiclenumber;
                              }
                        } else {
                              $sql = sqlsrv_query($connection, "INSERT INTO Vehicle 
                              (VehicleNumber,RFID,OnlineSyncTime,IsActive) 
                              VALUES 
                              ('$vno','$rfid1',DATEADD(MINUTE, 330,GETUTCDATE()),1)");
                                if (!$sql) {
                                    $response['status'] = "0";
                                    $response['message'] = "not inserted succesfully";
                              } else {
                                    $response['status'] = "1";
                                    $response['message'] = "inserted successfully";
                              }
                        }

                  } else {

                        $rfid1 = $readerdata[0];
                        $rfid2 = $readerdata[1];

                        $sql11212121 = sqlsrv_query($connection, "SELECT * from vehicle where ( RFID like '%$rfid1%' or RFID2 like '%$rfid1%') or  ( RFID like '%$rfid2%' or RFID2 like '%$rfid2%') ", array(), array("Scrollable" => 'static'));
                        $num1213 = sqlsrv_num_rows($sql11212121);
                        if ($num1213 > 0) {

                              while ($data = sqlsrv_fetch_array($sql11212121, SQLSRV_FETCH_ASSOC)) {
                                    $vehiclenumber = $data['VehicleNumber'];
                                    $response['status'] = "0";
                                    $response['message'] = "duplication Not Allowed  " . $vehiclenumber;
                                    $response['createdid']=$vehiclenumber;
                              }
                        } 
                        else 
                        {
                              $sql = sqlsrv_query($connection, "INSERT INTO Vehicle 
                              (VehicleNumber,RFID,RFID2,OnlineSyncTime,IsActive) 
                              VALUES 
                              ('$vno','$rfid1','$rfid2',DATEADD(MINUTE, 330,GETUTCDATE()),1)");
                              if (!$sql) {
                                    $response['status'] = "0";
                                    $response['message'] = "not inserted succesfully";
                              } else {
                                    $response['status'] = "1";
                                    $response['message'] = "inserted successfully";
                              }
                        }


                  }

            } else {
                  
                  $sql11212121 = sqlsrv_query($connection, "SELECT * from vehicle where  RFID like '%$rfid%' or RFID2 like '%$rfid%'", array(), array("Scrollable" => 'static'));
                  $num1213 = sqlsrv_num_rows($sql11212121);
                  if ($num1213 > 0) {

                        while ($data = sqlsrv_fetch_array($sql11212121, SQLSRV_FETCH_ASSOC)) {
                              $vehiclenumber = $data['VehicleNumber'];
                              $response['status'] = "0";
                              $response['message'] = "duplication Not Allowed  " . $vehiclenumber;
                              $response['createdid']=$vehiclenumber;
                        }
                  } 
                  else 
                  {
                        $sql = sqlsrv_query($connection, "INSERT INTO Vehicle 
                        (VehicleNumber,RFID,OnlineSyncTime,IsActive) 
                        VALUES 
                        ('$vno' ,'$rfid',DATEADD(MINUTE, 330,GETUTCDATE()) ,1)");
                          if (!$sql) {
                              $response['status'] = "0";
                              $response['message'] = "not inserted succesfully";
                        } else {
                              $response['status'] = "1";
                              $response['message'] = "inserted successfully";
                        }
                  }

            }


          
      }




      echo json_encode($response);
      sqlsrv_close($connection);
}

?>