<?php
session_start();
include('../database/database.php');

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || $_SESSION['role_id'] != 3) {
  header("Location: index.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Add new category</title>
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
    <div class="NewProduct w-full h-[700px] bg-[#f9d4b3] md:rounded-[70px] rounded-[32px] px-[10px] py-[40px] md:px-[80px] md:py-[40px] flex flex-col items-start gap-10">
        <form method="POST" action="add_category.php" class="Form max-w-[370px] w-full flex flex-col justify-center items-start gap-5">
            <div class="BestCoffeeRoasted text-[#333333] text-7xl font-bold font-['Red Rose'] leading-[80px]">ADD NEW<br/>CATEGORY</div>
            <div class="LightFieldDefault w-full md:w-auto h-[76px] relative">
            <input type="text" name="name" class="Rectangle max-w-[370px] w-full md:w-[370px] h-12 left-0 pl-[24px] top-[28px] absolute bg-white rounded-3xl " placeholder="Name" required></input>
            </div>
            <div class="LightFieldDefault w-full md:w-auto h-[76px] relative">
            <textarea type="text" name="description" rows="3" class="Rectangle max-w-[370px] w-full md:w-[370px] h-16 resize-none left-0 px-[24px] pt-[12px] top-[28px] absolute bg-white rounded-3xl" placeholder="Description" required></textarea>
            </div>
            <button type="submit" class="SubmitBtn w-[110px] h-[50px] px-[18px] bg-[#f28d3c] rounded-[90px] justify-center items-center gap-2.5 inline-flex">
              <div class="Submit text-white text-[15px] font-bold font-['Red Rose']">ADD</div>
            </button>
        </form>
    </div>
</div>
    <?php
      include('footer.php')
    ?>
    <script src="responsive_header.js"></script>
</body>
</html>