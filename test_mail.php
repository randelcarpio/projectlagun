<?php
require 'libs/PHPMailer-master/src/Exception.php';
require 'libs/PHPMailer-master/src/PHPMailer.php';
require 'libs/PHPMailer-master/src/SMTP.php';

$mail = new PHPMailer\PHPMailer\PHPMailer();
$mail->isSMTP();
$mail->SMTPDebug = 2;
$mail->Host = 'smtp-relay.sendinblue.com';
$mail->Port = 587;
$mail->SMTPAuth = true;
$mail->Username = 'projectlagun@gmail.com';
$mail->Password = 'LJs4gqRby8EwD3ha';
$mail->setFrom('projectlagun@gmail.com', 'Randel');
$mail->addAddress('randel.mail13@gmail.com', 'Mr. Carpio');
$mail->Subject = 'Test PHP Mailer';
$mail->Body = 'This is a test email using PHP Mailer';

if (!$mail->send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message sent!';
}
?>
