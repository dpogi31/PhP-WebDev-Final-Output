<?php
session_start();
require 'db_connect.php';

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("Invalid CSRF token. Action denied.");
}

$payment_method = $_POST['payment_method'] ?? null;
if (!$payment_method) {
    die("Please select a payment method.");
}

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    echo "Cart is empty!";
    exit();
}

$_SESSION['payment_method'] = $payment_method;

$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Checkout Review</title>
    <link rel="stylesheet" href="CS.css">
    <style>
        body {
            font-family: 'Roboto';
            margin: 30px;
            background-image: url(assets/images/BG.jpg);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .checkout-container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #008fe2;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .total {
            text-align: right;
            font-weight: bold;
            font-size: 1.2em;
        }

        .form-section {
            margin-top: 30px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
            resize: vertical;
        }

        .btn-confirm {
            background: #008fe2;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            cursor: pointer;
        }

        .btn-confirm:hover {
            background: #333;
        }
    </style>
</head>

<body>
    <div class="checkout-container">
        <h1>Checkout Summary</h1>
        <p>Payment Method: <strong><?= htmlspecialchars($payment_method) ?></strong></p>

        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td>₱<?= number_format($item['price'], 2) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td>₱<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p class="total">Grand Total: ₱<?= number_format($total, 2) ?></p>

        <form action="placeOrder.php" method="post" class="form-section">
            <label for="full_name">Full Name:</label>
            <input type="text" name="full_name" required>

            <label for="shipping_address">Shipping Address:</label>
            <textarea name="shipping_address" required></textarea>

            <label for="billing_address">Billing Address:</label>
            <textarea name="billing_address" required></textarea>

            <label for="phone_number">Phone Number:</label>
            <input type="text" name="phone_number" required>

            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="hidden" name="payment_method" value="<?= htmlspecialchars($payment_method) ?>">
            <button type="submit" class="btn-confirm">Place Order</button>
        </form>
    </div>
</body>

</html>