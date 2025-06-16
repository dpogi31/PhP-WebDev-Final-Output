<?php
session_start();
include __DIR__ . '/db_connect.php';

require __DIR__ . '/vendor/autoload.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['temp_user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['temp_user_id'];
$user_email = $_SESSION['temp_user_email'];
$user_name = $_SESSION['temp_user_name'];

// Generate new OTP
$otp = random_int(100000, 999999);
$expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));

// Update OTP in database
$stmt = $conn->prepare("UPDATE users SET otp_code = ?, otp_expiration = ? WHERE id = ?");

$stmt->bind_param("ssi", $otp, $expires_at, $user_id);
$stmt->execute();
$stmt->close();

// Send Email
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'danielpogi90@gmail.com';
    $mail->Password = 'ycsdqdoqmykzllsu'; 
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('danielpogi90@gmail.com', 'Chrono Sync');
    $mail->addAddress($user_email);
    $mail->Subject = 'Your New OTP Code';
    $mail->Body = "Hi $user_name,\n\nYour new OTP is: $otp\nIt will expire in 5 minutes.";

    $mail->send();

    header("Location: verify_otp.php?resent=1");
    exit();
} catch (Exception $e) {
    echo "Failed to resend OTP. Mailer Error: {$mail->ErrorInfo}";
}
?>
