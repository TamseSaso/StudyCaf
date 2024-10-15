<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Footer</title>
</head>
<body>
<div class="Footer w-full px-20 py-10 text-white">
    
    <!-- Links Section -->
    <div class="flex flex-col sm:flex-row sm:justify-between items-center sm:items-start text-center sm:text-left gap-8">
        
        <!-- Logo -->
        <a href="index.php" class="text-3xl font-bold font-['Red Rose']">STUDYCAF</a>
        
        <!-- Menu Links -->
        <div>
            <div class="text-[#f28d3c] text-lg uppercase font-Lato tracking-wider">Menu</div>
            <div class="text-base font-Lato mt-2">
                <a href="index.php#about" class="block">About</a>
                <a href="menu.php" class="block">Services</a>
                <a href="index.php#contact" class="block">Contact</a>
            </div>
        </div>
        
        <!-- Service Section -->
        <div>
            <div class="text-[#f28d3c] text-lg uppercase font-Lato tracking-wider">Service</div>
            <div class="text-base font-Lato mt-2">
                <a href="menu.php">High Quality<br/>Excellent<br/>Awesome</a>
            </div>
        </div>
        
        <!-- Social Links -->
        <div>
            <div class="text-[#f28d3c] text-lg uppercase font-Lato tracking-wider">Social</div>
            <div class="flex justify-center sm:justify-start gap-4 mt-4">
                <a href="#" class="w-10 h-10 bg-[#f28d3c] rounded-full flex items-center justify-center">
                    <img src="/pictures/instagram.png" alt="Instagram" class="w-4 h-4">
                </a>
                <a href="#" class="w-10 h-10 bg-[#f28d3c] rounded-full flex items-center justify-center">
                    <img src="/pictures/twitter.png" alt="Twitter" class="w-4 h-4">
                </a>
                <a href="#" class="w-10 h-10 bg-[#f28d3c] rounded-full flex items-center justify-center">
                    <img src="/pictures/facebook.png" alt="Facebook" class="w-[9.33px] h-4">
                </a>
            </div>
        </div>
    </div>

    <!-- Divider -->
    <div class="w-full h-px bg-[#414141] my-6"></div>
    
    <!-- Terms and Copyright Section -->
    <div class="flex flex-col sm:flex-row sm:justify-between items-center text-center sm:text-left text-sm">
        <div class="text-white/50 mb-4 sm:mb-0">Copyright © 2020 Laaqiq. All Rights Reserved.</div>
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="#" class="text-white">Terms of Use</a>
            <a href="#" class="text-white">Privacy Policy</a>
        </div>
    </div>
</div>

</body>
</html>
