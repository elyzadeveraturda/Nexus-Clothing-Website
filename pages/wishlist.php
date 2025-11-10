<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include("../includes/database.php");
include("../includes/header.php");

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header("Location: login.php");
    exit();
}

// Handle wishlist item removal
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["remove_item"])) {
    $product_code = $_POST['product_code'];
    
    $delete_sql = "DELETE FROM wishlist WHERE user_id = ? AND product_code = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("is", $user_id, $product_code);
    $stmt->execute();
    
    header("Location: wishlist.php");
    exit();
}

// Fetch wishlist items
$sql = "
  SELECT 
    w.product_code,
    p.name,
    p.price,
    p.image_url,
    p.description
  FROM wishlist AS w
  JOIN products AS p
    ON w.product_code = p.product_code
  WHERE w.user_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$wishlist_items = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Wishlist</title>
    <link rel="stylesheet" href="../assets/css/cart.css">

</head>

<body>
    <div class="cart-container">
        <br><br><br>
        <h2>My Wishlist</h2>
        <div class="cart-item">
            <?php if (!empty($wishlist_items)): ?>
            <?php foreach ($wishlist_items as $item): ?>
            <div class="wishlist-item">
                <div class="wishlist-left">
                    <a href="product.php?code=<?= urlencode($item['product_code']) ?>">
                        <img src="../<?= htmlspecialchars($item['image_url']) ?>"
                            alt="<?= htmlspecialchars($item['name']) ?>" class="wishlist-img">
                    </a>

                    <div class="item-details">
                        <h3><?= htmlspecialchars($item['name']) ?></h3>
                        <p class="price">₱<?= number_format($item['price'], 2) ?></p>
                    </div>
                </div>

                <div class="wishlist-actions">
                    <form method="POST" action="../functions/add_to_cart.php">
                        <input type="hidden" name="product_code" value="<?= htmlspecialchars($item['product_code']) ?>">
                        <input type="hidden" name="size" value="M">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-primary">Add to Cart</button>
                    </form>

                    <form method="POST">
                        <input type="hidden" name="product_code" value="<?= htmlspecialchars($item['product_code']) ?>">
                        <button type="submit" name="remove_item" class="btn btn-danger"
                            onclick="return confirm('Remove this item from wishlist?')">
                            Remove
                        </button>
                    </form>
                </div>
            </div>

            <?php endforeach; ?>

            <?php else: ?>
            <div class="wishlist-empty">
                <p>Your wishlist is empty.</p>
                <a href="<?= htmlspecialchars($_SERVER['HTTP_REFERER'] ?? 'index.php') ?>"
                    class="btn btn-primary">Continue Shopping</a>
            </div>

            <?php endif; ?>
        </div>
    </div>
</body>

</html>