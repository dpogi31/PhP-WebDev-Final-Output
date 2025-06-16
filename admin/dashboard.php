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
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <div class="dashboard-container">
    <h1>Welcome, Admin 👋</h1>
    <p>Use the options below to manage the website.</p>

    <div class="card-container">
        <a href="products.php" class="admin-card">
            <h3><i class="fas fa-box-open"></i> Manage Products</h3>
            <p>Add, edit, or delete products.</p>
        </a>
        <a href="users.php" class="admin-card">
            <h3><i class="fas fa-users"></i> Manage Users</h3>
            <p>View or manage registered users.</p>
        </a>
        <a href="logout.php" class="admin-card">
            <h3><i class="fas fa-sign-out-alt"></i> Logout</h3>
            <p>Click here to securely sign out.</p>
        </a>
    </div>
</div>



        </div>
    </div>

</body>

</html>