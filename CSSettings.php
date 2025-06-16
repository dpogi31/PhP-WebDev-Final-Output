<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: CSLandingPage.php");
    exit();
}
$userId = $_SESSION['user_id'];

// Fetch user data from DB
$sql = "SELECT username, email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();

$userName = $user['user_name'] ?? '';
$userEmail = $user['email'] ?? '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $newPassword = trim($_POST['new_password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');

    if (empty($newPassword) || empty($confirmPassword)) {
        $message = "⚠️ Both password fields are required.";
    } elseif ($newPassword !== $confirmPassword) {
        $message = "⚠️ Passwords do not match.";
    } elseif (strlen($newPassword) < 6) {
        $message = "⚠️ Password must be at least 6 characters long.";
    } else {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("si", $hashedPassword, $userId);
            if ($stmt->execute()) {
                $message = " Password updated successfully!";
            } else {
                $message = " Failed to update password: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = " Database error: " . $conn->error;
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Settings | Chrono Sync</title>
    <link rel="stylesheet" href="CS.css" />
    <style>
        body {
            background-image: url(assets/images/BG.jpg);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: #fff;
            font-family: 'Raleway', sans-serif;
        }

        .settings-container {
            max-width: 600px;
            margin: 100px auto;
            background: #1e1e1e;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(240, 176, 40, 0.3);
        }

        .settings-container h2 {
            text-align: center;
            color: #f0b028;
            margin-bottom: 30px;
        }

        .settings-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .settings-container input[type="email"],
        .settings-container input[type="password"],
        .settings-container input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #555;
            border-radius: 6px;
            background-color: #2c2c2c;
            color: white;
            font-size: 1rem;
            margin-bottom: 20px;
        }


        .settings-container .form-actions {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .settings-container .form-actions .btn-action {
            background-color: #f0b028;
            color: #000;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .settings-container .form-actions .btn-action:hover {
            background-color: rgb(156, 108, 2);
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1rem;
            font-weight: bold;
            color: #f0b028;
        }
    </style>
</head>

<body>

    <div class="settings-container">
        <h2>Account Settings</h2>

        <?php if (!empty($message))
            echo "<div class='message'>$message</div>"; ?>

        <form action="updateSettings.php" method="POST">

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userEmail); ?>" readonly>

            <label for="username">New Username</label>
            <input type="text" id="username" name="username" placeholder="Enter New Username"
                value="<?php echo htmlspecialchars($userName); ?>" required>


            <label for="new_password">New Password</label>
            <input type="password" id="new_password" name="new_password" placeholder="Enter new password">

            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password">

            <div class="form-actions">
                <button type="submit" class="btn-action">Save</button>
                <a href="CSHomePage.php" class="btn-action">Cancel</a>
            </div>
        </form>
    </div>

</body>

</html>