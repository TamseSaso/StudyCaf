<?php
session_start();
require_once '../database/database.php'; // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

// Step 1: Delete reservations older than 4 hours
$stmt = $pdo->prepare("
    DELETE FROM reservations 
    WHERE time_res < NOW() - INTERVAL 4 HOUR
");
$stmt->execute();

// Step 2: Handle reservation form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $reservation_date = $_POST['reservation_date'];
  $reservation_time = $_POST['reservation_time'];
  $chair_count = $_POST['chair_count'];

  // Combine the date and time into a single datetime string
  $reservation_datetime = $reservation_date . ' ' . $reservation_time;

  // Ensure the reservation is within the next 7 days
  $current_date = date('Y-m-d');
  $max_date = date('Y-m-d', strtotime('+7 days'));
  
  if ($reservation_date < $current_date || $reservation_date > $max_date) {
    $_SESSION['error'] = "Reservation date must be within the next 7 days.";
    header("Location: reserveatable.php");
    exit();
  }

  // Check if the time is within 6 AM to 10 PM
  if ($reservation_time < '06:00' || $reservation_time > '22:00') {
    $_SESSION['error'] = "Reservation time must be between 6 AM and 10 PM.";
    header("Location: reserveatable.php");
    exit();
  }

  // Check if the user already has an active reservation
  $stmt = $pdo->prepare("SELECT * FROM reservations WHERE user_id = :user_id");
  $stmt->execute([':user_id' => $user_id]);
  if ($stmt->rowCount() > 0) {
    $_SESSION['error'] = "You already have an active reservation.";
    header("Location: reserveatable.php");
    exit();
  }

  // If 1 chair is selected, assign a table with 2 chairs
  if ($chair_count == 1) {
    $chair_count = 2;
  }

  // Step 3: Check if a table with the required number of chairs is available
  $stmt = $pdo->prepare("
      SELECT t.id 
      FROM tables t 
      WHERE t.chair_no >= :chair_count
      AND t.id NOT IN (
          SELECT table_id FROM reservations 
          WHERE time_res = :reservation_time
      )
      LIMIT 1
  ");
  $stmt->execute([
    ':chair_count' => $chair_count,
    ':reservation_time' => $reservation_datetime
  ]);
  $table = $stmt->fetch();

  if ($table) {
    // Step 4: Insert the new reservation
    $stmt = $pdo->prepare("
        INSERT INTO reservations (user_id, table_id, time_res) 
        VALUES (:user_id, :table_id, :time_res)
    ");
    $stmt->execute([
      ':user_id' => $user_id,
      ':table_id' => $table['id'],
      ':time_res' => $reservation_datetime
    ]);

    $_SESSION['message'] = "Your table has been reserved!";
    header("Location: reserveatable.php");
    exit();
  } else {
    $_SESSION['error'] = "No tables available for the selected time.";
    header("Location: reserveatable.php");
    exit();
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
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
    <title>Reserve a Table</title>
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

    <div class="ReserveATable w-full bg-[#f9d4b3] md:rounded-[70px] rounded-[32px] px-[15px] py-[40px] md:px-[60px] md:py-[140px] flex flex-col md:flex-row justify-between items-center gap-10">
        <form action="reserveatable.php" method="POST" class="Form w-full md:w-[370px] flex flex-col justify-center items-start gap-[15px]">
          <div class="BestCoffeeRoasted self-stretch text-[#333333] text-6xl md:text-7xl font-bold font-['Red Rose'] leading-tight md:leading-[80px]">RESERVE A TABLE</div>
          
          <!-- Date Picker -->
          <div class="w-full">
              <label for="reservation_date" class="text-black text-xs font-bold font-['Lato'] uppercase">WHEN</label>
              <input type="date" name="reservation_date" required min="<?= date('Y-m-d'); ?>" max="<?= date('Y-m-d', strtotime('+7 days')); ?>" class="w-full h-12 px-3 bg-white rounded-3xl mt-1">
          </div>

          <!-- Time Picker -->
          <div class="w-full">
              <label for="reservation_time" class="text-black text-xs font-bold font-['Lato'] uppercase">TIME</label>
              <input type="time" name="reservation_time" required min="06:00" max="22:00" class="w-full h-12 px-3 bg-white rounded-3xl mt-1">
          </div>

          <!-- Chair Count Dropdown -->
          <div class="w-full">
              <label for="chair_count" class="text-black text-xs font-bold font-['Lato'] uppercase">NUMBER OF CHAIRS</label>
              <select name="chair_count" required class="custom-select w-full h-12 px-3 bg-white rounded-3xl mt-1 appearance-none">
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
              </select>
          </div>

          <!-- Submit Button -->
          <button type="submit" class="ReserveBtn w-full md:w-[140px] h-[50px] px-[18px] bg-[#f28d3c] rounded-[90px] mt-5 flex justify-center items-center">
              <div class="text-white text-[15px] font-bold">RESERVE</div>
          </button>

          <!-- Error or Success Messages -->
          <?php if (isset($_SESSION['error'])): ?>
              <p class="text-red-500 mt-3"><?= $_SESSION['error']; ?></p>
              <?php unset($_SESSION['error']); ?>
          <?php endif; ?>

          <?php if (isset($_SESSION['message'])): ?>
              <p class="text-green-500 mt-3"><?= $_SESSION['message']; ?></p>
              <?php unset($_SESSION['message']); ?>
          <?php endif; ?>
        </form>
    </div>
  </div>
  <?php include('footer.php'); ?>
  <script src="responsive_header.js"></script>
</body>
</html>