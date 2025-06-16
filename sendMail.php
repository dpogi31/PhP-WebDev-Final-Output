<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


require __DIR__ . '/vendor/autoload.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


$mail = new PHPMailer(true);

try {
    
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'Danielpogi90@gmail.com';      
    $mail->Password   = 'ycsdqdoqmykzllsu';             
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

   
    $mail->setFrom('danielpogi90@gmail.com', 'Daniel'); 
    $mail->addAddress('danielpogi90@gmail.com', 'Friend'); 

    
    $mail->isHTML(true);
    $mail->Subject = 'Hello from PHPMailer';
    $mail->Body    = 'This is a <b>test email</b> sent using PHPMailer.';
    $mail->AltBody = 'This is a plain-text version of the email body.';

    $mail->send();
    echo ' Message has been sent successfully!';
} catch (Exception $e) {
    echo " Message could not be sent. Error: {$mail->ErrorInfo}";
}
