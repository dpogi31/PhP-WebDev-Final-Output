<?php
session_start();
// titignan kung ang CSRF token mula sa form ay tugma sa naka store sa session
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('Invalid CSRF token'); // Kung hindi tugma, itigil ang proseso at ipakita ang error
}

// titignan kung may laman ang "quantities" field mula sa form submission
if (isset($_POST['quantities'])) {
    foreach ($_POST['quantities'] as $index => $qty) {
        // ichecheck na ang index na ito ay totoo sa session cart
        if (isset($_SESSION['cart'][$index])) {
            // iaupdate ang quantity ng product, to make sure hindi ito bababa sa 1
            $_SESSION['cart'][$index]['quantity'] = max(1, (int)$qty);
        }
    }
}

// Pagkatapos ng update, babalik sa cart page para ma-reflect ang mga pagbabago
header("Location: CSCart.php");
exit();
