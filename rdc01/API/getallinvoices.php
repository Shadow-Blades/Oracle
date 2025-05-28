<?php
header('Content-Type: application/json');

// Basic Authentication

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo json_encode(["status" => 0, "message" => "Authentication required"]);
    exit;
} else {
    $username = $_SERVER['PHP_AUTH_USER'];
    $password = $_SERVER['PHP_AUTH_PW'];

    if ($username !== 'sa' || $password !== 'Endel@d1g1tal') {
        header('HTTP/1.0 403 Forbidden');
        echo json_encode(["status" => 0, "message" => "Forbidden"]);
        exit;
    }
}

// Include your database configuration file
include "config.php";

// Initialize response arrays
$response = [];
$complete = [];
$completecounter = 0;

// Query to fetch data from the database
$sql = sqlsrv_query($connection, " 
    SELECT TOP(50)
        POD.PO_NUMBER AS 'ponumber',
        cu_orders.VEHICLE_NUMBER AS 'vehiclenumber',
     
        cu_orders.challan_no1,
        cu_orders.NET1,
        cu_orders.withinvoice,
        cu_orders.withinvoice1,
  
     
        cu_orders.MOISTURE_PER AS 'moisture',
        cu_orders.MRN_NO AS 'mrnno',
        cu_orders.RDC_GROSS AS 'rdcgross',
        cu_orders.RDC_TARE AS 'rdctare',
        cu_orders.RDC_NET AS 'rdcnet',
        cu_orders.TARE AS 'tareweight',
        cu_orders.GROSS AS 'grossweight',
        cu_orders.id AS 'orderid',
        Table_1.status,
        Table_1.remarks,
        Table_1.name,
        Table_1.invoicedate,
        Table_1.invoicenumber AS 'invoiceid',
        Table_1.approved_by,
        Table_1.qty,
        Table_1.amount,
        POD.ITEM_NAME AS 'itemname',
        cu_orders.RECIEPT_IMAGE AS 'reciept',
        POD.UOM AS 'uom',
        ostatus.placed AS 'placed',
        cu_orders.created_at AS 'creationtime',
        cu_orders.NET AS 'netweight',
        ostatus.actiontime AS 'lastaction',
        cu_orders.gross_time AS 'grosstime',
        cu_orders.tare_time AS 'taretime',
        cu_orders.CHALAN_NO AS 'challanno',
        cu_orders.ROYALTI_PASS AS 'royaltipassno',
     
        POD.SITENAME AS 'sitename',
        ostatus.pick AS 'active',
        ostatus.denied AS 'cancel',
        ostatus.complate AS 'complete',
        ostatus.actiontime,
        ostatus.placed AS 'placed',
        cu_orders.ACCEPT_BY AS 'acceptby'
    FROM cu_orders 
    LEFT JOIN po_details AS POD ON POD.ID = cu_orders.PO_ID 
 
    LEFT JOIN cu_statuses AS ostatus ON ostatus.order_id = cu_orders.id
    LEFT JOIN Table_1 ON Table_1.order_id = cu_orders.id
    WHERE Table_1.sapstatus = 'In Progress' and cu_orders.created_at  > '2025-01-01 00:00:00.000' and ostatus.complate=1 and  Table_1.approved_by !=0 AND ostatus.actiontime IS NOT NULL 
    ORDER BY cu_orders.id desc
", array(), array("Scrollable" => 'static'));

if ($sql === false) {
    echo json_encode(["status" => 0, "message" => "Error executing query", "error" => sqlsrv_errors()]);
    exit;
}

// Fetch results and process them
$num = sqlsrv_num_rows($sql);

if ($num > 0) {
    while ($data = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)) {
        $datevalneedby = $data['lastaction'];
        $date_actiontime = date_format($datevalneedby, 'd/m/Y H:i:s');
        $actiontime = $data['creationtime'];
        $date_creationtime = date_format($actiontime, 'd/m/Y H:i:s');
        $invoicedate = $data['invoicedate'];
        $invoicedate1 = date_format($invoicedate, 'd/m/Y');
        $filePath = 'C:/Program Files/Ampps/www/rdc01/' . $data['name'];
        $invoicenumber=$data['invoiceid'];




        $invoicename =$data['name'];
$number="";
// Extract only the number using preg_match
if (preg_match('/\d+/', $invoicename, $matches)) {
    $number = $matches[0]; // The matched number
   
} else {
   
}

        // Check if the file exists before trying to read it
        if (file_exists($filePath)) {
            $fileContent = file_get_contents($filePath);
            $base64EncodedContent = base64_encode($fileContent);
            
        if ($data['approved_by'] == 2 && $base64EncodedContent !="") {
            $complete[] = [
                "ponumber" => $data['ponumber'],
                "mrnno" => $data['mrnno'],
                "invoiceid" => $data['invoiceid'],
                "invoicedate" => $invoicedate1,
                "qty" => $data['qty'],
                "amount" => $data['amount'],
                "invoicefile" => $base64EncodedContent,
                "TransactionID" => $number
            ];
            $completecounter++;
        }
        else{
            $sql11=sqlsrv_query($connection,"UPDATE Table_1 set status='CANCELLED',sapstatus='CANCELLED',erpdescription='Wrong Invoice File Please Reupload !' where invoicenumber='$invoicenumber'");

        }
        } else {

            $base64EncodedContent = null;
        }

    }

    $response['status'] = 1;
    $response['message'] = "You have transactions";
    $response['completecounter'] = $completecounter;
    $response['complete'] = $complete;
} else {
    $response['status'] = 0;
    $response['message'] = "You don't have any requests";
}

// Return JSON response
echo json_encode($response);

// Close the database connection
sqlsrv_close($connection);
?>
