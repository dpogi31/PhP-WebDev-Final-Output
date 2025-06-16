<?php
session_start();
include __DIR__ . '/db_connect.php';

require __DIR__ . '/vendor/autoload.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

//  Check if temp session exists (user is not yet verified)
if (!isset($_SESSION['temp_user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

$user_id = $_SESSION['temp_user_id'];
$user_email = $_SESSION['temp_user_email'];
$user_name = $_SESSION['temp_user_name'];

//  Generate OTP
$otp = random_int(100000, 999999);
$expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));

//  Store OTP in database
$stmt = $conn->prepare("UPDATE user_reg SET otp_code = ?, otp_expiration = ? WHERE id = ?");
$stmt->bind_param("ssi", $otp, $expires_at, $user_id);
$stmt->execute();
$stmt->close();

//  Send Email
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'danielpogi90gmail@gmail.com';         
    $mail->Password = 'ycsdqdoqmykzllsu';           
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('danielpogi90gmail@gmail.com', 'Chrono Sync');
    $mail->addAddress($user_email, $user_name);
    $mail->Subject = 'Your OTP Code';
    $mail->Body = "Hi $user_name,\n\nYour OTP is: $otp\nIt expires in 5 minutes.";

    $mail->send();
    echo json_encode(['success' => true, 'message' => 'OTP sent successfully.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Mailer Error: ' . $mail->ErrorInfo]);
}

$conn->close();
