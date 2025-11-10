<?php
include("../includes/database.php");

$user_id = $_SESSION['user_id'] ?? null;
$total = $total ?? 0;  
$payment_method = $payment_method ?? 'cash_on_delivery';  
$user_info_id = $user_info_id ?? 1; 

$order_sql = "INSERT INTO orders (user_id, total_amount, payment_method, status, user_info_id) 
              VALUES (?, ?, ?, 'pending', ?)";
$stmt = $conn->prepare($order_sql);
$stmt->bind_param("idsi", $user_id, $total, $payment_method, $user_info_id);
$stmt->execute();
$order_id = $conn->insert_id;
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../assets/css/home.css">
    <title>N E X U S</title>
</head>

<body>
    <section class="hero-banner">
        <video autoplay muted loop playsinline class="hero-video">
            <source src="../assets/videos/checkout.mov" type="video/mp4">
            Your browser does not support the video tag.
        </video>

        <div class="hero-content">
            <p class="hero-subtitle">Order placed successfully!</p>
            <h1 class="hero-title">Order ID: <?= $order_id ?></h1>
            <a href="intro.php" class="hero-button">Continue Shopping</a>
        </div>
    </section>
</body>

</html>