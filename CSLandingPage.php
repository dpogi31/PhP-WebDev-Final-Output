<?php

// Enable error reporting for debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Security headers
header("Content-Security-Policy: default-src 'self'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com; font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; script-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com;");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: no-referrer");

$host = $_SERVER['HTTP_HOST'];
$is_local = in_array($host, ['localhost', '127.0.0.1']);

if (!$is_local && (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off")) {
    $redirect = 'https://' . $host . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}



// Secure session cookie parameters
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();

include 'db_connect.php';
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$register_message = '';
$login_message = '';

// Init login attempts
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_attempt_time'] = time();
}

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// CSRF validation
function validate_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Auto-login with remember_token
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    $remember_token = $_COOKIE['remember_token'];

    $stmt = $conn->prepare("SELECT id, username, token_expires_at FROM users WHERE remember_token = ?");
    $stmt->bind_param("s", $remember_token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $username, $expires_at);
        $stmt->fetch();

        if (strtotime($expires_at) > time()) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user'] = $username;
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // regenerate token
            header("Location: CSHomePage.php");
            exit();
        } else {
            setcookie("remember_token", "", time() - 3600, "/");
            // Optional: Remove token from DB as well
        }
    }
    $stmt->close();
}

// Registration
if (isset($_POST['registerSubmit'])) {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        die('Invalid CSRF token.');
    }

    $age_map = [
        'under_18' => 17,
        '18-25' => 21,
        '26-35' => 30,
        '36-50' => 45,
        'above_50' => 60,
    ];

    $username = filter_var(trim($_POST['signup_username']), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['signup_email']), FILTER_VALIDATE_EMAIL);
    $password = $_POST['signup_password'];
    $confirm_password = $_POST['signup_confirm_password'];
    $gender = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
    $age_group = $_POST['age'] ?? '';

    $valid_ages = array_keys($age_map);
    $valid_genders = ['Male', 'Female', 'Other'];

    if (!in_array($age_group, $valid_ages)) {
        $register_message = "Please select a valid age group.";
    } else {
        $age = $age_map[$age_group];

        if (!$email) {
            $register_message = "Invalid email address.";
        } elseif ($age < 13 || $age > 120) {
            $register_message = "Please enter a valid age between 13 and 120.";
        } elseif (!in_array($gender, $valid_genders)) {
            $register_message = "Invalid gender selection.";
        } elseif ($password !== $confirm_password) {
            $register_message = "Passwords do not match.";
        } elseif (
            strlen($password) < 8 ||
            !preg_match('/[A-Z]/', $password) ||
            !preg_match('/[a-z]/', $password) ||
            !preg_match('/[0-9]/', $password) ||
            !preg_match('/[\W]/', $password)
        ) {
            $register_message = "Password must include uppercase, lowercase, number, special character, and be at least 8 characters.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            $emailExists = $stmt->num_rows > 0;
            $stmt->close();

            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();
            $usernameExists = $stmt->num_rows > 0;
            $stmt->close();

            if ($emailExists) {
                $register_message = "Email already registered.";
            } elseif ($usernameExists) {
                $register_message = "Username already taken.";
            } else {
                $stmt = $conn->prepare("INSERT INTO users (username, email, password, gender, age) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssi", $username, $email, $hashed_password, $gender, $age);
                if ($stmt->execute()) {
                    $register_message = "Registration successful! You can now log in.";
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                } else {
                    $register_message = "Registration error: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    }
}

// Login
if (isset($_POST['loginSubmit'])) {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        exit("Invalid CSRF token.");
    }

    $max_attempts = 3;
    $lockout_time = 60;

    if ($_SESSION['login_attempts'] >= $max_attempts) {
        $elapsed_time = time() - $_SESSION['last_attempt_time'];
        if ($elapsed_time < $lockout_time) {
            $remaining = $lockout_time - $elapsed_time;
            $login_message = "Too many failed attempts. Try again in $remaining seconds.";
            exit;
        } else {
            $_SESSION['login_attempts'] = 0;
        }
    }

    $username = filter_var(trim($_POST['login_username']), FILTER_SANITIZE_STRING);
    $password = $_POST['login_password'];
    $remember = isset($_POST['remember_me']);

    $stmt = $conn->prepare("SELECT id, username, email, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $fetchedUsername, $email, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['login_attempts'] = 0;

            if ($remember) {
                $_SESSION['remember_me'] = true;
            }

            $_SESSION['temp_user_id'] = $id;
            $_SESSION['temp_user_name'] = $fetchedUsername;
            $_SESSION['temp_user_email'] = $email;
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // regenerate after login

            $otp = random_int(100000, 999999);
            $expires = date('Y-m-d H:i:s', strtotime('+5 minutes'));

            $stmt->close();
            $stmt = $conn->prepare("UPDATE users SET otp_code = ?, otp_expiration = ? WHERE id = ?");
            $stmt->bind_param("ssi", $otp, $expires, $id);
            $stmt->execute();
            $stmt->close();

            file_put_contents("log.txt", "OTP sent to user ID $id at ".date('Y-m-d H:i:s')."\n", FILE_APPEND);

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = getenv('SMTP_EMAIL'); // from .env or environment
                $mail->Password = getenv('SMTP_PASSWORD'); // no hardcoded password
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom(getenv('SMTP_EMAIL'), 'Chrono Sync');
                $mail->addAddress($email, $fetchedUsername);
                $mail->Subject = 'Your OTP Code';
                $mail->Body = "Hi $fetchedUsername,\n\nYour OTP is: $otp\nIt will expire in 5 minutes.";

                $mail->send();
            } catch (Exception $e) {
                exit("Failed to send OTP. Please try again.");
            }

            header("Location: verify_otp.php");
            exit;
        } else {
            $login_message = "Invalid password.";
            $_SESSION['login_attempts']++;
            $_SESSION['last_attempt_time'] = time();
        }
    } else {
        $login_message = "Invalid username.";
        $_SESSION['login_attempts']++;
        $_SESSION['last_attempt_time'] = time();
    }

    $stmt->close();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Security-Policy"
        content="default-src 'self'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com; font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; script-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://www.google.com https://www.gstatic.com;">


    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Landing Page</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600&family=Raleway:wght@400;500;600&family=Roboto:wght@400;500;600&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="CS.css" />
</head>

<body>
    <div class="landing-page">
        <div class="landing-container">
            <img src="assets/images/logo1.png" alt="logo" class="landing-logo" />
            <h1>Welcome to <span class="brand" style="font-size: 5rem;">Chrono Sync</span></h1>
            <p>Redefining every second with smart innovation.</p>
            <a href="#" class="landing-page-btn" id="loginBtn">Get Started</a>
        </div>

        <div id="authModal" class="modal">
            <div class="modal-content">
                <span class="close-btn">&times;</span>

                <div class="form-toggle">
                    <button id="loginToggle" class="active">Login</button>
                    <button id="signupToggle">Sign Up</button>
                </div>

                <form id="loginForm" class="form" method="POST" action="">
                    <h2>Login</h2>
                    <?php if ($login_message): ?>
                        <p class="message"><?= htmlspecialchars($login_message) ?></p>
                    <?php endif; ?>

                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />

                    <input type="text" name="login_username" placeholder="Username" required />
                    <input type="password" name="login_password" placeholder="Password" required />


                    <input type="checkbox" id="myCheckbox" name="remember_me" />
                    <label for="myCheckbox">Remember me</label>

                    <a href="google-login.php" class="google-btn">
                        <img src="assets/images/GG.png" alt="Google icon" />
                        Sign in with Google
                    </a>



                    <button type="submit" name="loginSubmit">Login</button>
                </form>

                <?php if (isset($_SESSION['user_email'])): ?>
                    <p>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>!</p>
                    <img src="<?= htmlspecialchars($_SESSION['user_picture']) ?>" alt="Profile Picture" style="width:50px;">
                <?php endif; ?>

                <form id="signupForm" class="form hidden" method="POST" action="">
                    <h2>Sign Up</h2>
                    <?php if ($register_message): ?>
                        <p class="message"><?= htmlspecialchars($register_message) ?></p>
                    <?php endif; ?>
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
                    <input type="text" name="signup_username" placeholder="Username" required />
                    <input type="email" name="signup_email" placeholder="Email" required />
                    <input type="password" name="signup_password" placeholder="Password" required />
                    <input type="password" name="signup_confirm_password" placeholder="Confirm Password" required />
                    <label>Gender:</label>
                    <div class="gender-options">
                        <input type="radio" id="male" name="gender" value="Male" required />
                        <label for="male">Male</label>

                        <input type="radio" id="female" name="gender" value="Female" required />
                        <label for="female">Female</label>

                        <input type="radio" id="other" name="gender" value="Other" required />
                        <label for="other">Other</label>
                    </div>

                    <div class="form-container">
                        <label for="age">Age:</label>
                        <select class="Age" name="age" id="age" required>
                            <option value="under_18">Under 18</option>
                            <option value="18-25">18–25</option>
                            <option value="26-35">26–35</option>
                            <option value="36-50">36–50</option>
                            <option value="above_50">50 and Above</option>
                        </select>
                    </div>

                    <button type="submit" name="registerSubmit">Sign Up</button>
                </form>
            </div>
        </div>

        <footer class="footer-landing">
            <div class="footer-content">
                <h2>Chrono Sync</h2>
                <p>Stay in sync with your health, your style, and your time. Only at Chrono Sync.</p>
                <div class="footer-icons">
                    <a href="https://www.facebook.com/" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/" target="_blank"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.tiktok.com/" target="_blank"><i class="fab fa-tiktok"></i></a>
                    <a href="https://www.youtube.com/" target="_blank"><i class="fab fa-youtube"></i></a>
                    <a href="https://www.linkedin.com/" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© 2025 ChronoSync. All rights reserved.</p>
            </div>
        </footer>
    </div>
    <!--nilagay ko to para automatic na mag bukas ang form kapag may error sa pag gawa ng account -->
    <?php if (!empty($register_message) || !empty($login_message)): ?>
        <script>

            document.addEventListener('DOMContentLoaded', () => {
                document.getElementById('authModal').style.display = 'block';
                <?php if (!empty($register_message)): ?>
                    document.getElementById('loginForm').classList.add('hidden');
                    document.getElementById('signupForm').classList.remove('hidden');
                    document.getElementById('loginToggle').classList.remove('active');
                    document.getElementById('signupToggle').classList.add('active');
                <?php else: ?>
                    document.getElementById('signupForm').classList.add('hidden');
                    document.getElementById('loginForm').classList.remove('hidden');
                    document.getElementById('signupToggle').classList.remove('active');
                    document.getElementById('loginToggle').classList.add('active');
                <?php endif; ?>
            });
        </script>
    <?php endif; ?>


    <script src="CS.js"></script>
</body>

</html>