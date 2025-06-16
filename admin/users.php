<?php
session_start();
// Prevent back button from accessing cached pages after logout
header("Cache-Control: no-cache, no-store, must-revalidate"); 
header("Pragma: no-cache"); 
header("Expires: 0"); 

if (!isset($_SESSION["admin"])) {
    header("Location: ../login.php");
    exit();
}

require '../db_connect.php'; 

$result = $conn->query("SELECT id, username, email, role FROM users");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <link rel="stylesheet" href="style.css">
    <style>
    .btn-back {
    display: inline-block;
    margin-top: 15px;
    padding: 13px 20px;
    background-color: #ddd;
    color: #333;
    text-decoration: none;
    border-radius: 6px;
    transition: background-color 0.3s ease;
    font-size: 14px;
}

.btn-back:hover {
    background-color:rgb(128, 128, 128); 
    color:white
}

</style>
</head>

<body>

<div class="container">
    <h2>👥 Manage Users</h2>

    <table class="product-table">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['role']) ?></td> 
            <td>
                <a class="btn-delete" href="delete_user.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <a href="dashboard.php" class="btn-back">Back to Dashboard</a>
</div>


</body>
</html>
