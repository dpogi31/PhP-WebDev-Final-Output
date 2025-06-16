<?php
session_start(); // Start session for CSRF token and sessions

// Generate CSRF token if none exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Product Page</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600&family=Raleway:wght@400;500;600&family=Roboto:wght@400;500;600&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="CS.css" />

    <style>
        .added-message {
            font-family: 'Raleway', sans-serif;
            font-size: 0.9rem;
            color: white;
            font-weight: 600;
            text-align: center;
            margin-top: 10px;
            display: none;
        }

        body.modal-open {
            overflow: hidden;
        }

        .modal-box-desc#descModal {
            display: none;
            align-items: center;
            justify-content: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .modal-box-desc #modalContent {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            padding: 25px 35px;
            max-width: 500px;
            width: 90%;
            text-align: center;
            position: relative;
            color: #333;
        }

        .modal-box-desc h2 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2rem;
            letter-spacing: 1px;
            color: #333;
            margin-bottom: 12px;
        }

        #closeDescModal {
            position: absolute;
            top: 12px;
            right: 16px;
            background: transparent;
            color: #333;
            font-size: 1.5rem;
            border: none;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        #closeDescModal:hover {
            color: #f0b028;
        }

        #productDescText {
            font-size: 1rem;
            margin-top: 10px;
            color: #333;
        }

        #productDescImage {
            max-width: 100%;
            border-radius: 12px;
            margin-top: 10px;
        }

        .view-desc-btn {
            background-color: rgb(25, 156, 36);
            color: #fff;
            border: none;
            padding: 10px 16px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .view-desc-btn:hover {
            background-color: rgb(48, 54, 50);
            transform: scale(1.05);
        }

        /* Add to Cart button inside modal */
        #modalAddToCartBtn {
            background-color: rgb(25, 156, 36);
            color: white;
            border: none;
            padding: 12px 20px;
            margin-top: 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-size: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.12);
            transition: background-color 0.3s ease, transform 0.2s ease;
            width: 100%;
        }

        #modalAddToCartBtn:hover {
            background-color: rgb(48, 54, 50);
            transform: scale(1.05);
        }
    </style>
</head>

<body class="body1">
    <header class="navbar">
        <div class="logo">
            <img src="assets/images/logo1.png" alt="logo" />
        </div>

        <div class="search-container">
            <input type="text" id="productSearch" placeholder="Search products..." />
            <button onclick="searchProducts()">Search</button>
        </div>

        <nav class="navcontainer">
            <ul class="navlinks">
                <li><a href="CSHomePage.php">Home</a></li>
                <li><a href="CSProducts.php">Products</a></li>
                <li><a href="CSCart.php"><i class="fa fa-shopping-cart"></i> Cart</a></li>
                <li><a href="#" id="supportBtn">Support</a></li>
                <li><a href="#" id="contactBtn">Contact</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h1>Our Products</h1>
        <section>
            <div class="product-grid">
                <?php
                include 'db_connect.php';

                $sql = "SELECT * FROM products";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="product-card">';
                        echo '<img src="' . htmlspecialchars($row['image_path']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                        echo '<h2>' . htmlspecialchars($row['name']) . '</h2>';
                        echo '<p>₱' . number_format($row['price'], 2) . '</p>';

                        // Add to Cart Form outside modal
                        echo '<form action="addToCart.php" method="post">';
                        echo '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';
                        echo '<input type="hidden" name="product_id" value="' . $row['id'] . '">';
                        echo '<input type="hidden" name="product_name" value="' . htmlspecialchars($row['name']) . '">';
                        echo '<input type="hidden" name="product_price" value="' . $row['price'] . '">';
                        echo '<input type="hidden" name="product_image" value="' . htmlspecialchars($row['image_path']) . '">';
                        echo '<input type="hidden" name="product_quantity" value="1">';
                        echo '<button type="submit" class="add-to-cart-btn">ADD TO CART</button>';
                        echo '</form>';

                        // Description Button with data attributes
                        echo '<button class="view-desc-btn" 
                            data-description="' . htmlspecialchars($row['description']) . '" 
                            data-title="' . htmlspecialchars($row['name']) . '" 
                            data-image="' . htmlspecialchars($row['image_path']) . '" 
                            data-price="' . $row['price'] . '"
                            data-id="' . $row['id'] . '"
                            >
                            View Description
                        </button>';

                        // Added to Cart Message
                        echo '<div class="added-message">Added to cart!</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No products found.</p>';
                }

                $conn->close();
                ?>
            </div>
        </section>
    </main>

    <!-- Other modals (support/contact) unchanged -->
    <div id="supportModal" class="modal-overlay-support">
        <div class="modal-box-support">
            <span class="close-btn-support" id="closeSupportModal">&times;</span>
            <h2>Need Help?</h2>
            <p>If you have any questions or issues, feel free to reach out to our support team!</p>
            <form id="supportForm">
                <input type="text" placeholder="Your Name" required />
                <input type="email" placeholder="Your Email" required />
                <textarea placeholder="How can we help you?" required></textarea>
                <button type="submit" class="modal-btn-support confirm">Send</button>
                <p>© 2025 ChronoSync. All rights reserved.</p>
            </form>
        </div>
    </div>

    <div class="modal-overlay-contact" id="contactModal">
        <div class="modal-box-contact">
            <button id="closeContactModal">&times;</button>
            <h2>Contact Us</h2>
            <p>Have questions or feedback? We'd love to hear from you.</p>
            <form id="contactForm">
                <input type="text" placeholder="Your Name" required />
                <input type="email" placeholder="Your Email" required />
                <textarea placeholder="Your Message" required></textarea>
                <button type="submit" class="modal-btn-contact">Send Message</button>
                <p>© 2025 ChronoSync. All rights reserved.</p>
            </form>
        </div>
    </div>

    <!-- Product Description Modal with Add to Cart form -->
    <div class="modal-box-desc" id="descModal" aria-hidden="true" role="dialog" aria-labelledby="productDescTitle">
        <div id="modalContent">
            <button id="closeDescModal" aria-label="Close description modal">&times;</button>
            <h2 id="productDescTitle">Product Name</h2>
            <img id="productDescImage" src="" alt="Product Image" />
            <p id="productDescText">Product description here...</p>

            <!-- Add to Cart form inside modal -->
            <form id="modalAddToCartForm" action="addToCart.php" method="post">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="product_id" id="modalProductId" value="">
                <input type="hidden" name="product_name" id="modalProductName" value="">
                <input type="hidden" name="product_price" id="modalProductPrice" value="">
                <input type="hidden" name="product_image" id="modalProductImage" value="">
                <input type="hidden" name="product_quantity" value="1">
                <button type="submit" id="modalAddToCartBtn">Add to Cart</button>
            </form>
        </div>
    </div>

    <footer class="footer-landing">
        <div class="footer-content">
            <h2>Chrono Sync</h2>
            <p>Stay in sync with your health, your style, and your time. Only at Chrono Sync.</p>
            <div class="footer-icons">
                <a href="https://www.facebook.com/" aria-label="Facebook" target="_blank"><i
                        class="fab fa-facebook-f"></i></a>
                <a href="https://www.instagram.com/" aria-label="Instagram" target="_blank"><i
                        class="fab fa-instagram"></i></a>
                <a href="https://www.tiktok.com/" aria-label="Tiktok" target="_blank"><i class="fab fa-tiktok"></i></a>
                <a href="https://www.youtube.com/" aria-label="Youtube" target="_blank"><i
                        class="fab fa-youtube"></i></a>
                <a href="https://www.linkedin.com/" aria-label="Linkedin" target="_blank"><i
                        class="fab fa-linkedin-in"></i></a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2025 ChronoSync. All rights reserved.</p>
        </div>
    </footer>

    <script src="CS.js"></script>

    <script>
        // "Added to cart" message on normal Add to Cart buttons
        document.querySelectorAll('.product-card form').forEach(form => {
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                const messageDiv = form.parentElement.querySelector('.added-message');
                messageDiv.style.display = 'block';
                messageDiv.setAttribute('aria-live', 'polite');

                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.disabled = true;

                setTimeout(() => {
                    messageDiv.style.display = 'none';
                    submitBtn.disabled = false;
                    form.submit();
                }, 1000);
            });
        });

        // Description modal logic with Add to Cart form population
        document.querySelectorAll('.view-desc-btn').forEach(button => {
            button.addEventListener('click', () => {
                const description = button.getAttribute('data-description');
                const title = button.getAttribute('data-title');
                const imageSrc = button.getAttribute('data-image');
                const price = button.getAttribute('data-price');
                const id = button.getAttribute('data-id');

                document.getElementById('productDescTitle').textContent = title;
                document.getElementById('productDescText').textContent = description;
                document.getElementById('productDescImage').src = imageSrc;
                document.getElementById('productDescImage').alt = title;

                // Fill hidden inputs in modal add to cart form
                document.getElementById('modalProductId').value = id;
                document.getElementById('modalProductName').value = title;
                document.getElementById('modalProductPrice').value = price;
                document.getElementById('modalProductImage').value = imageSrc;

                // Show the modal
                document.getElementById('descModal').style.display = 'flex';
                document.getElementById('descModal').setAttribute('aria-hidden', 'false');
            });
        });

        // Close modal button logic
        document.getElementById('closeDescModal').addEventListener('click', () => {
            document.getElementById('descModal').style.display = 'none';
            document.getElementById('descModal').setAttribute('aria-hidden', 'true');
        });

        // Optional: Close modal when clicking outside modal content
        window.addEventListener('click', (e) => {
            const modal = document.getElementById('descModal');
            if (e.target === modal) {
                modal.style.display = 'none';
                modal.setAttribute('aria-hidden', 'true');
            }
        });
    </script>
</body>

</html>