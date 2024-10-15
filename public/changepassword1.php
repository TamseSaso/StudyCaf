<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
    <title>Change Password</title>
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

    <div class="Register w-full h-[700px] px-4 md:px-20 py-10 md:py-[35px] bg-[#f9d4b3] md:rounded-[70px] rounded-[32px] flex flex-col md:flex-row justify-between items-center gap-10">
        <div class="ChangePasswordForm w-full max-w-md flex flex-col justify-start items-start gap-5">
            <div class="BestCoffeeRoasted text-[#333333] text-6xl md:text-7xl font-bold font-['Red Rose'] leading-tight md:leading-[80px]">
                CHANGE<br/>PASSWORD
            </div>

            <form action="send_reset_link.php" method="POST" class="flex flex-col gap-4 w-full">
                <div class="LightFieldDefault w-full relative">
                    <input name="email" type="email" class="Rectangle w-full h-12 pl-6 bg-white rounded-3xl" placeholder="TYPE YOUR EMAIL ADDRESS" required>
                </div>
                
                <div class="SubmitBtn w-[110px] h-[50px] px-[18px] bg-[#f28d3c] rounded-[90px] justify-center items-center gap-2.5 flex">
                    <button type="submit" class="text-white text-[15px] font-bold">SUBMIT</button>
                </div>
            </form>
        </div>
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