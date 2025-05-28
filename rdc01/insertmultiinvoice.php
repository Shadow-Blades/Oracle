<?php
 
include "config.php";
include "phpmailer.php";
 
$response = array();
$id = '4';
 
// Decode input data
$entityBody = file_get_contents('php://input');
$data = json_decode($entityBody, true);
 
file_put_contents('updateinvoice.txt', $entityBody);
 
// Variables from input
$x = $data["selectedRows"];
$invoicenumbertwo = $data['invoiceFile'];
$totalammont = $data['totalammont'];
$invoicenumber = $data['invoicenumber'];
$invoicedate = $data['invoicedate'];
$totalweight = $data['totalweight'];
 
foreach ($x as $key => $value) {
    // Save PDF invoice
    $invoice_two_path = 'images/' . $key . $value . 'multi.pdf';
    file_put_contents($invoice_two_path, base64_decode($invoicenumbertwo));
 
    // Insert invoice into the database
    $sqlinvoiceinsert = sqlsrv_query($connection, "INSERT INTO Table_1 
        (name, status, po_id, order_id, approved_by, date, qty, amount, invoicenumber, invoicedate, sapstatus)
        VALUES ('$invoice_two_path', 'Pending', '$value', '$key', '2', GETDATE(), '$totalweight', '$totalammont', '$invoicenumber', '$invoicedate', 'In Progress')");
    // Update order status
    $update = sqlsrv_query($connection, "UPDATE cu_orders SET withinvoice = NULL WHERE id = '$key'");
    // Retrieve vendor details for email
    $query = sqlsrv_query($connection, "SELECT PO_NUMBER, V_ID, VENDOR_EMAIL, CONTACT_PERSON FROM po_details WHERE ID = '$value' AND VENDOR_EMAIL IS NOT NULL");
    $vendorDetails = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
 
    if ($vendorDetails) {
        $to = $vendorDetails['VENDOR_EMAIL'];
        $contactPerson = $vendorDetails['CONTACT_PERSON'];
        $poNumber = $vendorDetails['PO_NUMBER'];
        $vendorId = $vendorDetails['V_ID'];
 
        // Email subject
        $subject = "Invoice Received - PO Number: $poNumber";
 
        // Construct email message
        $message = "
<html>
<body>
<p>Dear $contactPerson,</p>
<p>This is an auto-generated mailer regarding invoice no. <strong>$invoicenumber</strong>.</p>
<p>We have successfully received the invoice. For further updates, please check the vendor portal in the uploaded invoices section against the same PO number.</p>
<h3>Invoice Details</h3>
<table border='1' cellpadding='5' cellspacing='0'>
<tr>
<th>Vendor ID</th>
<th>PO Number</th>
<th>Total Quantity (kg)</th>
<th>Invoice Date</th>
<th>Invoice Number</th>
<th>Total Amount</th>
</tr>
<tr>
<td>$vendorId</td>
<td>$poNumber</td>
<td>$totalweight</td>
<td>$invoicedate</td>
<td>$invoicenumber</td>
<td>$totalammont</td>
</tr>
</table>
 
            <p>Thanks,<br>RDC Concrete</p>
</body>
</html>";
 
        // Send email using PHPMailer
        try {
            $mail = new PHPMailer(true);
            $mail->setFrom('no-reply@rdc.in', 'RDC-Concrete');
            $mail->addAddress('kanhaiya.jha@rdc.in');
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;
            $mail->send();
 
            $response['status'] = 1;
            $response['message'] = "Invoice email sent to $to.";
        } catch (Exception $e) {
            $response['status'] = 0;
            $response['message'] = "Failed to send email to $to: " . $mail->ErrorInfo;
        }
    }
}
 
echo json_encode($response);
sqlsrv_close($connection);
 
?>