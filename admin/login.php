<?php
// --- Security Headers ---
header("Content-Security-Policy: default-src 'self'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; script-src 'self' 'unsafe-inline';");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("Referrer-Policy: no-referrer");

// --- Force HTTPS ---
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('Location: ' . $redirect, true, 301);
    exit();
}

// --- Secure Session Cookie ---
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();

// --- Include DB ---
require_once __DIR__ . '/../db_connect.php';

// --- Redirect if already logged in ---
if (isset($_SESSION["admin"])) {
    header("Location: dashboard.php");
    exit();
}

// --- CSRF Token ---
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error = "";

// --- Handle POST ---
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate CSRF
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error = "Invalid CSRF token.";
    } else {
        $username = htmlspecialchars(trim($_POST["username"]), ENT_QUOTES, 'UTF-8');
        $password = $_POST["password"];

        // Lookup admin
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ? AND role = 'admin'");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();
        $stmt->close();

        if ($admin && password_verify($password, $admin["password"])) {
            $_SESSION["admin"] = $admin["username"];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid login credentials.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Chrono Sync</title>
    <link rel="stylesheet" href="style.css"> <!-- Your CSS -->
</head>
<body>
<div class="login-container">
    <form method="POST" action="">
        <h2>Admin Login</h2>
        <?php if (!empty($error)) echo "<p class='error'>" . htmlspecialchars($error) . "</p>"; ?>

        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <!-- CSRF Token -->
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

        <button class="admin-login" type="submit">Login</button>
    </form>
</div>
</body>
</html>
