<?php
header('Content-Type: application/json');

// Basic Authentication
$valid_username = "sa";
$valid_password = "Endel@d1g1tal";

if (
    !isset($_SERVER['PHP_AUTH_USER']) || 
    !isset($_SERVER['PHP_AUTH_PW']) || 
    $_SERVER['PHP_AUTH_USER'] !== $valid_username || 
    $_SERVER['PHP_AUTH_PW'] !== $valid_password
) {
    header('WWW-Authenticate: Basic realm="Restricted Area"');
    header('HTTP/1.0 401 Unauthorized');
    echo json_encode(array("status" => 0, "message" => "Unauthorized"));
    exit;
}

include "config.php";

// Initialize response
$response = [];
$resultData = [];

// Read the raw POST data
$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);
file_put_contents('updateinvoic1515e.txt', $rawData);
// Validate JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(["status" => 0, "message" => "Invalid JSON"]);
    exit;
}

// Check if payload is an array
if (!is_array($data) || empty($data)) {
    echo json_encode(["status" => 0, "message" => "Invalid or empty payload"]);
    exit;
}

// Process each entry in the payload
foreach ($data as $entry) {
    // Validate the required fields
    if (!isset($entry['invoiceid']) || !isset($entry['status']) || !isset ($entry['erpdescription'] ) || !isset($entry['bookedamount']) ) {
        $resultData[] = [
            "invoiceid" => $entry['invoiceid'] ?? null,
            "status" => 0,
            "message" => "Missing required fields"
        ];
        continue;
    }

    $invoiceid = $entry['invoiceid'];
    $status = $entry['status'];
    $bookedamount=$entry['bookedamount'];
    $erpdescription=$entry['erpdescription'];

    // Determine the new status for Table_1 based on payload status
    $newStatus = null;
    if (strtolower($status) === "processed") {
        $newStatus = "Complete";
    } else {
        $newStatus = $status;
    }

if($status=='CANCELLED'){
    $sqll = sqlsrv_query( 
        $connection,
        "UPDATE cu_orders set withinvoice =2 where id in (select order_id from Table_1 where invoicenumber='?')",
        [$invoiceid]
    );
}

    if ($newStatus) {
        // Update query
        $sql = sqlsrv_query( 
            $connection,
            "UPDATE Table_1 SET status = ?,sapstatus= ?,erpdescription=?,bookedamount=? WHERE invoicenumber = ?",
            [$newStatus,$status, $erpdescription,$bookedamount,$invoiceid]
        );

        if ($sql === false) {
            // Log error if query fails
            $resultData[] = [
                "invoiceid" => $invoiceid,
                "status" => 0,
                "message" => sqlsrv_errors()
            ];
        } else {
            $resultData[] = [
                "invoiceid" => $invoiceid,
                "status" => 1,
                "message" => "Updated successfully"
            ];
        }
    } else {
        $resultData[] = [
            "invoiceid" => $invoiceid,
            "status" => 0,
            "message" => "Invalid status value in payload"
        ];
    }
}

// Respond with results
$response['status'] = 1;
$response['message'] = "Processing completed";
$response['data'] = $resultData;

echo json_encode($response);

// Close database connection
sqlsrv_close($connection);
?>
