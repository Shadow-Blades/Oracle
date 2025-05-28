<?php

include "config.php";
$response = array();
$id = '4';
$itemname = "";

$entityBody = file_get_contents('php://input');
file_put_contents("1234.txt", $entityBody);
$product = array(
    "ADMAEA", "ADMBA", "ADMCORRFRE", "ADMCRST", "ADMHPCEA",
    "ADMHPCEAFR", "ADMHRWRA", "ADMHRWRAFR", "ADMLPCEA",
    "ADMMPCEA", "ADMMPCEAFR", "ADMNONSHR", "ADMRAFREE",
    "ADMVMA", "ADMWPA", "ICE"
);

$data = json_decode($entityBody, true);

$VEHICLE_NUMBER = $data['VEHICLENUMBER'];
$sql21vehicle = sqlsrv_query($connection, "SELECT * FROM cu_orders INNER JOIN cu_statuses AS ostatus ON ostatus.order_id = cu_orders.id WHERE cu_orders.VEHICLE_NUMBER = '$VEHICLE_NUMBER' AND ostatus.complate != 1 AND ostatus.denied != 1", array(), array("Scrollable" => 'static'));
$numofvehicle = sqlsrv_num_rows($sql21vehicle);
if ($data['createdat'] != null && $data['driverid'] != null && $data['vendorid'] != null &&   $numofvehicle <=0) {
    $created_at = $data['createdat'];
    $driver_id = $data['driverid'];
    $vendor_id = $data['vendorid'];
    $poid = $data['poid'];
    $MRN_NO = "";
    $GROSS = $data['GROSS'];
    $TARE = $data['TARE'];
    $NET = $data['NET'];
    $netx = (int)$NET;
    $CHALAN_NO = $data['CHALANNO']; // invoice number
    $ROYALTI_PASS = $data['ROYALTIPASS'];
    
    $MOISTURE_PER = $data['MOISTUREPER'];
    $ACCEPT_BY = $data['ACCEPTBY'];
    $VEHICLE_CONDITION = $data['VEHICLECONDITION'];
    $RECIEPT_IMAGE = "aa";
    $MOISTURE_CHECK = $data['MOISTURECHECK'];
    $V_Email = $data['VEmail'];
    $challannoone = $data['challannoone']; // invoice number
    $netweightone = $data['netweightone'];
    $invoiceflag = $data['withinvoice'];
    $invoiceflag1 = $data['withinvoice1'];
    $invoicenumberone = $data['invoicenumberone'];
    $invoicenumbertwo = $data['invoicenumbertwo'];

    // Check if a similar entry already exists
    $check_duplicate_query = sqlsrv_query($connection, "SELECT * FROM cu_orders WHERE CHALAN_NO = '$CHALAN_NO' AND VEHICLE_NUMBER = '$VEHICLE_NUMBER' AND PO_ID = $poid", array(), array("Scrollable" => 'static'));
    $duplicate_count = sqlsrv_num_rows($check_duplicate_query);
    
    if ($duplicate_count > 0) {
        $response['status'] = "0";
        $response['message'] = "Duplicate entry found! Challan Number and Vehicle Number already exist.";
    } else {
        // If no duplicate entry found, proceed with insertion

        // Decode and save the first invoice
        $amountquery = sqlsrv_query($connection, "select * from po_details where id=$poid", array(), array("Scrollable" => 'static'));
        $price = sqlsrv_fetch_array($amountquery, SQLSRV_FETCH_ASSOC);
        $actualamount=$price['unit_price'];
        $sql21challan = sqlsrv_query($connection, "SELECT * FROM cu_orders WHERE V_Email='$V_Email' AND CHALAN_NO='$CHALAN_NO'", array(), array("Scrollable" => 'static'));
        $sql21 = sqlsrv_query($connection, "SELECT PO_NUMBER FROM po_details WHERE ID=$poid", array(), array("Scrollable" => 'static'));

        $num2 = sqlsrv_num_rows($sql21);
        $num2challan = sqlsrv_num_rows($sql21challan);

        if ($num2challan > 0 || strlen($VEHICLE_NUMBER) < 8 ) {
            $response['status'] = "0";
            $response['message'] = "Challan No is already Used or Wrong Vehicle Number!";
        } else {
            $data121 = sqlsrv_fetch_array($sql21, SQLSRV_FETCH_ASSOC);
            $ponum = $data121['PO_NUMBER'];
            $sql11 = sqlsrv_query($connection, "SELECT status, ITEM_NAME FROM po_details WHERE PO_NUMBER='$ponum'", array(), array("Scrollable" => 'static'));
            $data12 = sqlsrv_fetch_array($sql11, SQLSRV_FETCH_ASSOC);

            $val = $data12['status'];
            $itemname = $data12['ITEM_NAME'];

            if ($val != 'Open') {
                $response['status'] = "0";
                $response['message'] = "PO is actually Closed!";
            } else {
                if (in_array($itemname, $product) || strstr($itemname, 'ADM')) {
                    $sql = sqlsrv_query($connection, "
                    INSERT INTO cu_orders(created_at, driver_id, vendor_id, PO_ID, GROSS, 
                                TARE, NET, MRN_NO, CHALAN_NO, ROYALTI_PASS, VEHICLE_NUMBER, MOISTURE_PER, 
                                ACCEPT_BY, gross_time, tare_time, RECIEPT_IMAGE, VEHICLE_CONDITION, MOISTURE_CHECK, V_Email,
                                RDC_GROSS, RDC_TARE, RDC_NET,challan_no1,NET1,withinvoice,withinvoice1) VALUES 
                                ('$created_at', $driver_id, $vendor_id, $poid, '$GROSS', '$TARE', '$NET', '$MRN_NO', '$CHALAN_NO', 
                                '$ROYALTI_PASS', '$VEHICLE_NUMBER', '$MOISTURE_PER', $ACCEPT_BY, '$created_at', '$created_at', 
                                '$RECIEPT_IMAGE', '$VEHICLE_CONDITION', '$MOISTURE_CHECK', '$V_Email', '', '', '','$challannoone','$netweightone','$invoiceflag','$invoiceflag1'); SELECT @@IDENTITY as id;");
                    $next_result = sqlsrv_next_result($sql);
                    $row = sqlsrv_fetch_array($sql);
                    $id = $row["id"];
                    if (!$sql) {
                        $response['status'] = "0";
                        $response['message'] = "not inserted";
                    } else {
                        if($invoiceflag !='0' ){
                        $invoice_one_path = 'images/'.$id.'one.pdf';
                        file_put_contents($invoice_one_path, base64_decode($invoicenumberone));
                        $sqlinvoiceinsert = sqlsrv_query($connection,"INSERT INTO Table_1(name,status,po_id,order_id,approved_by,date,qty,amount,invoicenumber,invoicedate,sapstatus)VALUES('$invoice_one_path', 'In Progress', '$poid','$id', '2',getdate(),'$NET',$NET*$actualamount,'$CHALAN_NO',getdate(),'In Progress')");
                        }
                        if($invoiceflag1 !='0' ){
                        $invoice_two_path = 'images/'.$id.'two.pdf';
                        file_put_contents($invoice_two_path, base64_decode($invoicenumbertwo));
                        $sqlinvoiceinsert = sqlsrv_query($connection,"INSERT INTO Table_1(name,status,po_id,order_id,approved_by,date,qty,amount,invoicenumber,invoicedate,sapstatus)VALUES('$invoice_two_path', 'In Progress', '$poid','$id', '2',getdate(),' $netweightone',$netweightone*$actualamount,'$CHALAN_NO',getdate(),'In Progress')");
                        }

                        $response['status'] = "1";
                        $response['message'] = "record inserted successfully345";

                        $sql1 = sqlsrv_query($connection, "INSERT INTO cu_statuses(order_id, placed, pick, collected, complate, denied, collector_id, actiontime, reach_site) VALUES ($id, 1, 1, 0, 0, 0, $driver_id, '$created_at', 0)");
                        $driverUpdate = sqlsrv_query($connection, "UPDATE cu_users SET IsActive = '1' WHERE id = $driver_id");
                    }
                } else {
                    if ($netx < 5000 && $netx != 0) {
                        $response['status'] = "0";
                        $response['message'] = "Challan Weight Should be Greater Than 5000 Kg or Should be zero (0)";
                    } else {
                        $sql = sqlsrv_query($connection, "INSERT INTO cu_orders(created_at, driver_id, vendor_id, PO_ID, GROSS, TARE, NET, MRN_NO, CHALAN_NO, ROYALTI_PASS, VEHICLE_NUMBER, MOISTURE_PER, ACCEPT_BY, gross_time, tare_time, RECIEPT_IMAGE, VEHICLE_CONDITION, MOISTURE_CHECK, V_Email, RDC_GROSS, RDC_TARE, RDC_NET,challan_no1,NET1,withinvoice,withinvoice1) VALUES ('$created_at', $driver_id, $vendor_id, $poid, '$GROSS', '$TARE', '$NET', '$MRN_NO', '$CHALAN_NO', '$ROYALTI_PASS', '$VEHICLE_NUMBER', '$MOISTURE_PER', $ACCEPT_BY, '$created_at', '$created_at', '$RECIEPT_IMAGE', '$VEHICLE_CONDITION', '$MOISTURE_CHECK', '$V_Email', '', '', '','$challannoone','$netweightone','$invoiceflag','$invoiceflag1'); SELECT @@IDENTITY as id;");
                        
                        $next_result = sqlsrv_next_result($sql);
                        $row = sqlsrv_fetch_array($sql);
                        $id = $row["id"];
                        if (!$sql) {
                            $response['status'] = "0";
                            $response['message'] = "not inserted";
                        } else {
                            if($invoiceflag !='0' ){
                                $invoice_one_path = 'images/'.$id.'one.pdf';
                                file_put_contents($invoice_one_path, base64_decode($invoicenumberone));
                                $sqlinvoiceinsert = sqlsrv_query($connection,"INSERT INTO Table_1(name,status,po_id,order_id,approved_by,date,qty,amount,invoicenumber,invoicedate,sapstatus)VALUES('$invoice_one_path', 'In Progress', '$poid','$id', '2',getdate(),'$NET',$NET*$actualamount,'$CHALAN_NO',getdate(),'In Progress')");
                            }
                            if($invoiceflag1 !='0' ){
                                $invoice_two_path = 'images/'.$id.'two.pdf';
                                file_put_contents($invoice_two_path, base64_decode($invoicenumbertwo));
                                $sqlinvoiceinsert = sqlsrv_query($connection,"INSERT INTO Table_1(name,status,po_id,order_id,approved_by,date,qty,amount,invoicenumber,invoicedate,sapstatus)VALUES('$invoice_two_path', 'In Progress', '$poid','$id', '2',getdate(),' $netweightone',$netweightone*$actualamount,'$CHALAN_NO',getdate(),'In Progress')");
                            }

                            $response['status'] = "1";
                            $response['message'] = "record inserted successfully123";

                            $sql1 = sqlsrv_query($connection, "INSERT INTO cu_statuses(order_id, placed, pick, collected, complate, denied, collector_id, actiontime, reach_site) VALUES ($id, 1, 1, 0, 0, 0, $driver_id, '$created_at', 0)");
                            $driverUpdate = sqlsrv_query($connection, "UPDATE cu_users SET IsActive = '1' WHERE id = $driver_id");
                        }
                    }
                }
            }
        }
    }

    echo json_encode($response);
    sqlsrv_close($connection);
} else {
    $response['status'] = "0";
    $response['message'] = "Required fields are missing or Vehicle is in running trip!";
    echo json_encode($response);
}
?>
