<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Header</title>
</head>
<body>
<div class="HeaderGuest w-full h-[50px] px-[60px] justify-between items-center inline-flex">
    <a class="Caf text-white text-[40px] font-bold font-['Red Rose'] leading-[48px]" href="index.php">STUDYCAF</a>
    <div class="Pages h-8 px-[35px] justify-center items-center gap-20 flex">
        <a class="About text-center text-white text-xl font-normal font-Lato leading-loose hover:text-[#f28d3c]" href="index.php#about">About</a>
        <a class="Menu text-center text-white text-xl font-normal font-Lato leading-loose hover:text-[#f28d3c]" href="menu.php?category=Coffee">Menu</a>
        <a class="Contact text-center text-white text-xl font-normal font-Lato leading-loose hover:text-[#f28d3c]" href="index.php#contact">Contact</a>
    </div>
    <div class="LoginRegisterBtn self-stretch justify-center items-center gap-5 flex">
        <a href="login.php">
        <button class="inline-flex h-[50px] w-[110px] items-center justify-center rounded-[90px] bg-[#f28d3c] px-[18px] text-center font-['Red Rose'] text-[15px] font-bold uppercase text-white hover:opacity-1 hover:border
        hover:shadow-custom-hover hover:border-[#f28d3c] active:border active:shadow-custom-inner">LOGIN</button>
        </a> 
        <a href="register.php">
        <button class="w-[140px] h-[50px] px-[18px] rounded-[90px] border border-[#f28d3c] justify-center items-center inline-flex text-center text-[#f28d3c] text-[15px] font-bold font-['Red Rose'] uppercase
        hover:shadow-custom-border-hover">REGISTER
        </button>
        </a>
    </div>
</div>
</body>
</html>