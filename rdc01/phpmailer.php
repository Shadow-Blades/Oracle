<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// SMTP Configuration
$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp-relay.gmail.com'; // Your SMTP server
$mail->SMTPAuth = true;
$mail->Username = 'no-reply@rdc.in'; // SMTP username
$mail->Password = 'bmtatblcrqurlqrq'; // SMTP password
$mail->SMTPSecure = 'tls';
$mail->Port = 465;





?>