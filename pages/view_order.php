<?php
include("../includes/database.php");

$order_id = $_GET['id'] ?? null;
if (!$order_id) {
    die("Order ID is missing!");
}

// Get order
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    die("Order not found!");
}

// Get order items
$stmt = $conn->prepare("SELECT oi.*, p.name, p.image_url 
                        FROM order_items oi 
                        JOIN products p ON oi.product_code = p.product_code 
                        WHERE oi.order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order #<?= $order['id'] ?> Details</title>
    <link rel="stylesheet" href="../assets/css/account.css">
</head>

<body>
    <div class="account-container">
        <section class="card">
            <h2>Order #<?= $order['id'] ?> Details</h2>

            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Image</th>
                        <th>Size</th>
                        <th>Quantity</th>
                        <th>Price (each)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><img src="../<?= htmlspecialchars($item['image_url']) ?>"
                                alt="<?= htmlspecialchars($item['name']) ?>" width="50"></td>
                        <td><?= htmlspecialchars($item['size']) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td>₱<?= number_format($item['price'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <p><a href="account.php">Back to Account</a></p>
        </section>
    </div>

</body>

</html>