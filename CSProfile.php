<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: CSLandingPage.php");
    exit();
}

require 'db_connect.php'; // Adjust the path if necessary

$userId = $_SESSION['user_id'];
$userName = 'N/A';
$userEmail = 'N/A';

// Fetch user details
$stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $userName = $row['username'];
    $userEmail = $row['email'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Profile - Chrono Sync</title>
    <link rel="stylesheet" href="CS.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        .profile-container {
            background-color: #222;
            padding: 30px;
            max-width: 600px;
            margin: 60px auto;
            border-radius: 10px;
            color: #fff;
            font-family: 'Raleway', sans-serif;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.4);
        }

        .profile-container h2,
        .profile-container h3 {
            font-family: 'Roboto', sans-serif;
            color: #f0b028;
            margin-bottom: 15px;
            border-bottom: 2px solid #f0b028;
            padding-bottom: 8px;
        }

        .profile-info {
            margin: 12px 0;
            padding: 12px 18px;
            background-color: #2d2d2d;
            border-radius: 6px;
            font-size: 1.05rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .profile-info strong {
            color: #aaa;
            min-width: 90px;
        }

        .profile-actions {
            margin-top: 30px;
            display: flex;
            justify-content: center;
        }

        .profile-btn {
            padding: 10px 24px;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s ease;
            background-color: #f0b028;
            color: #000;
            text-decoration: none;
        }

        .profile-btn:hover {
            opacity: 0.85;
        }

        .purchase-group {
            margin-top: 20px;
            background-color: #1b1b1b;
            border-radius: 6px;
            padding: 10px 15px;
        }

        .purchase-group h4 {
            color: #ccc;
            margin-bottom: 8px;
        }

        .purchase-item {
            display: flex;
            justify-content: space-between;
            font-size: 0.95rem;
            color: #eee;
            padding: 6px 0;
            border-bottom: 1px solid #333;
        }

        .purchase-item:last-child {
            border-bottom: none;
        }

        .no-purchases {
            color: #aaa;
            margin-top: 10px;
        }

        .clear-history-form {
            text-align: center;
            margin-top: 25px;
        }

        .clear-history-form button {
            background-color: crimson;
            color: white;
            border: none;
            padding: 10px 20px;
            font-weight: bold;
            font-size: 0.95rem;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .clear-history-form button:hover {
            background-color: darkred;
        }
    </style>
</head>

<body class="body1">
    <div class="profile-container">
        <h2><i class="fas fa-user"> Profile</i></h2>


        <div class="profile-info">
            <strong>User ID:</strong>
            <span><?= htmlspecialchars($userId); ?></span>
        </div>

        <div class="profile-info">
            <strong>Name:</strong>
            <span><?= htmlspecialchars($userName); ?></span>
        </div>

        <div class="profile-info">
            <strong>Email:</strong>
            <span><?= htmlspecialchars($userEmail); ?></span>
        </div>

        <div class="profile-actions">
            <a href="CSHomePage.php" class="profile-btn">Back to Dashboard</a>
        </div>

        <!-- Purchase History -->
        <h3>🛍 Purchase History</h3>
        <?php
        $stmt = $conn->prepare("
            SELECT o.id AS order_id, o.order_date, o.total_amount,
                   i.product_name, i.quantity, i.price, i.subtotal
            FROM orders o
            JOIN order_items i ON o.id = i.order_id
            WHERE o.user_id = ?
            ORDER BY o.order_date DESC, i.id ASC
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $purchases = [];
        while ($row = $result->fetch_assoc()) {
            $orderId = $row['order_id'];
            if (!isset($purchases[$orderId])) {
                $purchases[$orderId] = [
                    'date' => $row['order_date'],
                    'total' => $row['total_amount'],
                    'items' => []
                ];
            }
            $purchases[$orderId]['items'][] = $row;
        }

        if (count($purchases) > 0) {
            foreach ($purchases as $orderId => $order) {
                echo "<div class='purchase-group'>";
                echo "<h4>Order #$orderId • " . date('F j, Y, g:i a', strtotime($order['date'])) . "</h4>";
                foreach ($order['items'] as $item) {
                    echo "<div class='purchase-item'>";
                    echo "<div>" . htmlspecialchars($item['product_name']) . " × " . $item['quantity'] . "</div>";
                    echo "<div>₱" . number_format($item['subtotal'], 2) . "</div>";
                    echo "</div>";
                }
                echo "<div class='purchase-item' style='font-weight:bold; border-top: 1px solid #444; padding-top: 8px;'>";
                echo "<div>Total:</div>";
                echo "<div>₱" . number_format($order['total'], 2) . "</div>";
                echo "</div>";
                echo "</div>";
            }

            // Clear History Form
            echo '<form class="clear-history-form" action="ClearHistory.php" method="POST" onsubmit="return confirm(\'Are you sure you want to clear your purchase history?\');">';
            echo '<input type="hidden" name="user_id" value="' . $userId . '">';
            echo '<button type="submit"> Clear Purchase History</button>';
            echo '</form>';
        } else {
            echo "<p class='no-purchases'>No purchases found.</p>";
        }
        ?>
    </div>
</body>

</html>