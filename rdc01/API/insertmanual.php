<?php
error_reporting(E_ERROR | E_PARSE);
header('Content-Type: application/json');
$orderId="";
$poDetailsid="";
// Basic Authentication
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo json_encode(["status" => 0, "message" => "Authentication required"]);
    exit;
} else {
    $username = $_SERVER['PHP_AUTH_USER'];
    $password = $_SERVER['PHP_AUTH_PW'];

    // Validate credentials
    if ($username !== 'sa' || $password !== 'Endel@d1g1tal') {
        header('HTTP/1.0 403 Forbidden');
        echo json_encode(["status" => 0, "message" => "Forbidden"]);
        exit;
    }
}

// Database connection settings
$serverName = ".\ENDELSQLSERVER";
$connectionOptions = [
    "Database" => "Endel_weighbridge_RDC",
    "Uid" => "Api_user",
    "PWD" => "9K7r4te^xPN5"
];

// Function to connect to the database
function connectToDatabase($serverName, $connectionOptions) {
    $conn = sqlsrv_connect($serverName, $connectionOptions);
    if ($conn === false) {
        die(json_encode(['status' => 0, 'message' => sqlsrv_errors()])); // Return error if connection fails
    }
    return $conn;
}

// Function to fetch PO details from po_details table
function fetchPODetails($conn, $poNumber) {
    $sql = "SELECT V_ID, id, VENDOR_EMAIL FROM po_details WHERE PO_NUMBER = ?";
    $params = [$poNumber];
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        return null;
    }

    $poDetails = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($stmt);

    return $poDetails;
}

// Function to insert into cu_orders table
function insertCUOrders($conn, $entry, $poDetails) {
    $createdAt = date('Y-m-d H:i:s');
    $driverId = 1; // Default driver ID
    $vehicleNumber = "noany";
    $netWeight = $entry['QUANTITY'];

    // SQL query to insert into cu_orders
    $sql = "INSERT INTO cu_orders (
            created_at, driver_id, vendor_id, PO_ID, GROSS, 
            TARE, NET, MRN_NO, VEHICLE_NUMBER, V_Email,RDC_NET
        ) 
        VALUES (getdate(), ?, ?, ?, 0, 0, 0, ?, ?, ? ,?)";
                    
    $params = [
        $driverId, 
        $poDetails['V_ID'], 
        $poDetails['id'],  
        $entry['MRN_NO'], 
        $vehicleNumber, 
        $poDetails['VENDOR_EMAIL'],
         $netWeight
    ];


    // Execute the query
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        // Handle errors
        die(json_encode(['status' => 0, 'message' => sqlsrv_errors()])); // Return error if insert fails
    }

    // Retrieve the last inserted ID
    $sqlLastID = "SELECT @@IDENTITY as id";
    $stmtLastID = sqlsrv_query($conn, $sqlLastID);
    if ($stmtLastID === false) {
        // Handle errors
        die(json_encode(['status' => 0, 'message' => sqlsrv_errors()])); // Return error if query fails
    }
 $poDetailsid=$poDetails['id'];
    // Fetch the inserted ID
    $insertedID = sqlsrv_fetch_array($stmtLastID, SQLSRV_FETCH_ASSOC)['id'];
    //insertIntoTable1($conn, $poDetailsid, $insertedID);
    // Free the statement resources
    sqlsrv_free_stmt($stmt);
    sqlsrv_free_stmt($stmtLastID);


    // Return the inserted ID
    return $insertedID;
}

function insertIntoTable1($conn, $poDetailsid, $orderId) {
    // Example value for 'name', replace this as needed
    $invoice_one_path = 'invoice_placeholder'; // Replace with actual path if available
    $status = 'Pending';
    $approved_by = 0;

    // SQL query to insert into Table_1
    $sql = "INSERT INTO Table_1 (name, status, po_id, order_id, approved_by, date) 
            VALUES (?, ?, ?, ?, ?, getdate())";
    
    $params = [
        $invoice_one_path,
        $status,
        $poDetailsid,
        $orderId,
        $approved_by
    ];

    // Execute the query
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        // Log error messages for debugging
        $errors = sqlsrv_errors();
        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[] = $error['message'];
        }

        // Log error messages to a file for later review
        file_put_contents('table1_insert_error_log.txt', implode("\n", $errorMessages), FILE_APPEND);

        return false; // Return false to indicate failure
    }
}

// Function to insert into cu_statuses table
function insertCUStatuses($conn, $orderId, $driverId) {
    $createdAt = date('Y-m-d H:i:s');

    $sql = "INSERT INTO cu_statuses (order_id, placed, pick, denied,collected,complate, collector_id, actiontime) 
            VALUES (?, 1, 1, 0 , 0, 1, ?, getdate())";

    $params = [$orderId, $driverId];
    $stmt = sqlsrv_query($conn, $sql, $params);
       
    if ($stmt === false) {
        // Log error messages for debugging
        $errors = sqlsrv_errors();
        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[] = $error['message'];
        }

        // Log error messages to a file for later review
        file_put_contents('status_insert_error_log.txt', implode("\n", $errorMessages), FILE_APPEND);

        return false; // Return false to indicate failure
    }

    sqlsrv_free_stmt($stmt);
    return true; // Indicate success
}

// Main function to handle POST requests
function handlePostRequest() {
    global $serverName, $connectionOptions;

    // Get raw input data
    $rawData = file_get_contents("php://input");
    file_put_contents('insertmrn.txt', $rawData); // Log input data for debugging
    $data = json_decode($rawData, true);

    // Check if JSON is valid
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['status' => 0, 'message' => 'Invalid JSON format']);
        return;
    }

    // Normalize data for both single and bulk entries
    if (isset($data['MRN_NO'])) {
        $data = [$data];
    }

    if (!is_array($data)) {
        echo json_encode(['status' => 0, 'message' => 'Invalid data format']);
        return;
    }

    $conn = connectToDatabase($serverName, $connectionOptions);
    $responses = [];

    // Loop through each entry
    foreach ($data as $entry) {
        // Validate required fields
        if (isset($entry['MRN_NO'], $entry['PO_NUMBER'], $entry['QUANTITY'], $entry['UPDATE_BY'], $entry['UPDATE_AT'])) {
            $poDetails = fetchPODetails($conn, $entry['PO_NUMBER']);
             $poDetailsid=$poDetails['id'];
            if (!$poDetails) {
                $responses[] = [
                    'status' => 0,
                    'message' => 'PO_NUMBER not found',
                    'PO_NUMBER' => $entry['PO_NUMBER']
                ];
                continue;
            }

            // Insert into cu_orders table
            $orderId = insertCUOrders($conn, $entry, $poDetails);
            if ($orderId) {
                // Insert into cu_statuses table
                insertCUStatuses($conn, $orderId, 1);
                $responses[] = [
                    'status' => 1,
                    'message' => 'Record inserted successfully',
                    'MRN_NO' => $entry['MRN_NO'],
                    'order_id' => $orderId
                ];
            } else {
                $responses[] = [
                    'status' => 0,
                    'message' => 'Insert failed for cu_orders'
                ];
            }
        } else {
            $responses[] = [
                'status' => 0,
                'message' => 'Missing required parameters',
                'data' => $entry
            ];
        }
    }

    sqlsrv_close($conn);

    // Prepare the data to append
$rawData = "Your new data here";
$currentDateTime = date('Y-m-d H:i:s'); // Get the current date and time
$dataToAppend = $currentDateTime . " - " . json_encode($responses) . PHP_EOL; // Combine date/time with data and add a new line

// Append the data to the file
file_put_contents('insertmrn4415.txt', $dataToAppend, FILE_APPEND);
    echo json_encode($responses);
}

// Handle the HTTP request
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST') {
    handlePostRequest();
} else {
    echo json_encode(['status' => 0, 'message' => 'Unsupported request method']);
}
?>
