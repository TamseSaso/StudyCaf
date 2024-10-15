<?php 
require_once '../database/database.php';

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $email_username = strstr($email, '@', true);

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        // Fetch the user's role and picture_certificate_id
        $stmt = $pdo->prepare("SELECT r.id as role_id, u.picture_certificate_id FROM users u 
            INNER JOIN user_role ur ON u.id = ur.user_id 
            INNER JOIN roles r ON r.id = ur.role_id 
            WHERE u.id = :user_id");

        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user_data && isset($user_data['role_id'])) {
            $_SESSION['role_id'] = $user_data['role_id'];
            
            // Only show the "Become a student" link if the role is not student and picture_certificate_id is null
            if ($user_data['role_id'] != 2 && is_null($user_data['picture_certificate_id'])) {
                $become_student_link = "become_student.php";
            } else {
                $become_student_link = null;
            }

            if ($user_data['role_id'] == 3) {
                $index_link = "index.php";
                $index_about_link = "index.php#about";
                $menu_link = "menu.php?category=Coffee";
                $index_contact_link = "index.php#contact";
                $add_student_link = "add_student_site.php";
                $add_product_link = "add_product_site.php";
                $edit_product_link = "edit_product_site1.php";
                $delete_product_link = "delete_product_site.php";
                $delete_student_link = "delete_student_site.php";
                $userdashboard_link = "userdashboard.php";
                $add_category_link = "add_category_site.php";
                $edit_category_link = "edit_category_site1.php";
                $delete_category_link = "delete_category_site.php";
                $add_city_link = "add_city_site.php";
                $edit_city_link = "edit_city_site1.php";
                $delete_city_link = "delete_city_site.php";
                $scan_code_link = "scan_code.php";
            } else {
                $index_link = "index.php";
                $index_about_link = "index.php#about";
                $menu_link = "menu.php?category=Coffee";
                $index_contact_link = "index.php#contact";
                $add_student_link = null;
                $add_product_link = null;
                $edit_product_link = null;
                $delete_product_link = null;
                $delete_student_link = null;
                $userdashboard_link = "userdashboard.php";
                $add_category_link = null;
                $edit_category_link = null;
                $delete_category_link = null;
                $add_city_link = null;
                $edit_city_link = null;
                $delete_city_link = null;
                $scan_code_link = null;
            }
        }
    }
} else {
    include 'header-guest.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Header</title>
</head>
<div class="HeaderUser w-full h-[50px] px-[60px] justify-between items-center inline-flex">
    <?php if(isset($index_link)) { ?>
    <a class="Caf text-white text-[40px] font-bold font-['Red Rose'] leading-[48px]" href="<?php echo $index_link?>">STUDYCAF</a>
    <?php } ?>
    <div class="Pages h-8 px-[35px] justify-center items-center gap-20 flex">
        <?php if(isset($index_about_link)) { ?>
            <a class="About text-center text-white text-xl font-normal font-Lato leading-loose hover:text-[#f28d3c]" href="<?php echo $index_about_link?>">About</a>
        <?php } ?>
        <?php if(isset($menu_link)) { ?>
            <a class="Menu text-center text-white text-xl font-normal font-Lato leading-loose hover:text-[#f28d3c]" href="<?php echo $menu_link?>">Menu</a>
        <?php } ?>
        <?php if(isset($index_contact_link)) { ?>
            <a class="Contact text-center text-white text-xl font-normal font-Lato leading-loose hover:text-[#f28d3c]" href="<?php echo $index_contact_link?>">Contact</a>
        <?php } ?>
    </div>
    <div class="User group justify-end inline-block">
        <button class="HelloUser flex items-center gap-[17px] text-white text-sm font-bold font-['Red Rose'] uppercase">HELLO, <?php echo strtoupper($email_username); ?>
            <div class="User05c w-[50px] h-[50px] relative bg-[#f9d4b3] rounded-full overflow-hidden">
                <img class="User05c w-[38px] h-[45px] left-[6px] top-[6.08px] absolute" src="../pictures/User05csmall.png">
            </div>
        </button>
        <div class="dropdown-content hidden absolute bg-black w-[180px] shadow-lg z-10 rounded-3xl group-hover:block">
            <?php if(isset($become_student_link)) { ?>
                <a class="text-white py-[12px] px-[14px] block hover:text-[#f28d3c]" href="<?php echo $become_student_link?>">Become a student</a>
            <?php } ?>
            <?php if(isset($userdashboard_link)) { ?>
                <a class="text-white py-[12px] px-[14px] block hover:text-[#f28d3c]" href="<?php echo $userdashboard_link?>">Membership card</a>
            <?php } ?>
            <?php if(isset($scan_code_link)) { ?>
                <a class="text-white py-[12px] px-[14px] block hover:text-[#f28d3c]" href="<?php echo $scan_code_link?>">Scan code</a>
            <?php } ?>
            <?php if(isset($add_student_link)) { ?>
                <a class="text-white py-[12px] px-[14px] block hover:text-[#f28d3c]" href="<?php echo $add_student_link?>">Add student</a>
            <?php } ?>
            <?php if(isset($delete_student_link)) { ?>
                <a class="text-white py-[12px] px-[14px] block hover:text-[#f28d3c]" href="<?php echo $delete_student_link?>">Delete student</a>
            <?php } ?>
            <?php if(isset($add_product_link)) { ?>
                <a class="text-white py-[12px] px-[14px] block hover:text-[#f28d3c]" href="<?php echo $add_product_link?>">Add product</a>
            <?php } ?>
            <?php if(isset($edit_product_link)) { ?>
                <a class="text-white py-[12px] px-[14px] block hover:text-[#f28d3c]" href="<?php echo $edit_product_link?>">Edit product</a>
            <?php } ?>
            <?php if(isset($delete_product_link)) { ?>
                <a class="text-white py-[12px] px-[14px] block hover:text-[#f28d3c]" href="<?php echo $delete_product_link?>">Delete product</a>
            <?php } ?>
            <?php if(isset($add_category_link)) { ?>
                <a class="text-white py-[12px] px-[14px] block hover:text-[#f28d3c]" href="<?php echo $add_category_link?>">Add category</a>
            <?php } ?>
            <?php if(isset($edit_category_link)) { ?>
                <a class="text-white py-[12px] px-[14px] block hover:text-[#f28d3c]" href="<?php echo $edit_category_link?>">Edit category</a>
            <?php } ?>
            <?php if(isset($delete_category_link)) { ?>
                <a class="text-white py-[12px] px-[14px] block hover:text-[#f28d3c]" href="<?php echo $delete_category_link?>">Delete category</a>
            <?php } ?>
            <?php if(isset($add_city_link)) { ?>
                <a class="text-white py-[12px] px-[14px] block hover:text-[#f28d3c]" href="<?php echo $add_city_link?>">Add city</a>
            <?php } ?>
            <?php if(isset($edit_city_link)) { ?>
                <a class="text-white py-[12px] px-[14px] block hover:text-[#f28d3c]" href="<?php echo $edit_city_link?>">Edit city</a>
            <?php } ?>
            <?php if(isset($delete_city_link)) { ?>
                <a class="text-white py-[12px] px-[14px] block hover:text-[#f28d3c]" href="<?php echo $delete_city_link?>">Delete city</a>
            <?php } ?>
            <a class="text-white py-[12px] px-[14px] block hover:text-[#f28d3c]" href="logout.php">Logout</a>
        </div>
    </div>
</div>
</html>