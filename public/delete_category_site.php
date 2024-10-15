<?php
session_start();
include('../database/database.php');

if (isset($_SESSION['user_id']) && isset($_SESSION['role_id'])) {
    $user_id = $_SESSION['user_id'];
    $role_id = $_SESSION['role_id'];
} else {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['category'])) {
        $category_id = intval($_POST['category']);

        try {
            $stmt = $pdo->prepare("DELETE FROM categories WHERE id = :id");
            $stmt->bindParam(':id', $category_id, PDO::PARAM_INT);
            $stmt->execute();

            echo "<script>alert('Category deleted successfully.'); window.location.href='delete_category_site.php';</script>";
        } catch (PDOException $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href='delete_category_site.php';</script>";
        }
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
    <title>Delete category</title>
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
        <div class="NewProduct w-full md:w-full h-[700px] px-4 md:px-20 py-10 md:py-[35px] bg-[#f9d4b3] rounded-[32px] md:rounded-[70px] flex flex-col justify-start items-start gap-2.5">
            <form method="POST" class="Form w-full md:w-auto flex flex-col justify-start items-start gap-3">
                <div class="BestCoffeeRoasted text-[#333333] text-6xl md:text-7xl font-bold font-['Red Rose'] leading-[80px]">DELETE<br/>CATEGORY</div>
                <div class="DarkFieldDropdown w-full max-w-[370px] md:w-[200px] h-[76px] relative">
                    <div class="Label text-black text-xs font-bold font-['Lato'] uppercase">CATEGORY</div>
                    <select name="category" class="custom-select text-[#333333] Rectangle w-full md:w-[200px] h-12 left-0 pl-[24px] top-[28px] absolute bg-white rounded-3xl appearance-none" required>
                        <option value="" disabled selected>SELECT</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= htmlspecialchars($category['id']); ?>"><?= htmlspecialchars($category['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="SubmitBtn w-[110px] h-[50px] px-[18px] bg-[#f28d3c] rounded-[90px] justify-center items-center gap-2.5 flex mt-4 md:mt-0">
                    <div class="Submit text-white text-[15px] font-bold font-['Red Rose']">DELETE</div>
                </button>
            </form>
        </div>
    </div>
    <?php include('footer.php'); ?>
    <script src="responsive_header.js"></script>
</body>
</html>