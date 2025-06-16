<?php
session_start();
include 'db_connect.php';

// Redirect if temp session not set
if (!isset($_SESSION['temp_user_id'])) {
    header("Location: login.php");
    exit();
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_otp = $_POST['otp'];
    $user_id = $_SESSION['temp_user_id'];

    $stmt = $conn->prepare("SELECT otp_code, otp_expiration, username, email FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if (!preg_match('/^\d{6}$/', $entered_otp)) {
        $message = "Invalid OTP format.";
    } elseif ($user && $entered_otp === $user['otp_code'] && strtotime($user['otp_expiration']) >= time()) {
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        session_regenerate_id(true);

        unset($_SESSION['temp_user_id'], $_SESSION['temp_user_email'], $_SESSION['temp_user_name']);

        $stmt = $conn->prepare("UPDATE users SET otp_code = NULL, otp_expiration = NULL WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();

        header("Location: CSHomePage.php");
        exit();
    } else {
        $message = "Invalid or expired OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Verify OTP - Chrono Sync</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');

        body {
            margin: 0;
            padding: 0;
            background-image: url(assets/images/BG.jpg);
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Roboto', sans-serif;
        }

        .card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 30px;
            width: 350px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            color: #fff;
        }

        .card h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color:rgb(0, 0, 0);
        }

        input[type="text"] {
            padding: 12px;
            width: 90%;
            margin: 10px 0;
            border: none;
            border-radius: 8px;
            font-size: 16px;
        }

        button {
            padding: 12px 20px;
            border: none;
            background: #f0b028;
            color: #000;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 10px;
        }

        button:hover {
            background:rgb(66, 66, 66);
            color: white;
        }

        .error {
            color: #ff8080;
            margin-top: 10px;
        }

        .resend {
            background: transparent;
            color:rgb(7, 7, 7);
            text-decoration: underline;
            border: none;
            margin-top: 10px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="card">
        <h2>Two-Factor Authentication</h2>
        <?php if (!empty($message)): ?>
            <p class="error"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="otp" placeholder="Enter 6-digit OTP" maxlength="6" required />
            <br>
            <button type="submit">Verify</button>
        </form>

        <form method="POST" action="resend_otp.php">
            <button class="resend" type="submit">Resend OTP</button>
        </form>
    </div>
</body>

</html>