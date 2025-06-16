<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: ../login.php");
    exit();
}

require '../db_connect.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: users.php");
exit();
