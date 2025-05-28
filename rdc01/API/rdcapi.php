<?php
header('Content-Type: application/json');

// Database connection settings
$serverName = ".\ENDELSQLSERVER";
$connectionOptions = array(
    "Database" => "Endel_weighbridge_RDC",
    "Uid" => "Api_user",
    "PWD" => "9K7r4te^xPN5"
);

// Function to connect to the database
function connectToDatabase($serverName, $connectionOptions) {
    $conn = sqlsrv_connect($serverName, $connectionOptions);
    if ($conn === false) {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Failed to connect to the database',
            'details' => sqlsrv_errors()
        ));
        exit; // Exit after error response
    }
    return $conn;
}

// Function to get PO details from the po_details table
function getPODetails($conn, $PO_NUMBER) {
    $sql = "SELECT ID, V_ID FROM po_details WHERE PO_NUMBER = ?";
    $params = array($PO_NUMBER);
    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    
    $result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($stmt);
    
    return $result;
}

// Function to update cu_orders table
function updateTransactionData($conn, $PO_NUMBER, $MRN_NO, $REVISED_QUANTITY) {
    $REVISED_QUANTITY = (float)$REVISED_QUANTITY; // Ensure it's numeric
    $MRN_NO = (int)$REVISED_QUANTITY;
    // Prepare the SQL query
    $sql = "UPDATE TransactionData 
            SET Final_Netweight = ?, OnlineSyncTime = GETDATE() 
            WHERE PO_No = ? AND MRN_NO = ?";
    
    // Prepare the parameters
    $params = array($REVISED_QUANTITY, $PO_NUMBER, $MRN_NO);
    
    // Debugging: log the query and parameters
    file_put_contents('query_log.txt', "Query: $sql\nParams: " . print_r($params, true) . "\n\n", FILE_APPEND);

    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    sqlsrv_free_stmt($stmt);
}


// Function to update TransactionData table
function updateCUOrders($conn, $PO_ID, $MRN_NO, $revisedQuantity) {
    // Prepare the SQL query to update RDC_NET and NET
    $sql = "UPDATE cu_orders 
            SET RDC_NET = CAST(RDC_NET AS FLOAT) + ?, NET =   ?
            WHERE PO_ID = ? AND MRN_NO = ?";
    
    // Prepare the parameters
    $params = array($revisedQuantity, 0, $PO_ID, $MRN_NO);

    // Log the query and parameters for debugging
    file_put_contents('cu_orders_update_log.txt', "Query: $sql\nParams: " . print_r($params, true) . "\n\n", FILE_APPEND);

    // Execute the query
    $stmt = sqlsrv_query($conn, $sql, $params);

    // Check for errors
    if ($stmt === false) {
        // Log the error for debugging
        file_put_contents('cu_orders_update_log.txt', "Error: " . print_r(sqlsrv_errors(), true) . "\n\n", FILE_APPEND);
        die(json_encode(array(
            'status' => 'error',
            'message' => 'Failed to update cu_orders',
            'details' => sqlsrv_errors()
        )));
    }

    // Free the statement resource
    sqlsrv_free_stmt($stmt);
}


// Function to handle POST requests
function handlePostRequest() {
    global $serverName, $connectionOptions;

    // Read the raw POST data
    $rawData = file_get_contents("php://input");
    $data = json_decode($rawData, true);
    // file_put_contents('updatemrn.txt', $rawData);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Invalid JSON'
        ));
        return;
    }

    // Establish database connection
    $conn = connectToDatabase($serverName, $connectionOptions);

    $responses = [];

    foreach ($data as $entry) {
        if (isset($entry['MRN_NO']) && isset($entry['PO_NUMBER']) && isset($entry['REVISED_QUANTITY']) && isset($entry['UPDATE_BY']) && isset($entry['UPDATE_AT'])) {
            
            // Ensure REVISED_QUANTITY is valid numeric
            $revisedQuantity = $entry['REVISED_QUANTITY'] ?? 0;
            if (!is_numeric($revisedQuantity)) {
                $responses[] = array(
                    'status' => 'error',
                    'message' => 'Invalid REVISED_QUANTITY value',
                    'data' => $entry
                );
                continue;
            }

            // Step 1: Get PO details
            $poDetails = getPODetails($conn, $entry['PO_NUMBER']);
            if ($poDetails) {
                $PO_ID = $poDetails['ID'];

                // Step 2: Update cu_orders table
                updateCUOrders($conn, $PO_ID, $entry['MRN_NO'], $revisedQuantity);

                // Step 3: Update TransactionData table
                updateTransactionData($conn, $entry['PO_NUMBER'], $entry['MRN_NO'], $revisedQuantity);

                // Prepare successful response for this entry
                $responses[] = array(
                    'status' => 'success',
                    'MRN_NO' => $entry['MRN_NO'],
                    'PO_NUMBER' => $entry['PO_NUMBER'],
                    'REVISED_QUANTITY' => $revisedQuantity,
                    'UPDATE_BY' => $entry['UPDATE_BY'],
                    'UPDATE_AT' => $entry['UPDATE_AT'],
                    'PO_ID' => $PO_ID,
                    'V_ID' => $poDetails['V_ID']
                );
            } else {
                // If PO_NUMBER not found in po_details, return error for this entry
                $responses[] = array(
                    'status' => 'error',
                    'message' => 'PO_NUMBER not found in po_details',
                    'PO_NUMBER' => $entry['PO_NUMBER']
                );
            }
        } else {
            // If any required parameters are missing, return error for this entry
            $responses[] = array(
                'status' => 'error',
                'message' => 'Missing required parameters',
                'data' => $entry
            );
        }
    }

    // Close the database connection
    sqlsrv_close($conn);

    // Return the responses
    echo json_encode($responses);
}

// Handle the request
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    handlePostRequest();
} else {
    echo json_encode(array(
        'status' => 'error',
        'message' => 'Unsupported request method'
    ));
}
?>
