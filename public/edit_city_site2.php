<?php
session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['role_id'])) {
    $user_id = $_SESSION['user_id'];
    $role_id = $_SESSION['role_id'];
} else {
    header("Location: index.php");
    exit();
}

include('../database/database.php');

if (!isset($_GET['city_id'])) {
    echo "<p style='color: red;'>City ID not provided.</p>";
    exit();
}

$city_id = $_GET['city_id'];

try {
    // Fetch the city details based on city_id
    $stmt = $pdo->prepare("SELECT name, postal_no FROM citys WHERE id = :city_id");
    $stmt->bindParam(':city_id', $city_id, PDO::PARAM_INT);
    $stmt->execute();
    $city = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$city) {
        echo "<p style='color: red;'>City not found.</p>";
        exit();
    }
} catch (PDOException $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $postal_no = $_POST['postal_no'];

    try {
        // Update the city details
        $stmt = $pdo->prepare("UPDATE citys SET name = :name, postal_no = :postal_no WHERE id = :city_id");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':postal_no', $postal_no);
        $stmt->bindParam(':city_id', $city_id, PDO::PARAM_INT);
        $stmt->execute();

        echo "<script>alert('City edited successfully.'); window.location.href='edit_city_site1.php';</script>";
    } catch (PDOException $e) {
        echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Edit City</title>
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
            <form class="Form max-w-[370px] w-full flex-col justify-center items-start gap-5 flex" method="POST" action="">
                <div class="BestCoffeeRoasted text-[#333333] text-7xl font-bold font-['Red Rose'] leading-[80px]">EDIT<br>CITY</div>
                <div class="LightFieldDefault w-full md:w-auto h-[76px] relative">
                    <input type="text" name="name" class="Rectangle max-w-[370px] w-full md:w-[370px] h-12 pl-[24px] bg-white rounded-3xl" placeholder="City name" value="<?= htmlspecialchars($city['name']); ?>" required>
                </div>
                <div class="LightFieldDefault w-full md:w-auto h-[76px] relative">
                    <input type="text" name="postal_no" class="Rectangle max-w-[370px] w-full md:w-[370px] h-12 pl-[24px] bg-white rounded-3xl" placeholder="Postal number" value="<?= htmlspecialchars($city['postal_no']); ?>" required>
                </div>
                <button type="submit" class="SubmitBtn w-[110px] h-[50px] px-[18px] bg-[#f28d3c] rounded-[90px] justify-center items-center gap-2.5 inline-flex">
                    <div class="Submit text-white text-[15px] font-bold font-['Red Rose']">UPDATE</div>
                </button>
            </form>
        </div>
    </div>
    <?php include('../public/footer.php'); ?>
    <script src="responsive_header.js"></script>
</body>
</html>