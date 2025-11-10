<?php
session_start();
include("../includes/database.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['product_code'])) {
    $user_id = $_SESSION['user_id'];
    $product_code = $_POST['product_code'];

    // Check if already in wishlist
    $check_stmt = $conn->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_code = ?");
    $check_stmt->bind_param("is", $user_id, $product_code);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows === 0) {
        // Insert into wishlist
        $insert_stmt = $conn->prepare("INSERT INTO wishlist (user_id, product_code) VALUES (?, ?)");
        $insert_stmt->bind_param("is", $user_id, $product_code);
        $insert_stmt->execute();
    }

    // Redirect back to product page
    header("Location: ../pages/wishlist.php?code=" . urlencode($product_code));
    exit();
}

// If accessed directly without POST
header("Location: ../pages/index.php");
exit();