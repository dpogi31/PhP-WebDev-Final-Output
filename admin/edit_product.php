<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: ../login.php");
    exit();
}

include '../db_connect.php'; 

// Get existing product info
$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $price = $_POST["price"];
    $description = $_POST["description"];

    // Handle optional image update
    $image = $product['image'];
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === 0) {
        $image = basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], "../uploads/" . $image);
    }

    $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, image = ?, description = ? WHERE id = ?");
    $stmt->bind_param("sdssi", $name, $price, $image, $description, $id);
    $stmt->execute();

    header("Location: products.php?updated=1");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Product</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h2>Edit Product</h2>

    <form method="POST" enctype="multipart/form-data">
        <label>Product Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

        <label>Price (₱)</label>
        <input type="number" name="price" step="0.01" value="<?= $product['price'] ?>" required>

        <label>Description</label>
        <input type="text" name="description" value="<?= htmlspecialchars($product['description']) ?>" required>

        <label>Current Image</label><br>
        <?php if ($product['image']): ?>
            <img src="../uploads/<?= $product['image'] ?>" width="100" style="margin-bottom: 10px;"><br>
        <?php else: ?>
            <p>No image uploaded.</p>
        <?php endif; ?>

        <label>Change Image</label>
        <input type="file" name="image" accept="image/*">

        <div class="form-buttons">
            <button type="submit" class="button-primary">Update Product</button>
            <a href="products.php" class="button-secondary">Cancel</a>
        </div>


    </form>

</body>

</html>