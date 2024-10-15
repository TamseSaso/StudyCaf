<?php
session_start();
require_once '../database/database.php'; // Database connection
require_once '../PHPMailer/src/PHPMailer.php'; // For sending the email, use PHPMailer or similar

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Check if the email exists in the users table
    $sql = "SELECT id, email FROM users WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if ($user) {
        // Generate a unique token
        $token = bin2hex(random_bytes(50));

        // Insert the token into the password_resets table
        $sql = "INSERT INTO password_resets (email, token) VALUES (:email, :token)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':email' => $email,
            ':token' => $token
        ]);

        // Create reset link
        $resetLink = "http://tamse-site.top/changepassword2.php?token=" . $token;

        // Send the reset email
        $subject = "Password Reset Request";
        $message = "
            <html>
            <head>
                <title>Password Reset Request</title>
            </head>
            <body>
                <p>Hi,</p>
                <p>Click the link below to reset your password:</p>
                <a href='$resetLink'>Reset Password</a>
                <p>If you did not request this, please ignore this email.</p>
            </body>
            </html>
        ";

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: noreply@tamse-site.top' . "\r\n";

        if (mail($email, $subject, $message, $headers)) {
            $_SESSION['message'] = "A password reset link has been sent to your email.";
        } else {
            $_SESSION['message'] = "Failed to send reset link. Try again.";
        }
    } else {
        // If email doesn't exist
        $_SESSION['message'] = "Email address not found.";
    }

    // Redirect to a confirmation page
    header('Location: password_reset_confirmation.php');
    exit();
}
