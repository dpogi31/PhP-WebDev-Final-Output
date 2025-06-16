<?php
require_once __DIR__ . '/vendor/autoload.php';

use Google\Client as Google_Client;

$client = new Google_Client();
$client->setClientId('827652394253-qmpvm29ojaptpqt0quggc24i97n61p6k.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/ForDefense/google-callback.php');
$client->addScope('email');
$client->addScope('profile');
