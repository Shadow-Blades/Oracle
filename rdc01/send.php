<?php
session_start();
include "phpmailer.php";

// Generate a random OTP
$otp = rand(100000, 999999);

// Store OTP in session
$_SESSION['otp'] = "12345";

// Email details
$to = "nik60607@gmail.com";
$subject = 'Your OTP';
$message = 'Your OTP is: ' ;

try {
    // Send email
    $mail->setFrom('nik.823840@gmail.com', 'Nikunjgiri Goswami');
    $mail->addAddress($to);
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->send();
    echo 'OTP has been sent to your email.';
} catch (Exception $e) {
    echo 'Failed to send OTP. Please try again later.';
}
?>