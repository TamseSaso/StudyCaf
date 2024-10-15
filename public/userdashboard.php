<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
  session_start();
  require_once '../database/database.php'; // Include database connection
  require_once '../vendor/autoload.php'; // Include library for QR code generation (e.g., Endroid/qr-code)

  use Endroid\QrCode\QrCode;
  use Endroid\QrCode\Writer\PngWriter;

  if (isset($_SESSION['user_id'])) {
      $userId = $_SESSION['user_id'];
      try {
          // Fetch user information
          $stmt = $pdo->prepare("SELECT u.address, u.gender_id, g.name AS gender FROM users u LEFT JOIN genders g ON u.gender_id = g.id WHERE u.id = :user_id");
          $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
          $stmt->execute();
          $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);

          $address = $userInfo['address'] ?? 'N/A';
          $gender = $userInfo['gender'] ?? 'N/A';
          $role = ($_SESSION['role_id'] == 2) ? 'STUDENT' : 'USER';
          $email = $_SESSION['email'] ?? 'N/A';

          // Fetch user points
          $stmtPoints = $pdo->prepare("SELECT point_no FROM points WHERE user_id = :user_id");
          $stmtPoints->bindParam(':user_id', $userId, PDO::PARAM_INT);
          $stmtPoints->execute();
          $pointsData = $stmtPoints->fetch(PDO::FETCH_ASSOC);

          $points = $pointsData['point_no'] ?? 0;

          // Reset points to 1 if the user has 11 points
          if ($points >= 11) {
              $points = 1;
              $updatePoints = $pdo->prepare("UPDATE points SET point_no = :points WHERE user_id = :user_id");
              $updatePoints->bindParam(':points', $points, PDO::PARAM_INT);
              $updatePoints->bindParam(':user_id', $userId, PDO::PARAM_INT);
              $updatePoints->execute();
          }

          // Generate QR code for adding a point
          $qrCode = new QrCode("/add_point.php?user_id={$userId}");
          $qrCode->setSize(300);
          $qrCode->setMargin(10);
          $writer = new PngWriter();
          $qrCodeImagePath = 'pictures/qrcode_' . $userId . '.png';
          $writer->write($qrCode)->saveToFile($qrCodeImagePath);
      } catch (PDOException $e) {
          die("Connection failed: " . $e->getMessage());
      }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="style.css" rel="stylesheet">
    <title>User Dashboard</title>
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
    <div class="UserDashboard self-stretch p-5 bg-[#f9d4b3] rounded-[32px] md:rounded-[70px] justify-start items-center gap-5 inline-flex flex-col 2xl:flex-row">
    <div class="UserAura w-full md:w-full h-auto md:h-[743px] p-5 bg-[#f28d3c] rounded-[32px] md:rounded-[50px] flex flex-col justify-start items-center gap-[30px] md:gap-[55px]">
    <div class="UserPfp h-[358.43px] flex-col justify-center items-center gap-2 flex">
          <div class="User05c w-[300px] h-[302.43px] relative bg-[#f9d4b3] rounded-full overflow-hidden">
            <img class="User05c w-60 h-[275.52px] left-[30px] top-[30.24px] absolute" src="../pictures/User05cbig.png"></img>
          </div>
      </div>
      <div class="UserInfo w-full md:w-auto h-auto md:h-[290px] px-4 md:px-6 py-[15px] md:py-[25px] bg-[#f9d4b3] rounded-[32px] md:rounded-[40px] flex flex-col justify-center items-start gap-2.5">
          <div class="UserInfoText min-w-[250px] md:min-w-[369px] flex flex-col justify-start items-start gap-2">
              <div class="AddressRandomStreet12"><span class="text-black text-xl md:text-3xl font-normal font-['Red Rose'] leading-[20px] md:leading-[48px]">ADDRESS: </span><span class="text-black text-sm md:text-xl font-normal font-['Red Rose'] leading-[20px] md:leading-[48px]"><?php echo htmlspecialchars($address); ?></span></div>
              <div class="RoleUser"><span class="text-black text-xl md:text-3xl font-normal font-['Red Rose'] leading-[20px] md:leading-[48px]">ROLE: </span><span class="text-black text-sm md:text-xl font-normal font-['Red Rose'] leading-[20px] md:leading-[48px]"><?php echo htmlspecialchars($role); ?></span></div>
              <div class="GenderMale"><span class="text-black text-xl md:text-3xl font-normal font-['Red Rose'] leading-[20px] md:leading-[48px]">GENDER: </span><span class="text-black text-sm md:text-xl font-normal font-['Red Rose'] leading-[20px] md:leading-[48px]"><?php echo htmlspecialchars($gender); ?></span></div>
              <div class="EmailRandomemailGmailCom"><span class="text-black text-xl md:text-3xl font-normal font-['Red Rose'] leading-[20px] md:leading-[48px]">EMAIL: </span><span class="text-black text-sm md:text-xl font-normal font-['Red Rose'] leading-[20px] md:leading-[48px]"><?php echo htmlspecialchars($email); ?></span></div>
              <div class="ChangePassword text-[#f28d3c] text-xl md:text-2xl font-normal font-['Red Rose'] leading-[20px] md:leading-[48px]">CHANGE PASSWORD</div>
          </div>
      </div>
      </div>
      <div class="UserPoints w-full 2md:w-full p-5 bg-[#f28d3c] rounded-[32px] 2md:rounded-[50px] flex flex-col justify-start items-center gap-5 2md:gap-[70px]">
        <div class="PointsCount w-full 2md:w-[730px] px-[35px] py-[42px] bg-[#f9d4b3] rounded-[32px] 2md:rounded-[40px] flex flex-col justify-center items-center gap-5 2md:gap-2.5">
          <div class="Points grid grid-cols-2 2md:grid-cols-5 gap-[60px] 2md:gap-[35px]">
            <?php
            for ($i = 0; $i < 10; $i++) {
                $pointImage = ($i < $points) ? "Point-full.png" : "Point-none.png";
                $pointHeight = ($i < $points) ? "100px" : "87.50px";
                echo '<img class="CombinedShape w-[100px] h-[' . $pointHeight . ']" src="../pictures/' . $pointImage . '" alt="Point Icon" />';
            }
            ?>
          </div>
        </div>
        <div class="PointsCode w-full 2md:w-[730px] pl-4 md:pl-[115px] pr-4 md:pr-5 py-4 2md:py-[18px] bg-[#f9d4b3] rounded-[32px] 2md:rounded-[40px] flex flex-col-reverse 2md:flex-row justify-between items-center gap-4 2md:gap-0">
          <button onClick="window.location.reload()" class="LightButtonSecondaryText w-full 2md:w-[170px] h-12 rounded-3xl border border-[#f28d3c] flex justify-center items-center">
            <div class="Button text-center text-[#f28d3c] text-sm font-bold font-['Red Rose'] uppercase">RELOAD PAGE</div>
          </button>
          <div class="QrCode w-full 2md:w-[300px] h-full 2md:h-[300px] p-[20px] bg-[#f28d3c] rounded-[20px] 2md:rounded-[30px]">
            <img src="<?php echo $qrCodeImagePath; ?>" alt="QR Code" class="w-full h-full rounded-[20px]" />
          </div>
        </div>
      </div>
      </div>
    <?php
      include('footer.php')
    ?>
    <script src="responsive_header.js"></script>
  </div>
</body>
</html>