<?php
session_start();
require 'db_connect.php';

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("Invalid CSRF token.");
}

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    die("Your cart is empty.");
}

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    die("User not logged in.");
}

$payment_method = $_SESSION['payment_method'] ?? null;
if (!$payment_method) {
    die("Payment method missing.");
}

$user_fullname = $_POST['full_name'] ?? 'Guest User';
$shipping_address = $_POST['shipping_address'] ?? '';
$billing_address = $_POST['billing_address'] ?? '';
$phone_number = $_POST['phone_number'] ?? '';

// Get current date and time
$order_date = date('Y-m-d H:i:s');

// Calculate total
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Insert into orders table
$stmt = $conn->prepare("INSERT INTO orders (user_id, full_name, total_amount, payment_method, order_date, shipping_address, billing_address, phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isdsssss", $userId, $user_fullname, $total, $payment_method, $order_date, $shipping_address, $billing_address, $phone_number);
$stmt->execute();
$order_id = $stmt->insert_id;
$stmt->close();

// Insert each item in order_items table
$stmt = $conn->prepare("INSERT INTO order_items (order_id, product_name, price, quantity, subtotal) VALUES (?, ?, ?, ?, ?)");
foreach ($cart as $item) {
    $name = $item['name'];
    $price = $item['price'];
    $qty = $item['quantity'];
    $subtotal = $price * $qty;
    $stmt->bind_param("isdid", $order_id, $name, $price, $qty, $subtotal);
    $stmt->execute();
}
$stmt->close();

// Store receipt data
$receipt_items = $cart;

// Clear cart session
unset($_SESSION['cart']);
unset($_SESSION['payment_method']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="CS.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto';
            background: #f4f4f4;
            padding: 50px;
            background-image: url(assets/images/BG.jpg);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .confirmation-container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            max-width: 750px;
            margin: auto;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .success-icon {
            font-size: 60px;
            color: green;
            margin-bottom: 20px;
        }

        .confirmation-container h1 {
            color: #008fe2;
        }

        .button-group {
            margin-top: 30px;
        }

        .button-group a {
            display: inline-block;
            padding: 12px 25px;
            background-color: #008fe2;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 25px;
            transition: background-color 0.3s ease;
            margin: 0 10px;
        }

        .button-group a:hover {
            background-color: #333;
        }

        .receipt {
            margin-top: 30px;
            text-align: left;
        }

        .receipt h2 {
            margin-bottom: 10px;
            color: #008fe2;
        }

        .receipt table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .receipt table th,
        .receipt table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        .receipt table th {
            background-color: #f0f0f0;
        }

        .receipt .total {
            text-align: right;
            font-weight: bold;
            padding-top: 10px;
        }

        .user-info {
            margin-top: 20px;
            text-align: left;
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 10px;
        }

        .user-info h3 {
            margin: 0 0 10px;
            color: #444;
        }

        .user-info p {
            margin: 5px 0;
            line-height: 1.4;
        }
    </style>
</head>
<body>
    <div class="confirmation-container">
        <div class="success-icon"><i class="fas fa-check-circle"></i></div>
        <h1>Thank You!</h1>
        <p>You selected <strong><?= htmlspecialchars($payment_method) ?></strong> as your payment method.</p>
        <p>Your order has been placed successfully. Your order ID is
            <strong>#<?= htmlspecialchars($order_id) ?></strong>.</p>
        <p>Order Date & Time: <strong><?= htmlspecialchars($order_date) ?></strong></p>

        <div class="user-info">
            <h3>Shipping/Billing Information</h3>
            <p><strong><?= htmlspecialchars($user_fullname) ?></strong></p>
            <p><strong>Shipping Address:</strong> <?= nl2br(htmlspecialchars($shipping_address)) ?></p>
            <p><strong>Billing Address:</strong> <?= nl2br(htmlspecialchars($billing_address)) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($phone_number) ?></p>
        </div>

        <div class="receipt">
            <h2>Receipt</h2>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($receipt_items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td>₱<?= number_format($item['price'], 2) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>₱<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p class="total">Grand Total: <strong>₱<?= number_format($total, 2) ?></strong></p>
        </div>

        <div class="button-group">
            <a href="CSHomePage.php">Back to Home</a>
            <a href="CSProducts.php">Shop More</a>
        </div>
    </div>
</body>
</html>
