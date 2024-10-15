<?php
session_start();
require_once '../database/database.php';

// Query for cities and genders
$result_city = $pdo->query("SELECT id, name FROM citys")->fetchAll(PDO::FETCH_ASSOC);
$result_gender = $pdo->query("SELECT id, name FROM genders")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = $_POST['email'];
  $address = $_POST['address'];
  $city_id = $_POST['city'];
  $gender_id = $_POST['gender'];
  $password = $_POST['password'];
  $password_again = $_POST['password_again'];
  $role = 1;

  if ($password !== $password_again) {
    $error = "Passwords don't match!";
  } else {
    // Check if email already exists
    $email_query = "SELECT id FROM users WHERE email = :email";
    $stmt_email = $pdo->prepare($email_query);
    $stmt_email->execute([':email' => $email]);

    if ($stmt_email->rowCount() > 0) {
        $error = "Email is already in use!";
    } else {
      // Hash the password
      $hashed_password = password_hash($password, PASSWORD_BCRYPT);

      // Prepare the insert query for users
      $sql = "INSERT INTO users (email, address, city_id, gender_id, password) 
              VALUES (:email, :address, :city_id, :gender_id, :password)";
      $stmt = $pdo->prepare($sql);
      $params = [
          ':email' => $email,
          ':address' => $address,
          ':city_id' => $city_id,
          ':gender_id' => $gender_id,
          ':password' => $hashed_password
      ];

      // Execute the statement
      if ($stmt->execute($params)) {
        $user_id = $pdo->lastInsertId();

        // Assign the role securely
        $sql_role = "INSERT INTO user_role (user_id, role_id) VALUES (:user_id, :role)";
        $stmt_role = $pdo->prepare($sql_role);
        $stmt_role->execute([':user_id' => $user_id, ':role' => $role]);

        // Add default points to the user
        $sql_points = "INSERT INTO points (user_id, point_no) VALUES (:user_id, 0)";
        $stmt_points = $pdo->prepare($sql_points);
        $stmt_points->execute([':user_id' => $user_id]);

        // Redirect to login page
        header('Location: login.php');
        exit();
      } else {
        $error = "Registration failed!";
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
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
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Register</title>
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
    <div class="Register w-full px-4 md:px-20 py-10 md:py-[35px] bg-[#f9d4b3] md:rounded-[70px] rounded-[32px] flex flex-col md:flex-row justify-between items-center gap-10">
            <div class="relative w-full max-w-lg hidden md:block h-96">
                <img class="absolute w-[100px] h-[110px] left-0 top-[25%]" src="/pictures/Bitmap100x100.jpg" alt="Decorative Bitmap">
                <img class="absolute w-[40px] h-[50px] right-0 bottom-0" src="/pictures/Bitmap42x49.jpg" alt="Decorative Bitmap">
                <img class="absolute w-[52px] h-[55px] right-[10%] top-0" src="/pictures/Bitmap55x55.jpg" alt="Decorative Bitmap">
            </div>
      <form method="POST" class="RegisterForm w-full max-w-md flex flex-col justify-start items-start gap-5">
        <div class="BestCoffeeRoasted text-[#333333] text-7xl md:text-7xl font-bold font-['Red Rose'] leading-tight md:leading-[80px]">REGISTER</div>

        <?php if (isset($error)) { echo '<p class="error text-red-500">' . $error . '</p>'; } ?>
        <?php if (isset($success)) { echo '<p class="success text-green-500">' . $success . '</p>'; } ?>

        <div class="LightFieldDefault w-full relative">
          <input name="email" type="text" class="Rectangle w-full h-12 pl-6 bg-white rounded-3xl" placeholder="EMAIL" required>
        </div>
        <div class="LightFieldDefault w-full relative">
          <input name="address" type="text" class="Rectangle w-full h-12 pl-6 bg-white rounded-3xl" placeholder="ADDRESS" required>
        </div>
        <div class="DarkFieldDropdown w-full h-[76px] md:w-[200px] relative">
          <div class="Label left-0 top-0 absolute text-black text-xs font-bold font-['Lato'] uppercase">CITY</div>
          <select name="city" class="custom-select text-[#333333] Rectangle top-6 absolute w-full md:w-[200px] h-12 pl-6 bg-white rounded-3xl appearance-none" required>
            <option value="" disabled selected>SELECT</option>
            <?php
              foreach ($result_city as $row) {
                  echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
              }
            ?>
          </select>
        </div>
        <div class="DarkFieldDropdown w-full h-[76px] md:w-[200px] relative">
          <div class="Label left-0 top-0 absolute text-black text-xs font-bold font-['Lato'] uppercase">GENDER</div>
          <select name="gender" class="custom-select text-[#333333] Rectangle w-full md:w-[200px] top-6 absolute h-12 pl-6 bg-white rounded-3xl appearance-none" required>
            <option value="" disabled selected>SELECT</option>
            <?php
              foreach ($result_gender as $row) {
                  echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
              }
            ?>
          </select>
        </div>
        <div class="LightFieldDefault w-full relative">
          <input name="password" type="password" class="Rectangle w-full h-12 pl-6 bg-white rounded-3xl" placeholder="PASSWORD" required>
        </div>
        <div class="LightFieldDefault w-full relative">
          <input name="password_again" type="password" class="Rectangle w-full h-12 pl-6 bg-white rounded-3xl" placeholder="PASSWORD AGAIN" required>
        </div>
        <div class="flex items-center gap-5 mt-5">
          <button type="submit" class="SubmitBtn w-[110px] h-[50px] px-[18px] bg-[#f28d3c] rounded-[90px] justify-center items-center gap-2.5 flex">
            <div class="Submit text-white text-[15px] font-bold font-['Red Rose']">SUBMIT</div>
          </button>
          <span class="text-[#f28d3c] text-base font-red-rose">or</span>
          <button type="button" onclick="location.href ='google_login.php'" class="w-[50px] h-[50px] bg-[#f28d3c] rounded-full flex justify-center items-center overflow-hidden">
              <img class="w-[40px] h-[40px]" src="../pictures/google.png" alt="Google Login">
          </button>
        </div>
      </form>
    </div>
  </div>
  <?php include('footer.php'); ?>
  <script src="responsive_header.js"></script>
</body>
</html>
