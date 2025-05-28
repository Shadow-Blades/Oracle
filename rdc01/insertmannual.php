<?php 
include "config.php";
$orderId="0";
$response = array();


$sql3 = sqlsrv_query($connection, "SELECT * from TransactionData 
where Status = 'Complete' and TransactionMode = 'Double'
 and CreationTime > '2025-01-01 00:00:10.000'
and Cu_orderID not in (select id from cu_orders)", array(), array("Scrollable" => 'static'));



$num = sqlsrv_num_rows($sql3);

if ($num > 0) {
    while ($data = sqlsrv_fetch_array($sql3, SQLSRV_FETCH_ASSOC)) {
        $po_num = $data['PO_No'];
        $mrn = $data['MRN_NO'];
        $vehicle_num = $data['VehicleNumber'];
        $Final_Netweight = $data['Final_Netweight'];
        $date_creationtime = date_format($data['CreationTime'], 'Y/m/d H:i:s');
        $gross = "0";
        $ReceiptTicketID = $data['ReceiptTicketID'];
        $TareWeight = "0";
        $Moisture_per = $data['Moisture_per'];
        $vendor_email = $data['Vendor_Email'];
        $challanno = $data['ChallanNo'];

        $sql1 = sqlsrv_query($connection, "SELECT ID, V_ID FROM po_details WHERE PO_NUMBER='$po_num'", array(), array("Scrollable" => 'static'));
        if (sqlsrv_num_rows($sql1) > 0) {
            $data11 = sqlsrv_fetch_array($sql1, SQLSRV_FETCH_ASSOC);
            $po_id = $data11['ID'];
            $vendor_id = $data11['V_ID'];
            $driverid = $data11['V_ID'];
        }

        // Check if the record exists
        $checkQuery = sqlsrv_query($connection, "SELECT id FROM cu_orders WHERE po_id='$po_id' AND MRN_NO='$mrn'", array(), array("Scrollable" => 'static'));

        if (sqlsrv_num_rows($checkQuery) > 0) {
            // Update existing record
            $existingData = sqlsrv_fetch_array($checkQuery, SQLSRV_FETCH_ASSOC);
            $orderId = $existingData['id'];

            $updateQuery = sqlsrv_query($connection, "UPDATE cu_orders SET RDC_GROSS='$gross', RDC_TARE='$TareWeight', RDC_NET='$Final_Netweight',MRN_NO='$mrn' WHERE id='$orderId'");
        } else {
            // Insert new record
            $insertQuery = sqlsrv_query($connection, "INSERT INTO cu_orders(created_at, driver_id, vendor_id, PO_ID, GROSS, TARE, NET, MRN_NO, CHALAN_NO, ROYALTI_PASS, VEHICLE_NUMBER, MOISTURE_PER, ACCEPT_BY, gross_time, tare_time, RECIEPT_IMAGE, VEHICLE_CONDITION, MOISTURE_CHECK, V_Email, RDC_GROSS, RDC_TARE, RDC_NET)
            VALUES ('$date_creationtime', $driverid, $vendor_id, $po_id, '0', '0', '0', '$mrn', '$challanno', '0', '$vehicle_num', '$Moisture_per', 1, '$date_creationtime', '$date_creationtime', '-', '1', '1', '$vendor_email', '$gross', '$TareWeight', '$Final_Netweight'); SELECT @@IDENTITY as id;");
            
            $next_result = sqlsrv_next_result($insertQuery);
            $row = sqlsrv_fetch_array($insertQuery);
            $orderId = $row["id"];
              $sqlStatus = sqlsrv_query($connection, "INSERT INTO cu_statuses(order_id, placed, pick, collected, complate, denied, collector_id, actiontime, reach_site)
        VALUES ($orderId, 1, 1, 0, 1, 0, $driverid, '$date_creationtime', 1)");
        }

        // Update cu_statuses
      

        // Update TransactionData with new order ID
        $updateTransaction = sqlsrv_query($connection, "UPDATE TransactionData SET Cu_orderID='$orderId', OnlineSyncTime=DATEADD(MINUTE, 330, GETUTCDATE()) WHERE ReceiptTicketID='$ReceiptTicketID'");

       
    }
}
sqlsrv_close($connection);
?>
