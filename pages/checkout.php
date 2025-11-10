<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include("../includes/database.php");
include '../includes/header.php';
$user_id = $_SESSION['user_id'] ?? null;
if (! $user_id) {
  header("Location: login.php");
  exit;
}


// Fetch cart items
$cart_sql = "SELECT c.*, p.name, p.price, p.image_url 
             FROM cart c 
             JOIN products p ON c.product_code = p.product_code 
             WHERE c.user_id = ?";
$stmt = $conn->prepare($cart_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Calculate total
$total = array_reduce($cart_items, fn($sum, $item) => $sum + ($item['price'] * $item['quantity']), 0);

// Handle order submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["place_order"])) {
    // Get shipping details
    $country = $_POST['country'];
    $region = $_POST['region'];
    $province = $_POST['province'];
    $city = $_POST['city'];
    $barangay = $_POST['barangay'];
    $street = $_POST['street'];
    $contact = $_POST['contact_number'];
    $payment_method = $_POST['payment_method'];

    // Get cart items
    $cart_sql = "SELECT c.*, p.name, p.price 
                 FROM cart c 
                 JOIN products p ON c.product_code = p.product_code 
                 WHERE c.user_id = ?";
    $stmt = $conn->prepare($cart_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $cart_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    if (empty($cart_items)) {
        $error = "Your cart is empty!";
    } else {
        // Insert shipping info
        $info_sql = "INSERT INTO user_info (user_id, country, region, province, city_municipality, barangay, street, contact_number) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($info_sql);
        $stmt->bind_param("isssssss", $user_id, $country, $region, $province, $city, $barangay, $street, $contact);
        $stmt->execute();
        $user_info_id = $conn->insert_id;

  

        // Insert into orders
        $order_sql = "INSERT INTO orders (user_id, total_amount, payment_method, status, user_info_id) 
                      VALUES (?, ?, ?, 'pending', ?)";
        $stmt = $conn->prepare($order_sql);
        $stmt->bind_param("idsi", $user_id, $total, $payment_method, $user_info_id);
        $stmt->execute();
        $order_id = $conn->insert_id;

        // Insert items
        foreach ($cart_items as $item) {
            $item_sql = "INSERT INTO order_items (order_id, product_code, size, quantity, price) 
                         VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($item_sql);
            $stmt->bind_param("issid", $order_id, $item['product_code'], $item['size'], $item['quantity'], $item['price']);
            $stmt->execute();
        }

        // Clear cart
        $clear_cart_sql = "DELETE FROM cart WHERE user_id = ?";
        $stmt = $conn->prepare($clear_cart_sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $success = "Order placed successfully! Order ID: " . $order_id;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Checkout | NEXUS</title>
    <link rel="stylesheet" href="../assets/css/checkout.css">

</head>

<body>
    <h2 class="section-title"></h2>
    <?php if (isset($success)): ?>
    <?php include 'order.php'; ?>
    <?php elseif (isset($error)): ?>
    <div class="alert alert-error"><?= $error ?></div>
    <a href="cart.php" class="btn btn-primary">Back to Cart</a>
    <?php elseif (empty($cart_items)): ?>
    <div class="alert alert-error">Your cart is empty!</div>
    <a href="index.php" class="btn btn-primary">Continue Shopping</a>
    <?php else: ?>
    <div class="checkout-container">
        <div class="checkout-form">

            <form method="POST">
                <h3>Shipping Address</h3>
                <!-- Country fixed to Philippines -->

                <div class="form-group">
                    <select name="country" required readonly>
                        <option value="Philippines" selected>Philippines</option>
                    </select>
                </div>

                <!-- Region Dropdown -->
                <div class="form-group">
                    <select name="region" id="region" required>
                        <option value="">Select Region</option>
                    </select>
                </div>

                <!-- Province Dropdown -->
                <div class="form-group">
                    <select name="province" id="province" required>
                        <option value="">Select Province</option>
                    </select>
                </div>

                <!-- City/Municipality Dropdown -->
                <div class="form-group">
                    <select name="city" id="city" required>
                        <option value="">Select City/Municipality</option>
                    </select>
                </div>

                <div class="form-group"><input type="text" name="barangay" placeholder="Barangay" required></div>
                <div class="form-group"><input type="text" name="street" placeholder="Street" required></div>
                <div class="form-group"><input type="text" name="contact_number" placeholder="Contact Number" required>
                </div>

                <h3>Payment Method</h3>
                <div class="form-group">
                    <select name="payment_method" required>
                        <option value="">Select Payment Method</option>
                        <option value="credit_card">Credit Card</option>
                        <option value="debit_card">Debit Card</option>
                        <option value="paypal">PayPal</option>
                        <option value="cash_on_delivery">Cash on Delivery</option>
                    </select>
                </div>

                <button type="submit" name="place_order" class="place-order-btn">
                    Place Order (₱<?= number_format($total, 2) ?>)
                </button>
            </form>

            <?php endif; ?>
        </div>

        <?php if (!empty($cart_items) && !isset($success)): ?>
        <div class="order-summary">
            <h3>Order Summary</h3>

            <?php foreach ($cart_items as $item): ?>
            <div class="order-item">
                <img src="../<?= htmlspecialchars($item['image_url']) ?>" …>

                <div class="item-info">
                    <div><?= htmlspecialchars($item['name']) ?></div>
                    <div>Size: <?= htmlspecialchars($item['size']) ?></div>
                    <div>Qty: <?= $item['quantity'] ?></div>
                </div>
                <div class="item-price">
                    ₱<?= number_format($item['price'] * $item['quantity'], 2) ?>
                </div>
            </div>
            <?php endforeach; ?>

            <div class="total-section">
                <div class="total-amount">
                    Total: ₱<?= number_format($total, 2) ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>
<script>
fetch("../assets/js/ph_locations.json")
    .then(res => res.json())
    .then(data => {
        const regionSelect = document.getElementById("region");
        const provinceSelect = document.getElementById("province");
        const citySelect = document.getElementById("city");

        // Populate Regions
        for (const region in data) {
            regionSelect.add(new Option(region, region));
        }

        // On region change
        regionSelect.addEventListener("change", () => {
            provinceSelect.innerHTML = '<option value="">Select Province</option>';
            citySelect.innerHTML = '<option value="">Select City/Municipality</option>';

            const provinces = data[regionSelect.value];
            for (const province in provinces) {
                provinceSelect.add(new Option(province, province));
            }
        });

        // On province change
        provinceSelect.addEventListener("change", () => {
            citySelect.innerHTML = '<option value="">Select City/Municipality</option>';
            const cities = data[regionSelect.value][provinceSelect.value];
            cities.forEach(city => {
                citySelect.add(new Option(city, city));
            });
        });
    })
    .catch(error => console.error("Error loading location data:", error));
</script>

</html>