<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: ../login.php");
    exit();
}
include '../db_connect.php'; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $price = $_POST["price"];
    $description = $_POST["description"];
    
    $image = "";
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === 0) {
        $image = time() . "_" . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], "../uploads/" . $image);
    }

    $stmt = $conn->prepare("INSERT INTO products (name, price, image, description) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdss", $name, $price, $image, $description);
    $stmt->execute();
    header("Location: products.php?added=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product | Admin Panel</title>
    <link rel="stylesheet" href="style.css"> 
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background-color: #f7f7f7;
        }
        h2 {
            color: #333;
        }
        form {
            background: #fff;
            padding: 20px 30px;
            border-radius: 10px;
            max-width: 500px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }
        input[type="text"],
        input[type="number"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        textarea {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        .btn-group {
            margin-top: 20px;
        }
        button, .button-secondary {
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }
        button {
            background-color: #4CAF50;
            color: white;
        }
        .button-secondary {
            background-color: #ccc;
            text-decoration: none;
            color: black;
            margin-left: 10px;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <h2>➕ Add New Product</h2>

    <form method="POST" enctype="multipart/form-data">
        <label for="name">Product Name</label>
        <input type="text" name="name" id="name" required>

        <label for="price">Price (₱)</label>
        <input type="number" name="price" id="price" step="0.01" required>

        <label for="description">Description</label>
        <input type="text" name="description" id="description" required>

        <label for="image">Image</label>
        <input type="file" name="image" id="image" accept="image/*">

        <div class="btn-group">
            <button type="submit">Save Product</button>
            <a href="products.php" class="button-secondary">Back to Products</a>
        </div>
    </form>

</body>
</html>
