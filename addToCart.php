<?php
session_start(); 

// titignan na POST ang request method (hindi GET o iba pa)
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    //  Siguraduhin na valid ang CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo "CSRF validation failed.";
        exit();
    }

    //  Kunin ang info ng product mula sa form
    $product = [
        'id' => $_POST['product_id'],
        'name' => $_POST['product_name'],
        'price' => $_POST['product_price'],
        'image' => $_POST['product_image'],
        'quantity' => $_POST['product_quantity'] // Quantity na ipinasa mula sa form
    ];

    //  Kung walang cart sa session, gagawa ng bago
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // ichecheck kung ang product ay nasa cart na
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product['id']) {
            // Kung may match sa ID, dagdagan lang ang quantity
            $item['quantity'] += 1;
            $found = true;
            break;
        }
    }

    //  Kung wala pa sa cart ang product, idagdag ito
    if (!$found) {
        $_SESSION['cart'][] = $product;
    }

    //  Ibabalik ang user sa products page
    header("Location: CSProducts.php");
    exit();
    
} else {
    // Kapag hindi POST request
    echo "Invalid request.";
}
