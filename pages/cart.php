<?php
session_start();
ini_set('display_errors',1);
error_reporting(E_ALL);

include("../includes/database.php");
include("../includes/header.php");
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header("Location: login.php");
    exit;
}
// Handle quantity update
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_quantity"])) {
    $cart_id = $_POST['cart_id'];
    $new_quantity = (int) $_POST['quantity'];
    
    if ($new_quantity > 0) {
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("iii", $new_quantity, $cart_id, $user_id);
        $stmt->execute();
    }
    
    header("Location: cart.php");
    exit();
}

// Handle item removal
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["remove_item"])) {
    $cart_id = $_POST['cart_id'];
    
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cart_id, $user_id);
    $stmt->execute();
    
    header("Location: cart.php");
    exit();
}

// Fetch cart items
$sql = "
  SELECT c.*, p.name, p.price, p.image_url
  FROM cart AS c
  JOIN products AS p ON c.product_code = p.product_code
  WHERE c.user_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$total = 0;
foreach ($items as $item) {
    $total += $item['price'] * $item['quantity'];
}

$total = array_reduce($items, function($sum, $item) {
    return $sum + ($item['price'] * $item['quantity']);
}, 0);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cart</title>
    <link rel="stylesheet" href="../assets/css/cart.css">
</head>

<body>
    <div class="cart-container">
        <br>
        <br>
        <br>
        <h2>Your Cart</h2>

        <?php if (!empty($items)): ?>
        <div class="cart-items">
            <?php foreach ($items as $item): ?>
            <div class="cart-item">

                <!-- Clickable product image -->
                <a href="product.php?code=<?= urlencode($item['product_code']) ?>">
                    <img src="../<?= htmlspecialchars($item['image_url']) ?>"
                        alt="<?= htmlspecialchars($item['name']) ?>" width="150">
                </a>

                <div class="item-details">
                    <h3><?= htmlspecialchars($item['name']) ?></h3>
                    <p>Size: <?= htmlspecialchars($item['size']) ?></p>
                    <p>Price: ₱<?= number_format($item['price'], 2) ?></p>

                    <!-- Quantity update form -->
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                        <label>Quantity:</label>
                        <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" max="10">
                        <button type="submit" name="update_quantity">Update</button>
                    </form>

                    <!-- Remove item form -->
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                        <button type="submit" name="remove_item"
                            onclick="return confirm('Remove this item?')">Remove</button>
                    </form>
                </div>

                <div class="item-total">
                    ₱<?= number_format($item['price'] * $item['quantity'], 2) ?>
                </div>

            </div>
            <?php endforeach; ?>
        </div>


        <div class="cart-summary">
            <h3>Total: ₱<?= number_format($total, 2) ?></h3>
            <a href="<?= htmlspecialchars($_SERVER['HTTP_REFERER'] ?? 'index.php') ?>" class="btn btn-primary">Continue
                Shopping</a>

            <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
        </div>
        <?php else: ?>
        <div class="wishlist-empty">
            <p>Your cart is empty.</p>
            <a href="<?= htmlspecialchars($_SERVER['HTTP_REFERER'] ?? 'index.php') ?>" class="btn btn-primary">Continue
                Shopping</a>
        </div>
        <?php endif; ?>
    </div>
</body>


</html>