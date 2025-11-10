<?php
session_start();
include("../includes/database.php");
include("../includes/header.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get user basic info
$sql_user = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql_user);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Get shipping info
$sql_info = "SELECT * FROM user_info WHERE user_id = ? ORDER BY created_at DESC LIMIT 1";
$stmt = $conn->prepare($sql_info);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_info = $stmt->get_result()->fetch_assoc();

// Get orders
$sql_orders = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql_orders);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Account | NEXUS</title>
    <link rel="stylesheet" href="../assets/css/account.css">
</head>

<body>
    <div class="account-container">
        <h1>My Account</h1>

        <section class="user-info">
            <h2>Personal Information</h2>
            <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?> <?= htmlspecialchars($user['surname']) ?>
            </p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>

            <?php if ($user_info): ?>
            <h3>Shipping Info</h3>
            <p><strong>Address:</strong> <?= htmlspecialchars($user_info['street']) ?>,
                <?= htmlspecialchars($user_info['barangay']) ?>,
                <?= htmlspecialchars($user_info['city_municipality']) ?>,
                <?= htmlspecialchars($user_info['province']) ?>,
                <?= htmlspecialchars($user_info['region']) ?>,
                <?= htmlspecialchars($user_info['country']) ?>
            </p>
            <p><strong>Contact:</strong> <?= htmlspecialchars($user_info['contact_number']) ?></p>
            <?php else: ?>
            <p>No shipping info available.</p>
            <?php endif; ?>
        </section>

        <section class="orders">
            <h2>Order History</h2>
            <?php if (count($orders) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>View</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>#<?= $order['id'] ?></td>
                        <td>₱<?= number_format($order['total_amount'], 2) ?></td>
                        <td><?= htmlspecialchars($order['payment_method']) ?></td>
                        <td><?= ucfirst($order['status']) ?></td>
                        <td><?= date('F j, Y', strtotime($order['created_at'])) ?></td>
                        <td><a href="view_order.php?id=<?= $order['id'] ?>">Products</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p>You haven’t placed any orders yet.</p>
            <?php endif; ?>
        </section>
    </div>
</body>

</html>