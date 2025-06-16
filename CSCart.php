<?php
session_start();

$cart = $_SESSION['cart'] ?? [];

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Your Cart</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600&family=Raleway:wght@400;500;600&family=Roboto:wght@400;500;600&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="CS.css" />

    <style>
        .cart-actions-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            align-items: flex-start;
            justify-content: space-between;
            margin-top: 30px;
            font-family: "Roboto";
        }

        .cart-actions-container form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .remove-button {
            background-color: crimson;
            color: white;
            padding: 8px 14px;
            font-family: "Roboto";
            font-weight: bold;
            font-size: 0.9em;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .remove-button:hover {
            background-color: darkred;
        }

        .cart-actions-container button,
        .cart-actions-container select {
            padding: 10px 20px;
            font-weight: bold;
            font-family: "Roboto";
            border-radius: 10px;
            border: none;
            font-size: 1em;
        }

        .cart-actions-container button {
            background-color: rgb(2, 102, 160);
            color: white;
            cursor: pointer;
        }

        .cart-actions-container button:hover {
            background-color: rgb(59, 59, 59);
        }

        .cart-actions-container label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .cart-actions-container select {
            background-color: #f0f0f0;
        }
    </style>
</head>

<body class="body1">
    <header class="navbar">
        <div class="logo"><img src="assets/images/logo1.png" alt="logo" /></div>
        <nav class="navcontainer">
            <ul class="navlinks">
                <li><a href="CSHomePage.php">Home</a></li>
                <li><a href="CSProducts.php">Products</a></li>
                <li><a href="CSCart.php">Cart</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h1>Your Cart</h1>

        <?php if (count($cart) > 0): ?>
            <form id="cartForm" action="updateCart.php" method="post">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
                <table class="cart-table" aria-label="Shopping Cart">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $grandTotal = 0;
                        foreach ($cart as $index => $item):
                            $total = $item['price'] * $item['quantity'];
                            $grandTotal += $total;
                            ?>
                            <tr id="cart-item-<?= $index ?>">
                                <td><img src="<?= htmlspecialchars($item['image']) ?>" width="60"
                                        alt="<?= htmlspecialchars($item['name']) ?>" /></td>
                                <td><?= htmlspecialchars($item['name']) ?></td>
                                <td>₱<?= number_format($item['price'], 2) ?></td>
                                <td><input type="number" name="quantities[<?= $index ?>]" value="<?= $item['quantity'] ?>"
                                        min="1" /></td>
                                <td class="subtotal">₱<?= number_format($total, 2) ?></td>
                                <td><button type="button" class="remove-button" data-index="<?= $index ?>">Remove</button></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="grand-total-row">
                            <td colspan="4" style="text-align:right;"><strong>Grand Total:</strong></td>
                            <td colspan="2" id="grandTotal"><strong>₱<?= number_format($grandTotal, 2) ?></strong></td>
                        </tr>
                    </tbody>
                </table>

                <div class="cart-actions-container">
                    <button type="submit">Update Quantities</button>
                </div>
            </form>

            <div class="cart-actions-container">
                <form action="clearCart.php" method="post">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
                    <button type="submit">Clear Cart</button>
                </form>

                <form action="checkout.php" method="post" id="checkoutForm">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
                    <label for="payment_method">Choose Payment Method:</label>
                    <select name="payment_method" id="payment_method" required>
                        <option value="" disabled selected>Select a method</option>
                        <option value="GCash">GCash</option>
                        <option value="PayPal">PayPal</option>
                        <option value="Credit Card">Credit Card</option>
                        <option value="Cash on Delivery">Cash on Delivery</option>
                    </select>
                    <button type="submit">Proceed to Checkout</button>
                </form>
            </div>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </main>

    <footer class="footer-landing">
        <div class="footer-content">
            <h2>Chrono Sync</h2>
            <p>Stay in sync with your health, your style, and your time. Only at Chrono Sync.</p>
            <div class="footer-icons">
                <a href="https://www.facebook.com/" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <a href="https://www.instagram.com/" target="_blank"><i class="fab fa-instagram"></i></a>
                <a href="https://www.tiktok.com/" target="_blank"><i class="fab fa-tiktok"></i></a>
                <a href="https://www.youtube.com/" target="_blank"><i class="fab fa-youtube"></i></a>
                <a href="https://www.linkedin.com/" target="_blank"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2025 ChronoSync. All rights reserved.</p>
        </div>
    </footer>

    <script>
        const csrfToken = '<?= $_SESSION['csrf_token'] ?>';

        function updateGrandTotal() {
            let total = 0;
            document.querySelectorAll('.cart-table tbody tr').forEach(row => {
                const cell = row.querySelector('.subtotal');
                if (cell) {
                    const value = parseFloat(cell.textContent.replace(/[₱,]/g, '')) || 0;
                    total += value;
                }
            });
            const grandTotal = document.getElementById('grandTotal');
            if (grandTotal) {
                grandTotal.innerHTML = `<strong>₱${total.toFixed(2)}</strong>`;
            }
        }

        document.querySelectorAll('.remove-button').forEach(button => {
            button.addEventListener('click', () => {
                if (!confirm('Do you want to remove this?')) return;

                const index = button.getAttribute('data-index');
                fetch('removeFromCart.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ index, csrf_token: csrfToken })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const row = document.getElementById('cart-item-' + index);
                            if (row) row.remove();
                            updateGrandTotal();

                            const remaining = document.querySelectorAll('.cart-table tbody tr:not(.grand-total-row)').length;
                            if (remaining === 0) {
                                document.getElementById('cartForm').remove();
                                document.querySelector('.cart-actions-container').remove();
                                document.querySelector('main').insertAdjacentHTML('beforeend', '<p>Your cart is empty.</p>');
                            }
                        } else {
                            alert('Could not remove item: ' + (data.message || 'Unknown error'));
                        }
                    }).catch(err => alert('Error: ' + err.message));
            });
        });

        document.getElementById('checkoutForm').addEventListener('submit', function (e) {
            if (!document.getElementById('payment_method').value) {
                e.preventDefault();
                alert('Please select a payment method.');
            }
        });
    </script>
</body>

</html>