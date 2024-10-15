<?php
session_start();
include('../database/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['name']) && isset($_POST['description'])) {
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM categories WHERE name = :name");
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            echo "<script>alert('Category already exists.'); window.location.href='add_category_site.php';</script>";
        } else {
            $stmt = $pdo->prepare("INSERT INTO categories (name, description) VALUES (:name, :description)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            
            if ($stmt->execute()) {
                echo "<script>alert('Category added successfully.'); window.location.href='add_category_site.php';</script>";
            } else {
                echo "<script>alert('Error adding category.'); window.location.href='add_category_site.php';</script>";
            }
        }
    } else {
        echo "<script>alert('Please provide all required fields.'); window.location.href='add_category_site.php';</script>";
    }
} else {
    header("Location: add_category_site.php");
    exit();
}
?>