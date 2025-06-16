<?php
session_start();

// Tatanggalin ang lahat ng session variables (yung mga user ID, cart, etc.)
session_unset();

// sisirain ang session upang hindi na ma-access ang kaka logout na user
session_destroy();

// Buburahin ang 'remember_token' cookie para sa "Remember Me" feature
// Ginagawang expired sa pamamagitan ng pag-set ng oras sa nakaraan (1 oras )
setcookie("remember_token", "", time() - 3600, '/', $_SERVER['HTTP_HOST'], true, true);

// iredirect ang user pabalik sa landing page matapos mag-logout
header("Location: CSLandingPage.php");
exit(); 
?>
