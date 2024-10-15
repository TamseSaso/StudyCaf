<?php
session_start();
$message = isset($_SESSION['message']) ? $_SESSION['message'] : 'An unknown error occurred.';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
    <title>Password Reset</title>
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
    <div class="bg-white p-10 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Password Reset</h1>
        <p class="text-gray-600"><?php echo $message; ?></p>
        <p class="text-gray-600">You will be redirected to the login page in 10 seconds...</p>
    </div>

    <script>
        // JavaScript redirect after 10 seconds
        setTimeout(function() {
            window.location.href = 'login.php';
        }, 10000); // 10 seconds
    </script>
</body>
</html>

<?php
session_unset();
session_destroy();
?>
