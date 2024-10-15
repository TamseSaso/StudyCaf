<?php
require_once '../database/database.php'; // Make sure this file sets up the $pdo connection

// Check if user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$error = "";
$success = "";

// Handle the file upload if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['picture_certificate'])) {
    $file = $_FILES['picture_certificate'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $upload_dir = __DIR__ . '/uploads'; // Correct path for the uploads directory
    
    // Check if file is an image
    if (in_array($file['type'], $allowed_types)) {
        // Check for errors
        if ($file['error'] == 0) {
            // Check the file size (limit to 2MB)
            if ($file['size'] <= 2 * 1024 * 1024) {
                // Generate a unique file name
                $file_name = uniqid() . '-' . basename($file['name']);
                $file_path = $upload_dir . '/' . $file_name;
                
                // Move the uploaded file to the desired directory
                if (move_uploaded_file($file['tmp_name'], $file_path)) {
                    // First, insert the picture into the `pictures` table
                    try {
                        $stmt = $pdo->prepare("INSERT INTO pictures (file_name, description) VALUES (:name, :description)");
                        $stmt->bindParam(':name', $file_name); // Save the file name
                        $stmt->bindParam(':description', $file_name); // You can customize the description if needed
                        $stmt->execute();
                        
                        // Get the last inserted picture ID
                        $picture_id = $pdo->lastInsertId();
                        
                        // Now, update the users table with the new picture_certificate_id
                        $stmt = $pdo->prepare("UPDATE users SET picture_certificate_id = :picture_id WHERE id = :user_id");
                        $stmt->bindParam(':picture_id', $picture_id, PDO::PARAM_INT);
                        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                        $stmt->execute();
                        
                        $success = "Picture uploaded and saved successfully!";
                    } catch (PDOException $e) {
                        $error = "Error saving picture in the database: " . $e->getMessage();
                    }
                } else {
                    $error = "Failed to upload the file.";
                }
            } else {
                $error = "File size exceeds the 2MB limit.";
            }
        } else {
            $error = "Error uploading file.";
        }
    } else {
        $error = "Invalid file type. Please upload a JPEG, PNG, or GIF image.";
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
    <title>Upload Picture Certificate</title>
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
        <div class="UploadPicture w-full px-[15px] py-[40px] md:px-[60px] md:py-[140px] bg-[#f9d4b3] rounded-[32px] md:rounded-[70px] flex flex-col md:flex-row justify-between items-center gap-8">
            <form method="POST" enctype="multipart/form-data" class="UploadForm w-full md:w-[370px] flex flex-col justify-center items-start gap-[24px]">
                <div class="BestCoffeeRoasted self-stretch text-[#333333] text-7xl md:text-7xl font-bold font-['Red Rose'] leading-tight md:leading-[80px]">UPLOAD PICTURE</div>
                
                <?php if ($error): ?>
                    <div class="error text-red-500"><?= htmlspecialchars($error); ?></div>
                <?php elseif ($success): ?>
                    <div class="success text-green-500"><?= htmlspecialchars($success); ?></div>
                <?php endif; ?>
                
                <label class="block mb-2 text-black text-xs font-bold font-['Lato'] uppercase" for="default_size">INSERT PRODUCT IMAGE</label>
                <input type="file" name="picture_certificate" class="block w-full mb-5 text-sm text-gray-900 border border-[#f9d4b3] rounded-3xl px-[16px] py-[12px] cursor-pointer bg-[#f9d4b3] dark:text-black focus:outline-none dark:bg-[#f28d3c] dark:border-[#f28d3c] dark:placeholder-gray-400" required>
                <div class="Upload h-[50px] justify-center items-center gap-px flex">
                    <button type="submit" class="SubmitBtn w-full md:w-[110px] h-[50px] px-[18px] bg-[#f28d3c] rounded-[90px] flex justify-center items-center">
                        <div class="Submit text-white text-[15px] font-bold font-['Red Rose']">UPLOAD</div>
                    </button>
                </div>
            </form>
            <div class="relative w-full max-w-lg h-64 md:h-96">
                <img class="absolute w-[80px] md:w-[100px] h-[88px] md:h-[110px] left-0 top-[25%]" src="../pictures/Bitmap100x100.jpg" alt="Decorative Bitmap">
                <img class="absolute w-[32px] md:w-[40px] h-[40px] md:h-[50px] right-0 bottom-0" src="../pictures/Bitmap42x49.jpg" alt="Decorative Bitmap">
                <img class="absolute w-[42px] md:w-[52px] h-[45px] md:h-[55px] right-[10%] top-0" src="../pictures/Bitmap55x55.jpg" alt="Decorative Bitmap">
            </div>
        </div>
    </div>
    <?php include('footer.php') ?>
    <script src="responsive_header.js"></script>
</body>
</html>