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

// Fetch cities to populate the dropdown
$cities = [];
try {
    $stmt = $pdo->query("SELECT id, name FROM citys");
    $cities = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    exit();
}

// Handle city deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $city_id = $_POST['city_id'];

    try {
        // Start a transaction
        $pdo->beginTransaction();

        // Fetch users in the selected city
        $stmt = $pdo->prepare("SELECT id, picture_certificate_id FROM users WHERE city_id = :city_id");
        $stmt->bindParam(':city_id', $city_id, PDO::PARAM_INT);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($users as $user) {
            $user_id = $user['id'];

            // Fetch user_role for the user
            $stmt = $pdo->prepare("SELECT id FROM user_role WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $user_roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Delete associated coupons and user_role
            foreach ($user_roles as $user_role) {
                $user_role_id = $user_role['id'];

                // Delete associated coupons
                $stmt = $pdo->prepare("DELETE FROM coupons WHERE user_role_id = :user_role_id");
                $stmt->bindParam(':user_role_id', $user_role_id, PDO::PARAM_INT);
                $stmt->execute();

                // Delete associated user_role
                $stmt = $pdo->prepare("DELETE FROM user_role WHERE id = :user_role_id");
                $stmt->bindParam(':user_role_id', $user_role_id, PDO::PARAM_INT);
                $stmt->execute();
            }

            // Delete associated points
            $stmt = $pdo->prepare("DELETE FROM points WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            // Delete associated picture certificates (if any)
            if ($user['picture_certificate_id']) {
                $stmt = $pdo->prepare("DELETE FROM pictures WHERE id = :picture_id");
                $stmt->bindParam(':picture_id', $user['picture_certificate_id'], PDO::PARAM_INT);
                $stmt->execute();
            }

            // Delete user
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = :user_id");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
        }

        // Delete the city itself
        $stmt = $pdo->prepare("DELETE FROM citys WHERE id = :city_id");
        $stmt->bindParam(':city_id', $city_id, PDO::PARAM_INT);
        $stmt->execute();

        // Commit the transaction
        $pdo->commit();

        echo "<p style='color: green;'>City, users, and related records deleted successfully.</p>";
    } catch (PDOException $e) {
        // Rollback transaction on failure
        $pdo->rollBack();
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
    <title>Delete City</title>
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
            <form class="Form w-full md:w-auto flex flex-col justify-start items-start gap-3" method="POST" action="">
                <div class="BestCoffeeRoasted text-[#333333] text-7xl font-bold font-['Red Rose'] leading-[80px]">DELETE<br/>CITY</div>
                <div class="DarkFieldDropdown w-full max-w-[370px] md:w-[200px] h-[76px] relative">
                    <div class="Label mb-2 absolute text-black text-xs font-bold font-['Lato'] uppercase">CITY</div>
                    <select name="city_id" class="custom-select text-[#333333] Rectangle w-full md:w-[200px] h-12 left-0 pl-[24px] top-[28px] absolute bg-white rounded-3xl appearance-none" required>
                        <option value="" disabled selected>SELECT</option>
                        <?php foreach ($cities as $city): ?>
                            <option value="<?= htmlspecialchars($city['id']); ?>">
                                <?= htmlspecialchars($city['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="SubmitBtn w-[110px] h-[50px] px-[18px] bg-[#f28d3c] rounded-[90px] justify-center items-center gap-2.5 inline-flex">
                    <div class="Submit text-white text-[15px] font-bold font-['Red Rose']">DELETE</div>
                </button>
            </form>
        </div>
    </div>
    <?php include('footer.php'); ?>
    <script src="responsive_header.js"></script>
</body>
</html>