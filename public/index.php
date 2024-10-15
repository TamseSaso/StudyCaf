<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="style.css" rel="stylesheet">
</head>
<body class="GuestRegister w-full h-full p-0 md:p-2.5 bg-black md:bg-[#2c2c2c] flex flex-col justify-start items-center gap-10">
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
        <div class="w-full bg-[#f9d4b3] md:rounded-[70px] rounded-[32px] px-4 md:px-[60px] py-10 md:py-[90px] flex flex-col md:flex-row justify-between items-center gap-[30px]">
            <div class="text-left max-w-[470px] gap-[40px]">
                <div class="text-lg uppercase font-normal text-[#333333] font-lato tracking-[3px]">Cafe</div>
                <h1 class="text-6xl md:text-7xl font-bold text-[#333333] font-red-rose leading-[80px]">Best Coffee<br>Roasted by<br>Barista</h1>
                <p class="text-xl text-[#333333] mt-4">The sky was cloudless and of a deep dark blue spectacle before us was indeed sublime.</p>
                <a href="reserveatable.php">
                    <button class="w-[180px] h-[50px] px-[18px] bg-black rounded-[90px] justify-center items-center gap-2.5 inline-flex hover:shadow-custom-hover-table mt-5">
                        <div class="text-center text-white text-[15px] font-bold font-red-rose uppercase">Reserve a Table</div>
                    </button>
                </a>
            </div>
            <div class="relative w-full max-w-lg h-96">
                <img class="absolute w-[100px] h-[110px] left-0 top-[25%]" src="/pictures/Bitmap100x100.jpg" alt="Decorative Bitmap">
                <img class="absolute w-[40px] h-[50px] right-0 bottom-0" src="/pictures/Bitmap42x49.jpg" alt="Decorative Bitmap">
                <img class="absolute w-[52px] h-[55px] right-[10%] top-0" src="/pictures/Bitmap55x55.jpg" alt="Decorative Bitmap">
            </div>
        </div>
        <div id="about" class="w-full flex flex-col lg:flex-row items-center justify-between gap-10 px-4 md:px-[60px] py-10 md:py-[90px]">
        <img class="bg-[#d7d7d7] rounded-3xl w-full md:w-[370px] max-w-[370px] aspect-square lg:order-2" src="/pictures/studycaf.png"></img>
        <div class="w-full lg:w-2/3 max-w-[470px]">
                <div class="text-[#f28d3c] text-lg uppercase font-lato tracking-[3px]">About</div>
                <h2 class="text-6xl font-red-rose text-white font-bold mt-4">Delightful Experience</h2>
                <p class="text-xl text-white mt-5">The sky was cloudless and of a deep dark blue spectacle before us was indeed sublime.</p>
                <p class="text-lg text-white/50 mt-5">The sky was cloudless and of a deep dark blue spectacle before us was indeed sublime sky was cloudless and of a deep dark blue spectacle before us was indeed sublime.</p>
                <a href="menu.php">
                    <button class="w-[140px] h-[50px] px-[18px] bg-[#f28d3c] rounded-[90px] justify-center items-center gap-2.5 inline-flex hover:opacity-1 hover:border hover:shadow-custom-hover hover:border-[#f28d3c] active:border active:shadow-custom-inner mt-10">
                        <div class="text-center text-white text-[15px] font-bold font-red-rose">SERVICES</div>
                    </button>
                </a>
            </div>
        </div>
        <div id="contact" class="w-full bg-[#f9d4b3] md:rounded-[70px] rounded-[32px] px-4 md:px-[60px] py-10 md:py-[90px] flex flex-col md:flex-row justify-between gap-10">
            <div class="w-full md:w-1/2 max-w-[372px]">
                <div class="text-lg uppercase text-[#333333] font-lato tracking-[3px]">Contact</div>
                <h3 class="text-5xl font-bold text-[#333333] mt-4">Contact Info</h3>
                <p class="text-xl text-[#333333] mt-4">+1 (2345) 678-90-12</p>
                <p class="text-lg text-[#333333]">cafe@company.com</p>
            </div>
            <form method="POST" class="w-full md:w-1/2 flex flex-col gap-[10px] max-w-[372px]">
                <div class="text-lg uppercase text-[#333333] font-lato tracking-[3px]">Get Latest News</div>
                <h4 class="text-5xl font-bold text-[#333333]">Subscribe</h4>
                <div class="relative mt-5">
                    <input type="text" name="email" class="w-full h-12 pl-[24px] bg-white rounded-3xl text-[#333333]" placeholder="Your Email" required>
                </div>
                <button type="submit" class="w-[140px] h-[50px] px-[18px] rounded-[90px] border border-[#f28d3c] justify-center items-center gap-2.5 inline-flex hover:shadow-custom-bg-border-hover mt-5">
                    <div class="text-[#f28d3c] text-[15px] font-bold font-red-rose">Subscribe</div>
                </button>
            </form>
        </div>
    </div>
    <?php include('footer.php'); ?>
    <script src="responsive_header.js"></script>
</body>
</html>
