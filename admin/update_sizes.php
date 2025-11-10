<?php
include("../includes/database.php");

$code = $_POST['product_code'];
$size = $_POST['size'];
$stock = $_POST['stock'];

// Check if size entry exists
$check = $conn->query("SELECT * FROM product_sizes WHERE product_code='$code' AND size='$size'");
if ($check->num_rows > 0) {
    $stmt = $conn->prepare("UPDATE product_sizes SET stock = ? WHERE product_code = ? AND size = ?");
    $stmt->bind_param("iss", $stock, $code, $size);
    $stmt->execute();
} else {
    $stmt = $conn->prepare("INSERT INTO product_sizes (product_code, size, stock) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $code, $size, $stock);
    $stmt->execute();
}


header("Location: dashboard.php");