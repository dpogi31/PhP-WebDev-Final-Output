<?php
session_start();

// Log errors but don't display them (prevents breaking JSON output)
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

include __DIR__ . '/db_connect.php';

require __DIR__ . '/vendor/autoload.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

try {
    // Check if required POST fields exist
    if (!isset($_POST['login_email'], $_POST['login_password'])) {
        echo json_encode(['success' => false, 'message' => 'Please fill in all fields.']);
        exit;
    }

    $email = $_POST['login_email'];
    $password = $_POST['login_password'];

    // Query the user
    $stmt = $conn->prepare("SELECT id, user_name, email, password FROM user_reg WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Check password
        if (password_verify($password, $user['password'])) {
            // Temporarily store user info for OTP verification
            $_SESSION['temp_user_id'] = $user['id'];
            $_SESSION['temp_user_email'] = $user['email'];
            $_SESSION['temp_user_name'] = $user['user_name'];
            $_SESSION['username'] = $user['user_name'];
            // Generate OTP
            $otp = random_int(100000, 999999);
            $expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));

            // Save OTP
            $stmt->close();
            $stmt = $conn->prepare("UPDATE user_reg SET otp_code = ?, otp_expiration = ? WHERE id = ?");
            $stmt->bind_param("ssi", $otp, $expires_at, $user['id']);
            $stmt->execute();
            $stmt->close();

            // Send Email with PHPMailer
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'danielpogi90@gmail.com'; 
            $mail->Password = 'ycsdqdoqmykzllsu';       
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('danielpogi90@gmail.com', 'Chrono Sync');
            $mail->addAddress($user['email']);
            $mail->Subject = 'Your OTP Code';
            $mail->Body = "Hi {$user['user_name']},\n\nYour OTP is: $otp\nIt expires in 5 minutes.";

            if ($mail->send()) {
                echo json_encode(['success' => true, '2fa' => true, 'redirect' => 'verify_otp.php']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to send OTP. Please try again later.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid password.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Email not found.']);
    }

    $conn->close();

} catch (Throwable $e) {
    
    echo json_encode(['success' => false, 'message' => 'A server error occurred. Please try again later.']);
}
