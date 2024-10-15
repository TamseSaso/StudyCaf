<?php
session_start();
require_once '../database/database.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate passwords match
    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header('Location: reset_password_form.php?token=' . $token);
        exit();
    }

    // Check if token exists in password_resets
    $sql = "SELECT email, created_at FROM password_resets WHERE token = :token LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':token' => $token]);
    $resetRequest = $stmt->fetch();

    if (!$resetRequest) {
        $_SESSION['error'] = "Invalid or expired token.";
        header('Location: reset_password_form.php');
        exit();
    }

    // Check if the token is expired (e.g., 1 hour expiration)
    $expiry_time = strtotime($resetRequest['created_at']) + 3600; // 1 hour = 3600 seconds
    if (time() > $expiry_time) {
        $_SESSION['error'] = "Token has expired.";
        header('Location: reset_password_form.php');
        exit();
    }

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    // Update the password in the users table
    $sql = "UPDATE users SET password = :password WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':password' => $hashed_password,
        ':email' => $resetRequest['email']
    ]);

    // Delete the reset token from password_resets table
    $sql = "DELETE FROM password_resets WHERE token = :token";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':token' => $token]);

    // Set success message and redirect to login page
    $_SESSION['message'] = "Your password has been successfully updated.";
    header('Location: login.php');
    exit();
}
?>