<?php
include("../includes/database.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $allowed = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
    if (in_array($status, $allowed)) {
        $stmt = $conn->prepare("UPDATE orders SET status=? WHERE id=?");
        $stmt->bind_param("si", $status, $order_id);
        $stmt->execute();
    }
    header("Location: dashboard.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>

<body>


    <!-- Orders Section -->
    <section class="card">
        <h2>Orders Management</h2>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User ID</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>View</th>
                </tr>
            </thead>
            <tbody>
                <?php
            $orders = $conn->query("SELECT * FROM orders WHERE status IN ('pending', 'processing', 'shipped', 'delivered') ORDER BY created_at DESC");
            while ($order = $orders->fetch_assoc()):
            ?>
                <tr>
                    <td><?= $order['id'] ?></td>
                    <td><?= $order['user_id'] ?></td>
                    <td>₱<?= number_format($order['total_amount'], 2) ?></td>


                    <td>
                        <form method="post" action="update_status.php">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            <select name="status" onchange="this.form.submit()">
                                <?php
                            $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
                            foreach ($statuses as $s):
                            ?>
                                <option value="<?= $s ?>" <?= $order['status'] === $s ? 'selected' : '' ?>>
                                    <?= ucfirst($s) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </td>
                    <td><?= $order['created_at'] ?></td>
                    <td><a href="view_order.php?id=<?= $order['id'] ?>">Products</a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>
</body>