<?php
session_start();
session_unset();
session_destroy();

//  clear session cookie
if (ini_get("session.use_cookies")) {
    setcookie(session_name(), '', time() - 42000, '/');
}

// Redirect to login page
header("Location: login.php");
exit();
?>
