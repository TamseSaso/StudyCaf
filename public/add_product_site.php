<?php
session_start();
error_reporting(E_ALL);
include('../database/database.php');

if (isset($_SESSION['user_id']) && isset($_SESSION['role_id'])) {
    $user_id = $_SESSION['user_id'];
    $role_id = $_SESSION['role_id'];
} else {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $description = trim($_POST['description']);
  $price = floatval($_POST['price']);
  $studentPrice = floatval($_POST['studentprice']);
  $category_id = intval($_POST['category']);

  $targetDir = "uploads/";
  $fileName = basename($_FILES["image"]["name"]);
  $targetFilePath = $targetDir . $fileName;
  $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

  $allowedTypes = ['jpg', 'png', 'jpeg', 'gif'];
  if (in_array($fileType, $allowedTypes)) {
      if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
          try {
              $stmt = $pdo->prepare("INSERT INTO pictures (file_name, description) VALUES (:file_name, :target_file_path)");
              $stmt->bindParam(':file_name', $fileName);
              $stmt->bindParam(':target_file_path', $targetFilePath);
              $stmt->execute();
              $picture_id = $pdo->lastInsertId();

              $stmt = $pdo->prepare("INSERT INTO products (name, description, price, student_price, category_id, picture_product_id) VALUES (:name, :description, :price, :student_price, :category_id, :picture_id)");
              $stmt->bindParam(':name', $name);
              $stmt->bindParam(':description', $description);
              $stmt->bindParam(':price', $price);
              $stmt->bindParam(':student_price', $studentPrice);
              $stmt->bindParam(':category_id', $category_id);
              $stmt->bindParam(':picture_id', $picture_id);
              
              if ($stmt->execute()) {
                echo "<script>alert('Product added successfully.'); window.location.href='add_product_site.php';</script>";
              } else {
                  echo "<script>alert('Error adding product.');</script>";
              }
          } catch (PDOException $e) {
            $error_message = addslashes($e->getMessage());
            echo "<script>alert('Error: " . $error_message . "');</script>";
          }
      } else {
          echo "<script>alert('File upload failed.');</script>";
      }
  } else {
      echo "<script>alert('Invalid file type. Please upload an image.');</script>";
  }
}

$categories = [];
try {
    $stmt = $pdo->prepare("SELECT id, name FROM categories");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Add New Product</title>
    <style>
        @layer components {
            .custom-select {
                background-image: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="black"%3E%3Cpath fill-rule="evenodd" d="M5.293 7.707a1 1 0 011.414 0L10 11l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /%3E%3C/svg%3E');
                background-position: right 0.75rem center;
                background-size: 1.5rem;
                background-repeat: no-repeat;
            }
        }
    </style>
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
        <div class="NewProduct w-full md:w-full px-4 md:px-20 py-10 bg-[#f9d4b3] rounded-[32px] md:rounded-[70px] flex flex-col 2md:flex-row justify-between items-start gap-5">
            <div class="BestCoffeeRoasted text-[#333333] text-7xl font-bold font-['Red Rose'] leading-[80px]">ADD NEW<br/>PRODUCT</div>
            <form method="POST" class="Form w-full md:w-[370px] flex flex-col justify-start items-start gap-2.5" enctype="multipart/form-data">
                <div class="LightFieldDefault w-full md:w-auto h-[76px] relative">
                    <input type="text" name="name" class="Rectangle max-w-[370px] w-full md:w-[370px] h-12 pl-[24px] bg-white rounded-3xl" placeholder="Name" required></input>
                </div>
                <div class="LightFieldDefault w-full md:w-auto h-[76px] relative">
                    <textarea name="description" rows="3" class="Rectangle max-w-[370px] w-full md:w-[370px] h-16 resize-none pl-[24px] pt-[12px] bg-white rounded-3xl" placeholder="Description" required></textarea>
                </div>
                <div class="LightFieldDefault w-full md:w-auto h-[76px] relative">
                    <input type="number" name="price" class="Rectangle max-w-[370px] w-full md:w-[370px] h-12 pl-[24px] bg-white rounded-3xl" placeholder="Price" required></input>
                </div>
                <div class="LightFieldDefault w-full md:w-auto h-[76px] relative">
                    <input type="number" name="studentprice" class="Rectangle max-w-[370px] w-full md:w-[370px] h-12 pl-[24px] bg-white rounded-3xl" placeholder="Student Price" required></input>
                </div>
                <label class="block mb-2 text-black text-xs font-bold font-Lato uppercase" for="default_size">INSERT PRODUCT IMAGE</label>
                <input type="file" name="image" class="block max-w-[370px] w-full mb-5 text-sm text-gray-900 border border-[#f9d4b3] rounded-3xl px-[16px] py-[12px] cursor-pointer bg-[#f9d4b3] dark:text-black focus:outline-none dark:bg-[#f28d3c] dark:border-[#f28d3c] dark:placeholder-gray-400" required>
                <div class="DarkFieldDropdown w-full max-w-[370px] md:w-[200px] h-[76px] relative">
                    <div class="Label text-black mb-4 text-xs font-bold font-Lato uppercase">CATEGORY</div>
                    <select name="category" class="custom-select text-[#333333] Rectangle w-full md:w-[200px] h-12 pl-[24px] bg-white rounded-3xl appearance-none" required>
                        <option value="" disabled selected>SELECT</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= htmlspecialchars($category['id']); ?>"><?= htmlspecialchars($category['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="SubmitBtn w-[110px] h-[50px] px-[18px] bg-[#f28d3c] rounded-[90px] justify-center items-center gap-2.5 flex mt-4 md:mt-0">
                    <div class="Submit text-white text-[15px] font-bold font-['Red Rose']">ADD</div>
                </button>
            </form>
        </div>
    </div>
    <?php include('footer.php'); ?>
    <script src="responsive_header.js"></script>
</body>
</html>