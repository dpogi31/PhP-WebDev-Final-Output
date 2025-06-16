<?php
session_start();
// Prevent back button from accessing cached pages after logout
header("Cache-Control: no-cache, no-store, must-revalidate"); 
header("Pragma: no-cache"); 
header("Expires: 0"); 

if (!isset($_SESSION["admin"])) {
  header("Location: login.php");
  exit();
}
include '../db_connect.php';

// Handle deletion
if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $message = "Product deleted successfully!";
}

$result = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html>

<head>
  <title>Admin Products</title>
  <link rel="stylesheet" href="style.css">
  <style>
  .btn-back {
  display: inline-block;
  margin: 10px 0;
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
  <h2>Product Management</h2>

  
  <a href="dashboard.php" class="btn-back">Back to Dashboard</a>

  <?php if (isset($message))
    echo "<p class='success-message'>$message</p>"; ?>

  <a href="add_product.php" class="btn-add">+ Add New Product</a>


    <table class="product-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Price</th>
          <th>Image</th>
          <th>Description</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td>₱<?= number_format($row['price'], 2) ?></td>
            <td>
              <?php if (!empty($row['image'])): ?>
                <img src="/ForDefense/uploads/<?= $row['image'] ?>" width="60">
              <?php else: ?>
                No image
              <?php endif; ?>
            </td>

            <td>
              <?= isset($row['description']) ? htmlspecialchars($row['description']) : 'No description' ?>
            </td>

            <td>
              <div class="action-buttons">
                <a href="edit_product.php?id=<?= $row['id'] ?>" class="btn-edit">Edit</a>
                <a href="products.php?delete=<?= $row['id'] ?>" class="btn-delete"
                  onclick="return confirm('Delete this product?')">Delete</a>
              </div>
            </td>

          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

</body>

</html>