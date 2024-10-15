<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once '../database/database.php'; // Assuming a separate file to handle database connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <title>Menu</title>
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
    <div class="DiscoverMenu w-full p-4 md:px-[115px] pb-3 xl:pb-[54px] pt-[54px] bg-[#f9d4b3] md:rounded-[70px] rounded-[32px] flex flex-col justify-center items-center gap-20">
      <div class="DiscoverMenuHeading flex flex-col justify-start items-center gap-10">
        <div class="OurMenu text-center text-[#333333] text-lg font-normal font-Lato uppercase tracking-[3px]">Our Menu</div>
        <div class="DiscoverMenu text-center text-[#333333] text-[32px] md:text-[56px] font-bold font-['Red Rose']">Discover Menu</div>
        <div class="MenuOptions w-full md:w-[463.39px] flex justify-center items-center gap-[30px] md:gap-[135px]">
          <div class="Coffee flex flex-col justify-center items-center gap-0.5">
            <a href="?category=Coffee">
              <img class="w-16 h-16" src="../pictures/<?php echo (isset($_GET['category']) && $_GET['category'] == 'Coffee') ? 'coffee-active.png' : 'coffee-idle.png'; ?>" alt="Coffee Icon" />
              <div class="Coffee text-center text-[#333333] <?php echo (isset($_GET['category']) && $_GET['category'] == 'Coffee') ? '' : '/50'; ?> text-base font-normal font-Lato">Coffee</div>
            </a>
            <?php if (isset($_GET['category']) && $_GET['category'] == 'Coffee') { ?>
              <div class="Choose w-2 h-2 bg-[#f28d3c] rounded-full"></div>
            <?php } ?>
          </div>
          <div class="Bakery flex flex-col justify-center items-center gap-0.5">
            <a href="?category=Bakery">
              <img class="w-16 h-[42px] mb-5" src="../pictures/<?php echo (isset($_GET['category']) && $_GET['category'] == 'Bakery') ? 'bakery-active.png' : 'bakery-idle.png'; ?>" alt="Bakery Icon" />
              <div class="Bakery text-center text-[#333333] <?php echo (isset($_GET['category']) && $_GET['category'] == 'Bakery') ? '' : '/50'; ?> text-base font-normal font-Lato">Bakery</div>
            </a>
            <?php if (isset($_GET['category']) && $_GET['category'] == 'Bakery') { ?>
              <div class="Choose w-2 h-2 bg-[#f28d3c] rounded-full"></div>
            <?php } ?>
          </div>
          <div class="Breakfast flex flex-col justify-center items-center gap-0.5">
            <a href="?category=Breakfast">
              <img class="w-16 h-16" src="../pictures/<?php echo (isset($_GET['category']) && $_GET['category'] == 'Breakfast') ? 'breakfast-active.png' : 'breakfast-idle.png'; ?>" alt="Breakfast Icon" />
              <div class="Breakfast text-center text-[#333333] <?php echo (isset($_GET['category']) && $_GET['category'] == 'Breakfast') ? '' : '/50'; ?> text-base font-normal font-Lato">Breakfast</div>
            </a>
            <?php if (isset($_GET['category']) && $_GET['category'] == 'Breakfast') { ?>
              <div class="Choose w-2 h-2 bg-[#f28d3c] rounded-full"></div>
            <?php } ?>
          </div>
        </div>
      </div>
      <div class="MenuAll flex flex-col xl:flex-row justify-center items-center gap-[30px]">
        <?php
        if (isset($_GET['category'])) {
            $category = $_GET['category'];
            // Fetch products from the database based on the selected category using PDO
            $query = "SELECT p.*, pics.description AS picture_path FROM products p INNER JOIN categories c ON p.category_id = c.id LEFT JOIN pictures pics ON p.picture_product_id = pics.id WHERE c.name = :category";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':category', $category, PDO::PARAM_STR);
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Split products into two menus
            $numProducts = count($products);
            $splitIndex = ceil($numProducts / 2);
            $menu1Products = array_slice($products, 0, $splitIndex);
            $menu2Products = array_slice($products, $splitIndex);
        ?>
        <div class="Menu1 w-full md:w-[570px] p-8 bg-[#f28d3c] rounded-[32px] md:rounded-[64px] border border-[#f5eadd] flex flex-col justify-center items-center gap-4">
          <?php foreach ($menu1Products as $product) { ?>
          <div class="Product w-full md:w-[506px] flex justify-start items-center gap-[25px]">
            <img class="Oval min-w-20 w-20 h-20 md:w-28 md:h-28 bg-white rounded-full overflow-hidden" src="<?php echo htmlspecialchars($product['picture_path']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            <div class="Description flex-1 pr-[20px] md:pr-[60px] flex flex-col justify-center items-start">
              <div class="ProductName text-[#333333] text-xl md:text-2xl font-bold font-['Red Rose']"><?php echo htmlspecialchars($product['name']); ?></div>
              <div class="ProductDescription text-[#333333]/50 text-base font-normal font-Lato"><?php echo htmlspecialchars($product['description']); ?></div>
            </div>
            <div class="Price text-right text-[#333333] text-2xl md:text-[40px] font-bold font-['Red Rose']">
              <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 2) { ?>
                <span class="line-through opacity-50"><?php echo htmlspecialchars($product['price']); ?>€</span>
                <span class="text-3xl md:text-[40px] font-bold"> <?php echo htmlspecialchars($product['student_price']); ?>€</span>
              <?php } else { ?>
                <?php echo htmlspecialchars($product['price']); ?>€
              <?php } ?>
            </div>
          </div>
          <?php } ?>
        </div>
        <div class="Menu2 w-full md:w-[570px] p-8 bg-[#f28d3c] rounded-[32px] md:rounded-[64px] border border-[#f5eadd] flex flex-col justify-center items-center gap-4">
          <?php foreach ($menu2Products as $product) { ?>
          <div class="Product w-full md:w-[506px] flex justify-start items-center gap-[25px]">
            <img class="Oval min-w-20 w-20 h-20 md:w-28 md:h-28 bg-white rounded-full overflow-hidden" src="<?php echo htmlspecialchars($product['picture_path']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            <div class="Description flex-1 pr-[20px] md:pr-[60px] flex flex-col justify-center items-start">
              <div class="ProductName text-[#333333] text-xl md:text-2xl font-bold font-['Red Rose']"><?php echo htmlspecialchars($product['name']); ?></div>
              <div class="ProductDescription text-[#333333]/50 text-base font-normal font-Lato"><?php echo htmlspecialchars($product['description']); ?></div>
            </div>
            <div class="Price text-right text-[#333333] text-2xl md:text-[40px] font-bold font-['Red Rose']">
              <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 2) { ?>
                <span class="line-through opacity-50"><?php echo htmlspecialchars($product['price']); ?>€</span>
                <span class="text-3xl md:text-[40px] font-bold"> <?php echo htmlspecialchars($product['student_price']); ?>€</span>
              <?php } else { ?>
                <?php echo htmlspecialchars($product['price']); ?>€
              <?php } ?>
            </div>
          </div>
          <?php } ?>
        </div>
        <?php } ?>
      </div>
      <div class="Gallery flex flex-col justify-start items-center gap-[30px] xl:gap-[65px]">
        <div class="GalleryHeader text-center">
          <div class="OurGallery text-[#333333] text-lg font-normal font-Lato uppercase tracking-[3px]">Our Gallery</div>
          <div class="WatchCafePhoto text-[#333333] text-[32px] md:text-[56px] font-bold font-['Red Rose']">Watch Cafe Photo</div>
        </div>
        <div class="Pictures justify-center items-center gap-[30px] inline-flex flex-col xl:flex-row">
          <div class="Group1 w-[370px] flex-col justify-start items-start gap-8 inline-flex">
            <div class="Bitmap w-[370px] h-[336px] relative left-0 top-0 bg-[#f28d3c] rounded-3xl overflow-hidden">
              <img class="rounded-[20px]" src="../pictures/image1.png"></img>
            </div>
            <div class="Bitmap w-[370px] h-[496px] relative left-0 top-0 bg-[#f28d3c] rounded-3xl overflow-hidden">
              <img class="w-full h-full object-cover rounded-[20px]" src="../pictures/image2.png"></img>
            </div>
          </div>
          <div class="Group2 w-[370px] flex-col justify-start items-start gap-8 inline-flex">
          <div class="Bitmap w-[370px] h-[496px] relative left-0 top-0 bg-[#f28d3c] rounded-3xl overflow-hidden">
              <img class="w-full h-full object-cover rounded-[20px]" src="../pictures/image3.png"></img>
            </div>
            <div class="Bitmap w-[370px] h-[336px] relative left-0 top-0 bg-[#f28d3c] rounded-3xl overflow-hidden">
              <img class="w-full h-full object-cover rounded-[20px]" src="../pictures/image4.png"></img>
            </div>
          </div>
          <div class="Group3 w-[370px] flex-col justify-start items-start gap-8 inline-flex">
            <div class="Bitmap w-[370px] h-[416px] relative left-0 top-0 bg-[#f28d3c] rounded-3xl overflow-hidden">
              <img class="w-full h-full object-cover rounded-[20px]" src="../pictures/image5.png"></img>
            </div>
            <div class="Bitmap w-[370px] h-[416px] relative left-0 top-0 bg-[#f28d3c] rounded-3xl overflow-hidden">
              <img class="w-full h-full object-cover rounded-[20px]" src="../pictures/image6.png"></img>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include('footer.php'); ?>
  <script src="responsive_header.js"></script>
</body>
</html>