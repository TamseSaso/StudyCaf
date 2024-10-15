<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once '../vendor/autoload.php'; // Include the Google API PHP Client Library
require_once '../database/database.php'; // Include your database connection

// Create a new Google Client
$client = new Google_Client();
$client->setClientId('');        // Replace with your Google Client ID
$client->setClientSecret(''); // Replace with your Google Client Secret
$client->setRedirectUri(''); // Replace with your redirect URI
$client->addScope('email');
$client->addScope('profile');

// Authenticate the user with the code from Google
if (isset($_GET['code'])) {
    // Get the access token using the authorization code
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    
    if (isset($token['error'])) {
        // Handle token fetch error
        echo 'Failed to authenticate with Google: ' . $token['error'];
        exit();
    }

    // Set the access token to the Google Client
    $client->setAccessToken($token['access_token']);

    // Fetch user information from Google
    $oauth2 = new Google_Service_Oauth2($client);
    $userInfo = $oauth2->userinfo->get();

    // Extract user details from the Google response
    $google_id = $userInfo->id;
    $email = $userInfo->email;
    $name = $userInfo->name;
    $picture = $userInfo->picture;

    // Check if the user already exists in your database
    $stmt = $pdo->prepare('SELECT * FROM users WHERE google_id = :google_id');
    $stmt->execute([':google_id' => $google_id]);
    $user = $stmt->fetch();

    if ($user) {
        // User exists, log them in by setting session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
    } else {
        // User does not exist, create a new user in the database
        $stmt = $pdo->prepare('INSERT INTO users (email, name, picture, google_id) VALUES (:email, :name, :picture, :google_id)');
        $stmt->execute([
            ':email' => $email,
            ':name' => $name,
            ':picture' => $picture,
            ':google_id' => $google_id
        ]);

        // Get the newly created user ID
        $user_id = $pdo->lastInsertId();

        // Log the user in by setting session
        $_SESSION['user_id'] = $user_id;
        $_SESSION['email'] = $email;

        // Assign a default role to the user
        $sql_role = "INSERT INTO user_role (user_id, role_id) VALUES (:user_id, :role)";
        $stmt_role = $pdo->prepare($sql_role);
        $stmt_role->execute([':user_id' => $user_id, ':role' => 1]);

        $stmt = $pdo->prepare("SELECT r.id as role_id, u.picture_certificate_id FROM users u 
            INNER JOIN user_role ur ON u.id = ur.user_id 
            INNER JOIN roles r ON r.id = ur.role_id 
            WHERE u.id = :user_id");

        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

        $_SESSION['role_id'] = $user_data['role_id'];

        // Add default points to the user
        $sql_points = "INSERT INTO points (user_id, point_no) VALUES (:user_id, 0)";
        $stmt_points = $pdo->prepare($sql_points);
        $stmt_points->execute([':user_id' => $user_id]);
    }

    // Redirect the user to the dashboard or homepage
    header('Location: index.php'); // Replace with your destination page
    exit();
} else {
    // If no 'code' parameter in the URL, redirect to login or show error
    echo 'Invalid Google login request. Please try again.';
    exit();
}