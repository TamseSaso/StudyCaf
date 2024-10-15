<?php
session_start();
require_once '../database/database.php';

if (isset($_GET['user_id']) && isset($_SESSION['role_id']) && $_SESSION['role_id'] == 3) {
    $userId = $_GET['user_id'];

    try {
        $conn = new PDO("mysql:host=localhost;dbname=Studycaf", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch the current points of the user
        $stmtPoints = $conn->prepare("SELECT point_no FROM points WHERE user_id = :user_id");
        $stmtPoints->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmtPoints->execute();
        $pointsData = $stmtPoints->fetch(PDO::FETCH_ASSOC);

        if ($pointsData) {
            $points = $pointsData['point_no'];
            $points++;

            // Reset points to 1 if the user has reached 11 points
            if ($points > 10) {
                $points = 1;
            }

            // Update the points in the database
            $updatePoints = $conn->prepare("UPDATE points SET point_no = :points WHERE user_id = :user_id");
            $updatePoints->bindParam(':points', $points, PDO::PARAM_INT);
            $updatePoints->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $updatePoints->execute();

            echo "Point successfully added.";
        } else {
            // If no points record exists for the user, create one
            $insertPoints = $conn->prepare("INSERT INTO points (user_id, point_no) VALUES (:user_id, 1)");
            $insertPoints->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $insertPoints->execute();

            echo "Point successfully added.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request or insufficient permissions.";
}
?>