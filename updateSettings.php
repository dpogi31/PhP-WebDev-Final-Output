<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: CSLandingPage.php");
    exit();
}

$userId = $_SESSION['user_id'];
$newUsername = trim($_POST['username'] ?? '');
$newPassword = trim($_POST['new_password'] ?? '');
$confirmPassword = trim($_POST['confirm_password'] ?? '');

// Update username if not empty
if (!empty($newUsername)) {
    $stmt = $conn->prepare("UPDATE user_reg SET user_name = ? WHERE id = ?");
    $stmt->bind_param("si", $newUsername, $userId);
    $stmt->execute();
    $_SESSION['username'] = $newUsername; 
    $stmt->close();
}

// Update password if both fields are filled
if (!empty($newPassword) && !empty($confirmPassword)) {
    if ($newPassword !== $confirmPassword) {
        echo json_encode(['success' => false, 'message' => '⚠️ Passwords do not match.']);
        exit;
    }
    if (strlen($newPassword) < 6) {
        echo json_encode(['success' => false, 'message' => '⚠️ Password too short.']);
        exit;
    }

    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE user_reg SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $hashedPassword, $userId);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
header("Location: CSSettings.php?updated=1");
exit();
?>
