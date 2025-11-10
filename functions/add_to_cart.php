<?php
session_start();
include("../includes/database.php");

// Make sure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit;
}
$user_id      = $_SESSION['user_id'];
$product_code = $_POST['product_code'];
$size         = $_POST['size'];
$qty          = (int)$_POST['quantity'];

// 1) Look for an existing cart entry
$check = $conn->prepare("
    SELECT id, quantity 
    FROM cart 
    WHERE user_id = ? 
      AND product_code = ? 
      AND size = ?
");
$check->bind_param("iss", $user_id, $product_code, $size);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    // 2a) If found, update quantity
    $check->bind_result($cart_id, $current_qty);
    $check->fetch();
    $new_qty = $current_qty + $qty;

    $upd = $conn->prepare("
        UPDATE cart 
        SET quantity = ? 
        WHERE id = ?
    ");
    $upd->bind_param("ii", $new_qty, $cart_id);
    $upd->execute();
} else {
    // 2b) Otherwise, insert a new row
    $ins = $conn->prepare("
        INSERT INTO cart (user_id, product_code, size, quantity) 
        VALUES (?, ?, ?, ?)
    ");
    $ins->bind_param("issi", $user_id, $product_code, $size, $qty);
    $ins->execute();
}

header("Location: ../pages/cart.php");
exit;