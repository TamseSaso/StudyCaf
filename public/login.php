<?php
require_once '../database/database.php'; // Make sure this file sets up the $pdo connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Use PDO's prepared statements which automatically handle escaping
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Query to find the user by email
    $email_query = "SELECT id, password FROM users WHERE email = :email";
    $stmt = $pdo->prepare($email_query);
    
    // Bind the email parameter
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        // Fetch the hashed password from the database
        $hashed_password = $user['password'];

        // Verify the password using password_verify
        if (password_verify($password, $hashed_password)) {
            session_start();
            
            // Store user ID and email in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $email;

            // Redirect to the homepage after successful login
            header("Location: index.php");
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "Email not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="style.css" rel="stylesheet">
    <title>Login</title>
</head>
<body class="GuestRegister w-full h-full p-0 md:p-2.5 bg-black md:bg-[#2c2c2c] flex flex-col justify-start items-center">
  <div class="Body w-full p-0 md:p-[25px] bg-black rounded-[70px] flex flex-col justify-start items-center">
  <div id="header-placeholder" class="w-full mb-0 md:mb-[30px]">
            <?php
            if (isset($_GET['mobile']) && $_GET['mobile'] === '1') {
                include 'header_mobile.php';
            } else {
                if (isset($_SESSION['user_id'])) {
                    include 'header-user.php';
                } else {
                    include 'header-guest.php';
                }
            }
            ?>
        </div>
        <div class="w-full bg-[#f9d4b3] md:rounded-[70px] rounded-[32px] px-[15px] py-[40px] md:px-[60px] md:py-[140px] flex flex-col md:flex-row justify-between items-center gap-10">
            <form method="POST" class="max-w-[370px] w-full flex flex-col justify-center items-start gap-5">
                <h1 class="text-7xl font-bold text-[#333333] font-red-rose leading-[80px]">LOGIN</h1>
                <input type="text" name="email" class="w-full h-12 pl-6 bg-white rounded-3xl text-[#333333] placeholder-[#333333]" placeholder="EMAIL" required>
                <input type="password" name="password" class="w-full h-12 pl-6 bg-white rounded-3xl text-[#333333] placeholder-[#333333]" placeholder="PASSWORD" required>
                <a href="changepassword1.php" class="text-[#f28d3c] text-base font-red-rose">CHANGE PASSWORD</a>
                <div class="flex items-center gap-5 mt-5">
                    <button type="submit" class="w-[110px] h-[50px] px-6 bg-[#f28d3c] rounded-[90px] text-white font-bold font-red-rose">SUBMIT</button>
                    <span class="text-[#f28d3c] text-base font-red-rose">or</span>
                    <button type="button" onclick="location.href ='google_login.php'" class="w-[50px] h-[50px] bg-[#f28d3c] rounded-full flex justify-center items-center overflow-hidden">
                        <img class="w-[40px] h-[40px]" src="../pictures/google.png" alt="Google Login">
                    </button>
                </div>
            </form>
            <div class="relative w-full max-w-lg h-96">
                <img class="absolute w-[100px] h-[110px] left-0 top-[25%]" src="../pictures/Bitmap100x100.jpg" alt="Decorative Bitmap">
                <img class="absolute w-[40px] h-[50px] right-0 bottom-0" src="../pictures/Bitmap42x49.jpg" alt="Decorative Bitmap">
                <img class="absolute w-[52px] h-[55px] right-[10%] top-0" src="../pictures/Bitmap55x55.jpg" alt="Decorative Bitmap">
            </div>
        </div>
    </div>
    <?php include('footer.php'); ?>
    <script src="responsive_header.js"></script>
</body>
</html>