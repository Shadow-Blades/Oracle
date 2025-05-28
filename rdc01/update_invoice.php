<?php

include "config.php";
$response = array();
$id = '4';
$itemname = "";

$entityBody = file_get_contents('php://input');
file_put_contents("1234.txt", $entityBody);
$data = json_decode($entityBody, true);


$x=$data["selected_rows"];
$y=$data["remarks"];
$z=$data["approval"];




foreach ($x as $key => $value) {


if($z=="approve"){
    $sqlinvoiceinsert = sqlsrv_query($connection,"UPDATE Table_1 Set status='Pending' , remarks='$y',approved_by=2 where po_id='$value'and order_id='$key' ");



}else{
    $sqlinvoiceinsert = sqlsrv_query($connection,"UPDATE Table_1 Set status='Reject' ,remarks='$y' where  po_id='$value'and order_id='$key'");
}
   


}
$response['status']= 1;
$response['message']="you have transacttions";

echo json_encode($response);
sqlsrv_close($connection);
?>
