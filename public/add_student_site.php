<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['role_id']) && $_SESSION['role_id'] == 3) {
    require_once '../database/database.php';

    // Fetch users who have uploaded a certificate but are not students (role_id != 2)
    $stmt = $pdo->prepare("SELECT u.id, u.email, u.picture_certificate_id, p.file_name as certificate_name, p.id as picture_id 
                           FROM users u 
                           INNER JOIN pictures p ON u.picture_certificate_id = p.id
                           LEFT JOIN user_role ur ON u.id = ur.user_id 
                           WHERE u.picture_certificate_id IS NOT NULL AND (ur.role_id != 2)");
    $stmt->execute();
    $pending_students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Handle admin action
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_id = $_POST['user_id'];
        if (isset($_POST['approve'])) {
            // Approve user: assign role_id = 2 (student)
            // First, check if the user already has a role
            $check_role_stmt = $pdo->prepare("SELECT id FROM user_role WHERE user_id = :user_id");
            $check_role_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $check_role_stmt->execute();
            
            if ($check_role_stmt->rowCount() > 0) {
                // If a role exists, update it to student (role_id = 2)
                $update_role_stmt = $pdo->prepare("UPDATE user_role SET role_id = 2 WHERE user_id = :user_id");
                $update_role_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $update_role_stmt->execute();
            } else {
                // If no role exists, insert a new role (role_id = 2)
                $insert_role_stmt = $pdo->prepare("INSERT INTO user_role (user_id, role_id) VALUES (:user_id, 2)");
                $insert_role_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $insert_role_stmt->execute();
            }
        } else if (isset($_POST['deny'])) {
            // Deny user: Remove picture_certificate_id, delete picture record, and delete the physical file
            // Get the picture ID and file name for deletion
            $get_picture_stmt = $pdo->prepare("SELECT picture_certificate_id, file_name FROM users INNER JOIN pictures ON users.picture_certificate_id = pictures.id WHERE users.id = :user_id");
            $get_picture_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $get_picture_stmt->execute();
            $picture_data = $get_picture_stmt->fetch(PDO::FETCH_ASSOC);

            if ($picture_data) {
                $picture_id = $picture_data['picture_certificate_id'];
                $file_name = $picture_data['file_name'];
                $file_path = __DIR__ . "/uploads/" . $file_name;

                // Remove the file from the server
                if (file_exists($file_path)) {
                    unlink($file_path); // Delete the file from the server
                }
                $deny_stmt = $pdo->prepare("UPDATE users SET picture_certificate_id = NULL WHERE id = :user_id");
                $deny_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $deny_stmt->execute();
                // Delete the picture record from the database
                $delete_picture_stmt = $pdo->prepare("DELETE FROM pictures WHERE id = :picture_id");
                $delete_picture_stmt->bindParam(':picture_id', $picture_id, PDO::PARAM_INT);
                $delete_picture_stmt->execute();
            }
        }

        // Reload page after action
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Add student</title>
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
    <div class="AdminDashboard w-full h-[790px] p-5 bg-[#f9d4b3] rounded-[70px] justify-start items-center gap-[34px] inline-flex">
      <div class="AdminControls w-full grow shrink basis-0 h-[750px] p-5 bg-[#f28d3c] rounded-[50px] flex-col justify-start items-center gap-5 inline-flex">
        <div class="StudentApproval text-black text-[40px] font-bold font-['Red Rose'] leading-[48px] text-center">STUDENT APPROVAL</div>

        <?php if (count($pending_students) > 0): ?>
          <?php foreach ($pending_students as $student): ?>
            <div class="StudentsWaiting self-stretch h-[180px] md:h-[70px] pl-0 md:pl-[30px] pr-0 md:pr-2.5 bg-[#f9d4b3] rounded-[30px] md:rounded-[90px] flex-col justify-start items-center gap-2.5 flex">
              <div class="StudentWaiting self-stretch py-2.5 justify-between text-center items-center flex-col md:flex-row md:inline-flex">
                <div class="UserEmail text-black text-xl font-normal font-['Red Rose'] leading-[48px]">
                  <?= htmlspecialchars($student['email']) ?>
                </div>
                <div class="LinkToCertificate text-black text-xl font-normal font-['Red Rose'] leading-[48px]">
                  <a href="./uploads/<?= htmlspecialchars($student['certificate_name']) ?>" target="_blank">View Certificate</a>
                </div>
                <div class="Buttons h-[50px] justify-center md:justify-start items-center gap-5 flex">
                  <form method="POST" class="inline-block">
                    <input type="hidden" name="user_id" value="<?= $student['id'] ?>">
                    <button type="submit" name="approve" class="AddStudentBtn w-[150px] h-[50px] px-[18px] bg-[#f28d3c] rounded-[90px] justify-center items-center gap-2.5 flex">
                      <div class="AddStudent text-white text-[15px] font-bold font-['Red Rose']">ADD STUDENT</div>
                    </button>
                  </form>
                  <form method="POST" class="inline-block">
                    <input type="hidden" name="user_id" value="<?= $student['id'] ?>">
                    <button type="submit" name="deny" class="RejectStudentBtn w-[50px] h-[50px] px-[18px] bg-[#f28d3c] rounded-[90px] justify-center items-center gap-2.5 flex">
                      <div class="X text-white text-[15px] font-bold font-['Red Rose']">X</div>
                    </button>
                  </form>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="text-black text-3xl text-center font-normal font-['Red Rose']">There are no new students.</p>
        <?php endif; ?>
      </div>
    </div>
    <?php include('../public/footer.php'); ?>
    <script src="responsive_header.js"></script>
  </div>
</body>
</html>