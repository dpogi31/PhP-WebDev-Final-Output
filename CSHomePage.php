<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: CSLandingPage.php");
  exit();
}

require_once 'db_connect.php'; // Make sure this connects to your database

$userId = $_SESSION['user_id'];
$userEmail = $_SESSION['user_email'] ?? 'user@example.com';
$userName = 'User';

$sql = "SELECT username FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
  $userName = $row['username'];
}

// Generate initials from username (e.g., JohnDoe => JD)
$nameParts = preg_split('/\s+/', trim($userName));
if (count($nameParts) >= 2) {
  $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
} else {
  $initials = strtoupper(substr($userName, 0, 2));
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">

  <title>Chrono Sync | Dashboard</title>
  <link
    href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600&family=Raleway:wght@400;500;600&family=Roboto:wght@400;500;600&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="CS.css" />
</head>
<style>
  /* Container styling */
  .profile-dropdown {
    position: relative;
    margin-left: 15px;
  }

  .profile-link {
    color: #fff;
    display: flex;
    align-items: center;
    cursor: pointer;
    text-decoration: none;
    gap: 5px;
  }

  .profile-link .username {
    display: none;
    /* Hide email on small screen, optional */
    font-weight: 500;
    font-family: 'Raleway', sans-serif;
  }

  .dropdown-arrow {
    font-size: 0.7em;
  }

  /* Dropdown menu */
  .dropdown-menu {
    position: absolute;
    top: 120%;
    right: 0;
    background-color: white;
    min-width: 230px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    border-radius: 5px;
    padding: 5px 0;
    display: none;
    flex-direction: column;
    z-index: 1000;
  }

  .dropdown-menu li {
    list-style: none;
    width: 100%;
  }

  .dropdown-menu li a,
  .dropdown-logout-btn {
    color: black;
    padding: 12px 16px;
    display: inline-block;
    text-decoration: none;
    box-sizing: border-box;
    font-family: 'Roboto', sans-serif;
    font-weight: 500;
    background-color: transparent;
    border: none;
    max-width: 100%;
    text-align: left;
    cursor: pointer;
    font-size: 1.6rem;
    margin-right: 8px;
    width: 16px;
    text-align: center;
    font-family: 'Raleway', sans-serif;
  }

  @media (min-width: 768px) {
    .profile-link .username {
      display: inline;
    }
  }

  /* Modal styles */
  .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 2000;
  }

  .modal-box {
    background-color: #222;
    padding: 25px 30px;
    border-radius: 8px;
    color: #fff;
    max-width: 400px;
    width: 90%;
    text-align: center;
  }

  .modal-actions {
    margin-top: 20px;
    display: flex;
    justify-content: center;
    gap: 15px;
  }

  .modal-btn {
    padding: 10px 25px;
    font-size: 1rem;
    font-weight: 600;
    border-radius: 5px;
    border: none;
    cursor: pointer;
  }

  .modal-btn.confirm {
    background-color: #f0b028;
    color: #000;
  }

  .modal-btn.cancel {
    background-color: #555;
    color: #fff;
  }
  .avatar-circle {
  background-color: #f0b028;
  color: black;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  font-size: 0.9rem;
  font-family: 'Raleway', sans-serif;
  text-transform: uppercase;
}

</style>

<body class="body1">
  <header class="navbar">
    <div class="logo">
      <img src="assets/images/logo1.png" alt="logo">
    </div>
    <nav class="navcontainer">
      <ul class="navlinks">
        <li><a href="CSHomePage.php">Home</a></li>
        <li><a href="CSProducts.php">Products</a></li>
        <li><a href="CSCart.php"><i class="fa fa-shopping-cart"></i> Cart</a></li>
        <li><a href="#" id="supportBtn">Support</a></li>
        <li><a href="#" id="contactBtn">Contact</a></li>
        <li class="profile-dropdown">
          <a href="#" class="profile-link" title="<?php echo htmlspecialchars($userName); ?>">
            <div class="avatar-circle"><?php echo $initials; ?></div>
            <span class="username"><?php echo htmlspecialchars($userName); ?></span>
            <i class="fa fa-caret-down dropdown-arrow"></i>
          </a>

          <ul class="dropdown-menu">
            <li>
              <a href="CSProfile.php">
                <i class="fas fa-user"></i>User Profile
              </a>
            </li>
            <li>
              <a href="CSSettings.php">
                <i class="fas fa-cog"></i>Profile Settings
              </a>

            </li>
            <li>
              <!-- Dropdown logout triggers modal -->
              <button type="button" id="dropdownLogoutBtn" class="dropdown-logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
              </button>
            </li>
          </ul>


          <!-- Logout form with modal -->
          <form id="logoutForm" method="POST" action="logout.php" style="display:inline;">
            <div id="logoutModal" class="modal-overlay">
              <div class="modal-box">
                <h2>Confirm Logout</h2>
                <p>Are you sure you want to log out?</p>
                <div class="modal-actions">
                  <button type="submit" id="confirmLogout" class="modal-btn confirm">Yes</button>
                  <button type="button" id="cancelLogout" class="modal-btn cancel">No</button>
                </div>
              </div>
            </div>
          </form>
    </nav>
  </header>

  <main class="home-page">
    <section class="hero">
      <div class="hero-text">
        <h1>Welcome to <span>Chrono Sync</span></h1>
        <p>Timepieces redefined - explore innovative design and premium craftsmanship with every tick.</p>
        <div class="hero-buttons">
          <a href="CSProducts.php" class="btn">Shop Now</a>
          <a href="#" class="btn secondary" id="learnMoreBtn">Learn More</a>
        </div>
      </div>
      <div class="hero-image">
        <img src="assets/images/CW.png" alt="Chrono Watch" />
      </div>
    </section>

    <section class="features">
      <h2>Featured Collections</h2>
      <div class="feature-cards">
        <div class="card">
          <img src="assets/images/FP1.png" alt="Watch 1">
          <h3>Modern Classic</h3>
          <p>Timeless design with cutting-edge technology.</p>
        </div>
        <div class="card">
          <img src="assets/images/FP2.jpg" alt="Watch 2">
          <h3>Urban Sync</h3>
          <p>Designed for the fast-paced modern lifestyle.</p>
        </div>
        <div class="card">
          <img src="assets/images/FP3.webp" alt="Watch 3">
          <h3>Elegant Tech</h3>
          <p>Luxury meets smart innovation.</p>
        </div>
      </div>
    </section>
  </main>

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

  <div class="modal" id="learnMoreModal">
    <div class="modal-content">
      <span class="close-btn" id="closeModal">&times;</span>
      <h2>About <span class="brand1">Chrono Sync</span></h2>
      <p>
        Chrono Sync is a fusion of precision and style, delivering smart timepieces crafted for the future.
        Our mission is to blend classic aesthetics with modern technology, creating watches that do more than just tell
        time.
      </p>
      <p>
        Whether you're an urban explorer, a tech enthusiast, or a minimalist trendsetter, Chrono Sync offers collections
        that match your lifestyle.
      </p><br>
      <p>© 2025 ChronoSync. All rights reserved.</p>
    </div>
  </div>

  <footer class="footer-landing">
    <div class="footer-content">
      <h2>Chrono Sync</h2>
      <p>Stay in sync with your health, your style, and your time. Only at Chrono Sync.</p>
      <div class="footer-icons">
        <a href="https://www.facebook.com/" aria-label="Facebook" target="_blank"><i class="fab fa-facebook-f"></i></a>
        <a href="https://www.instagram.com/" aria-label="Instagram" target="_blank"><i class="fab fa-instagram"></i></a>
        <a href="https://www.tiktok.com/" aria-label="Tiktok" target="_blank"><i class="fab fa-tiktok"></i></a>
        <a href="https://www.youtube.com/" aria-label="Youtube" target="_blank"><i class="fab fa-youtube"></i></a>
        <a href="https://www.linkedin.com/" aria-label="Linkedin" target="_blank"><i class="fab fa-linkedin-in"></i></a>

      </div>
    </div>
    <div class="footer-bottom">
      <p>© 2025 ChronoSync. All rights reserved.</p>
    </div>
  </footer>

  <script src="CS.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const dropdownLogoutBtn = document.getElementById('dropdownLogoutBtn');
      const logoutModal = document.getElementById('logoutModal');
      const cancelLogout = document.getElementById('cancelLogout');
      const confirmLogout = document.getElementById('confirmLogout');

      const profileDropdown = document.querySelector('.profile-dropdown');
      const dropdownMenu = document.querySelector('.dropdown-menu');

      // Show logout modal
      dropdownLogoutBtn.addEventListener('click', () => {
        logoutModal.style.display = 'flex';
      });

      // Hide modal on cancel
      cancelLogout.addEventListener('click', () => {
        logoutModal.style.display = 'none';
      });

      // Close modal if clicking outside modal box
      logoutModal.addEventListener('click', (e) => {
        if (e.target === logoutModal) {
          logoutModal.style.display = 'none';
        }
      });

      // Toggle dropdown menu on profile link click
      profileDropdown.querySelector('.profile-link').addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropdownMenu.style.display = dropdownMenu.style.display === 'flex' ? 'none' : 'flex';
      });

      // Close dropdown if clicking outside of it
      document.addEventListener('click', (e) => {
        if (!profileDropdown.contains(e.target)) {
          dropdownMenu.style.display = 'none';
        }
      });

      // --------------------------
      // SETTINGS MODAL LOGIC BELOW
      // --------------------------
      const settingsBtn = document.getElementById('settingsDropdownBtn');
      const settingsModal = document.getElementById('settingsModal');
      const cancelSettings = document.getElementById('cancelSettings');

      if (settingsBtn && settingsModal && cancelSettings) {
        // Open settings modal
        settingsBtn.addEventListener('click', (e) => {
          e.preventDefault();
          settingsModal.style.display = 'flex';
        });

        // Close modal when cancel is clicked
        cancelSettings.addEventListener('click', () => {
          settingsModal.style.display = 'none';
        });

        // Close modal if clicking outside modal box
        settingsModal.addEventListener('click', (e) => {
          if (e.target === settingsModal) {
            settingsModal.style.display = 'none';
          }
        });
      }
    });
  </script>


</body>

</html>