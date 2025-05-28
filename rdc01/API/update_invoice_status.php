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

// Read the raw POST data
$rawData = file_get_contents("php://input");
file_put_contents('updateinvoice.txt', $rawData);
$data = json_decode($rawData, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(["status" => 0, "message" => "Invalid JSON"]);
    exit;
}

// Initialize response arrays
$response = [];
$updateCounter = 0;

// Process each entry in the JSON array
foreach ($data as $entry) {
    $status = isset($entry['status']) ? $entry['status'] : '';
    $invoiceid = isset($entry['invoiceid']) ? $entry['invoiceid'] : '';
    $amount= isset($entry['bookedamount']) ? $entry['bookedamount'] : '';
  
    if (!empty($status) && !empty($invoiceid)) {
        $dbStatus = "";
        if ($status === "Processed") {
            $dbStatus = "In Progress";
        } elseif ($status === "Cancel") {
            $dbStatus = "Pending";
        }

        if ($dbStatus) {
            $sql = sqlsrv_query(
                $connection, 
                "UPDATE Table_1 SET status = ?, bookedamount = ? , sapstatus = ? WHERE invoicenumber = ? ",
                array($dbStatus,$amount,$status, $dbStatus)
            );

            if ($sql) {
                $response[] = [
                    "status" => 1,
                    "message" => "Updated successfully",
                    "invoiceid" => $invoiceid,
                    "updated_status" => $dbStatus
                ];
                $updateCounter++;
            } else {
                $response[] = [
                    "status" => 0,
                    "message" => "Failed to update",
                    "invoiceid" => $invoiceid,
                    "error" => sqlsrv_errors()
                ];
            }
        }
    } else {
        $response[] = [
            "status" => 0,
            "message" => "Invalid entry",
            "invoiceid" => $invoiceid
        ];
    }
}




// Return a summary if no entries were updated
if ($updateCounter === 0) {
    echo json_encode(["status" => 0, "message" => "No valid entries provided"]);
} else {
    echo json_encode($response);
}

// Close the database connection
sqlsrv_close($connection);
?>
