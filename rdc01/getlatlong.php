<?php
include("config.php");

$response = array();

if (isset($_REQUEST['Sitename']) && !empty($_REQUEST['Sitename'])) {
    // Get the Sitename from the request
    $sitename = $_REQUEST['Sitename'];

    // Prepare the SQL query to select latitude and longitude for the given Sitename
    $sql = sqlsrv_query($connection, "SELECT Sitename, lat, long FROM LocationMaster WHERE Sitename = ?", array($sitename));

    if ($sql === false) {
        $response['status'] = 0;
        $response['message'] = "Error in query execution.";
    } else {
        $row = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC);

        if ($row) {
            // If data is found, return it in the response
            $response['status'] = 1;
            $response['Sitename'] = $row['Sitename'];
            $response['latitude'] = $row['lat'];
            $response['longitude'] = $row['long'];
        } else {
            // If no data is found
            $response['status'] = 0;
            $response['message'] = "No record found for the given Sitename.";
        }
    }

    // Close the connection
    sqlsrv_close($connection);
} else {
    $response['status'] = 0;
    $response['message'] = "Sitename parameter is missing.";
}

// Return the response as JSON
echo json_encode($response);
?>
