

<?php
// Basic Authentication
error_reporting(E_ERROR | E_PARSE);
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

include "config.php";

$response = [];
$complete = [];
$completecounter = 0;

$sql = sqlsrv_query($connection, " 
    SELECT  
        POD.PO_NUMBER AS 'ponumber',
        cu_orders.VEHICLE_NUMBER AS 'vehiclenumber',
        driver.id AS 'driverid',
        vendor.user_email AS 'vendoremail',
        cu_orders.challan_no1,
        cu_orders.NET1,
        cu_orders.withinvoice,
        cu_orders.withinvoice1,
        driver.user_fullname AS 'drivername',
        driver.user_image AS 'images',
        driver.user_mobile AS 'drivermobile',
        vendor.user_mobile AS 'vendormobile',
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
        Table_1.date,Table_1.id as 'invoiceid',
        Table_1.approved_by,Table_1.qty,Table_1.amount,
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
        vendor.user_fullname AS 'vendorname',
        POD.SITENAME AS 'sitename',
        ostatus.pick AS 'active',
        ostatus.denied AS 'cancel',
        ostatus.complate AS 'complete',
        ostatus.actiontime,
        ostatus.placed AS 'placed',
        cu_orders.ACCEPT_BY AS 'acceptby' 
    FROM cu_orders 
    INNER JOIN cu_users AS vendor ON vendor.id = cu_orders.vendor_id 
    LEFT JOIN po_details AS POD ON POD.ID = cu_orders.PO_ID 
    LEFT JOIN cu_users AS driver ON driver.id = cu_orders.driver_id 
    LEFT JOIN cu_statuses AS ostatus ON ostatus.order_id = cu_orders.id
    LEFT JOIN Table_1 ON Table_1.order_id = cu_orders.id
    WHERE Table_1.status = 'Complete' AND ostatus.actiontime IS NOT NULL 
    ORDER BY cu_orders.id DESC
", array(), array("Scrollable" => 'static'));

if ($sql === false) {
    die(print_r(sqlsrv_errors(), true));
}

$num = sqlsrv_num_rows($sql);

if ($num > 0) {
    while ($data = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)) {
        $datevalneedby = $data['lastaction'];
        $date_actiontime = date_format($datevalneedby, 'd/m/Y H:i:s');
        $actiontime = $data['creationtime'];
        $date_creationtime = date_format($actiontime, 'd/m/Y H:i:s');
        $invoicedate = $data['date'];
        $invoicedate1 = date_format($invoicedate, 'd/m/Y');
        $filePath = 'C:/Program Files/Ampps/www/rdc01/'.$data['name'];

        $fileContent = file_get_contents($filePath);
        $base64EncodedContent = base64_encode($fileContent);


        if ($data['status'] == 'Complete') {
            $complete[] = [
                "ponumber" => $data['ponumber'],
                "mrnno" => $data['mrnno'],
                "invoiceid" => $data['invoiceid'],
                "invoicedate" => $invoicedate1,
                "qty" => $data['qty'],
                "amount" => $data['amount'],
                "invoicefile"=> $base64EncodedContent
            ];
            $completecounter++;
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

echo json_encode($response);

sqlsrv_close($connection);
?>
