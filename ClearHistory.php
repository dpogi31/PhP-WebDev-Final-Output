<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_POST['user_id'] != $_SESSION['user_id']) {
    die("Unauthorized request.");
}

$userId = $_SESSION['user_id'];

// Step 1: Get all order IDs by this user
$stmt = $conn->prepare("SELECT id FROM orders WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$orderIds = [];
while ($row = $result->fetch_assoc()) {
    $orderIds[] = $row['id'];
}
$stmt->close();

// Step 2: Delete order_items
if (!empty($orderIds)) {
    $placeholders = implode(',', array_fill(0, count($orderIds), '?'));
    $types = str_repeat('i', count($orderIds));

    $stmt = $conn->prepare("DELETE FROM order_items WHERE order_id IN ($placeholders)");
    $stmt->bind_param($types, ...$orderIds);
    $stmt->execute();
    $stmt->close();

    // Step 3: Delete orders
    $stmt = $conn->prepare("DELETE FROM orders WHERE id IN ($placeholders)");
    $stmt->bind_param($types, ...$orderIds);
    $stmt->execute();
    $stmt->close();
}

header("Location: CSProfile.php");
exit();
