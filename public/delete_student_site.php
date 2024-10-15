<?php
session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['role_id']) && $_SESSION['role_id'] == 3) {
    require_once '../database/database.php';

    $message = "";

    // Handle the form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'];

        // Check if the user with the provided email exists and has a role_id of 2 (student)
        $stmt = $pdo->prepare("SELECT u.id, ur.role_id FROM users u
                               INNER JOIN user_role ur ON u.id = ur.user_id
                               WHERE u.email = :email AND ur.role_id = 2");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // If the user is a student, update the role to role_id = 1 (regular user)
            $update_role_stmt = $pdo->prepare("UPDATE user_role SET role_id = 1 WHERE user_id = :user_id");
            $update_role_stmt->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
            if ($update_role_stmt->execute()) {
                $message = "User role successfully changed to regular user.";
            } else {
                $message = "Failed to update user role. Please try again.";
            }
        } else {
            // If the user is not a student (role_id != 2)
            $message = "This user is not a student or does not exist.";
        }
    }
} else {
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
    <title>Delete Student Role</title>
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
    <div class="DeleteStudent w-full md:w-full h-[700px] px-4 md:px-20 py-10 md:py-[35px] bg-[#f9d4b3] rounded-[32px] md:rounded-[70px] flex flex-col justify-start items-start gap-2.5">
      <div class="Form w-full md:w-auto flex flex-col justify-start items-start gap-3">
        <div class="BestCoffeeRoasted text-[#333333] text-7xl font-bold font-['Red Rose'] leading-[80px]">DELETE<br/>STUDENT ROLE</div>

        <!-- Form to submit email -->
        <form method="POST" class="gap-[24px]">
            <div class="LightFieldDefault w-full md:w-auto min-w-[328px] h-[76px] relative">
              <input type="text" name="email" class="Rectangle w-full md:w-[370px] h-12 pl-[24px] bg-white rounded-3xl" placeholder="STUDENT EMAIL" required>
            </div>
            <button type="submit" class="SubmitBtn w-[110px] h-[50px] px-[18px] bg-[#f28d3c] rounded-[90px] justify-center items-center gap-2.5 flex md:inline-flex mt-4 md:mt-0">
              <div class="Submit text-white text-[15px] font-bold font-['Red Rose']">DELETE</div>
            </button>
        </form>

        <!-- Display message -->
        <?php if (!empty($message)): ?>
          <p class="text-black text-base md:text-lg font-normal font-['Red Rose'] leading-[30px] md:leading-[48px] mt-4"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
      </div>
    </div>
  </div>
    <?php
      include('../public/footer.php')
    ?>
    <script src="responsive_header.js"></script>
</body>
</html>