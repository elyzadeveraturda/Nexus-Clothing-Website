<?php
include("../includes/database.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['code'])) {
    $code = $_POST['code'];

    // 1. Delete from cart
    $stmt = $conn->prepare("DELETE FROM cart WHERE product_code = ?");
    $stmt->bind_param("s", $code);
    $stmt->execute();

    // 2. Delete from wishlist
    $stmt = $conn->prepare("DELETE FROM wishlist WHERE product_code = ?");
    $stmt->bind_param("s", $code);
    $stmt->execute();

    // 3. Delete from order_items (if your design keeps order history, this is optional but safe)
    $stmt = $conn->prepare("DELETE FROM order_items WHERE product_code = ?");
    $stmt->bind_param("s", $code);
    $stmt->execute();

    // 4. Delete from product_images
    $stmt = $conn->prepare("DELETE FROM product_images WHERE product_code = ?");
    $stmt->bind_param("s", $code);
    $stmt->execute();

    // 5. Delete from product_sizes
    $stmt = $conn->prepare("DELETE FROM product_sizes WHERE product_code = ?");
    $stmt->bind_param("s", $code);
    $stmt->execute();

    // 6. Finally, delete from products
    $stmt = $conn->prepare("DELETE FROM products WHERE product_code = ?");
    $stmt->bind_param("s", $code);
    $stmt->execute();
}

header("Location: dashboard.php");
exit();