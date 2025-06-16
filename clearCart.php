<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: CSCart.php');
    exit;
}

// chinecheck ang CSRF token mula sa form at session ay magkapareho (security check)
if (!isset($_POST['csrf_token'], $_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    // Kung walang CSRF token o hindi pareho, babalik sa cart page
    header('Location: CSCart.php');
    exit;
}

// clear ang laman ng cart sa session (i-delete ang buong cart)
unset($_SESSION['cart']);  

// redirect pabalik sa cart page matapos ma-clear
header('Location: CSCart.php');
exit;
