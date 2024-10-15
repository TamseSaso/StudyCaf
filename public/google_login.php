<?php
require_once '../vendor/autoload.php'; // Include the Google API PHP Client Library

session_start();

// Create a new Google Client
$client = new Google_Client();
$client->setClientId('');       // Replace with your Google Client ID
$client->setClientSecret('');   // Replace with your Google Client Secret
$client->setRedirectUri('');    // Replace with your redirect URI
$client->addScope('email');
$client->addScope('profile');

// Redirect to Google's OAuth 2.0 server
$authUrl = $client->createAuthUrl();
header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
exit();
