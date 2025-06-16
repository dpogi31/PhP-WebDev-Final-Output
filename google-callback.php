<?php
require_once 'google-config.php';
require_once 'db_connect.php'; 
session_start();

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (isset($token['error'])) {
        echo '<h3>Google login failed:</h3>';
        echo '<pre>'; print_r($token); echo '</pre>';
        exit;
    }

    $client->setAccessToken($token);
    $oauth2 = new Google_Service_Oauth2($client);
    $userInfo = $oauth2->userinfo->get();

    $google_id = $userInfo->id;
    $username = $userInfo->name;
    $email = $userInfo->email;
    $role = 'user';

    // Check if user with the email already exists (manual or Google)
    $stmt = $conn->prepare("SELECT id, username, google_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Existing user (manual or Google)
        $stmt->bind_result($user_id, $existing_username, $existing_google_id);
        $stmt->fetch();
        $stmt->close();

        // If user was manual and has no google_id, update it
        if (empty($existing_google_id)) {
            $update_stmt = $conn->prepare("UPDATE users SET google_id = ? WHERE id = ?");
            $update_stmt->bind_param("si", $google_id, $user_id);
            $update_stmt->execute();
            $update_stmt->close();
        }

        // Use existing username
        $username = $existing_username;
    } else {
        // New user via Google, insert into DB
        $stmt->close();
        $insert_stmt = $conn->prepare("INSERT INTO users (google_id, username, email, role) VALUES (?, ?, ?, ?)");
        $insert_stmt->bind_param("ssss", $google_id, $username, $email, $role);
        if (!$insert_stmt->execute()) {
            die("Database insert error: " . $insert_stmt->error);
        }
        $user_id = $insert_stmt->insert_id;
        $insert_stmt->close();
    }

    // Set session
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user'] = $username;
    $_SESSION['email'] = $email;

    header('Location: CSHomePage.php');
    exit;
} else {
    echo "No code provided in callback.";
}
