<?php
session_start();
include("../includes/database.php");
include("../includes/header.php");

$product_code = $_GET['code'] ?? '';
if (!$product_code) {
    echo "Product not found!";
    exit;
}

// Get product
$stmt = $conn->prepare("SELECT * FROM products WHERE product_code = ?");
$stmt->bind_param("s", $product_code);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    echo "Product not found!";
    exit;
}
// Get all images from product_images
$image_stmt = $conn->prepare("SELECT image_url FROM product_images WHERE product_code = ?");
$image_stmt->bind_param("s", $product_code);
$image_stmt->execute();
$image_result = $image_stmt->get_result();

$product_images = [];
while ($row = $image_result->fetch_assoc()) {
    $product_images[] = $row['image_url'];
}


// Parse sizes from the sizes column
$size_stmt = $conn->prepare("
  SELECT size, stock
  FROM product_sizes
  WHERE product_code = ?
");
$size_stmt->bind_param("s", $product_code);
$size_stmt->execute();
$sizes_data = $size_stmt->get_result()->fetch_all(MYSQLI_ASSOC);


// Determine previous page URL
$previous_page = $_SERVER['HTTP_REFERER'] ?? '../pages/category.php';
?>

<!DOCTYPE html>
<html>

<head>
    <title><?= htmlspecialchars($product['name']) ?></title>
    <link rel="stylesheet" href="../assets/css/product.css">
</head>

<body>

    <div class="product-container">
        <div class="product-images">
            <?php if (count($product_images)): ?>
            <?php foreach ($product_images as $url): ?>
            <img src="../<?= htmlspecialchars($url) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
            <?php endforeach; ?>
            <?php else: ?>
            <!-- fallback to main product image -->
            <img src="../<?= htmlspecialchars($product['image_url']) ?>"
                alt="<?= htmlspecialchars($product['name']) ?>">
            <?php endif; ?>
        </div>


        <div class="product-details">
            <h2><?= htmlspecialchars($product["name"]) ?></h2>
            <div class="product-price">₱<?= number_format($product["price"], 2) ?></div>
            <p class="product-description"><?= htmlspecialchars($product["description"]) ?></p>

            <form method="POST" action="../functions/add_to_cart.php">
                <input type="hidden" name="product_code" value="<?= htmlspecialchars($product_code) ?>">

                <!-- SIZE PICKER -->
                <div class="size-options">
                    <span class="picker-label">Select Size:</span>
                    <?php foreach ($sizes_data as $i => $s): ?>
                    <?php 
        // e.g. id="size-S", name="size", value="S"
        $size     = htmlspecialchars($s['size']);
        $stock    = (int)$s['stock'];
        $input_id = "size-{$size}";
      ?>
                    <input type="radio" id="<?= $input_id ?>" name="size" value="<?= $size ?>"
                        <?= $i === 0 ? 'checked' : '' ?> <?= $stock < 1 ? 'disabled' : '' ?>>
                    <label for="<?= $input_id ?>">
                        <?= $size ?>
                        <?php if ($stock < 1): ?>
                        (Sold Out)
                        <?php endif; ?>
                    </label>
                    <?php endforeach; ?>
                </div>

                <label for="qty">Quantity</label>
                <div class="quantity-selector">
                    <button type="button" onclick="updateQuantity(-1)">−</button>
                    <input id="qty" type="number" name="quantity" value="1" min="1"
                        max="<?= max(array_column($sizes_data, 'stock')) ?>" required>
                    <button type="button" onclick="updateQuantity(1)">＋</button>
                </div>

                <button type="submit" class="btn-add-to-cart">Add to Cart</button>
            </form>

            <script>
            function updateQuantity(delta) {
                const qtyEl = document.getElementById('qty');
                let val = parseInt(qtyEl.value) + delta;
                const min = parseInt(qtyEl.min) || 1;
                const max = parseInt(qtyEl.max) || Infinity;

                if (val < min) val = min;
                if (val > max) val = max;
                qtyEl.value = val;
            }
            </script>

            <form method="POST" action="../functions/add_to_wishlist.php" style="margin-top: 10px;">
                <input type="hidden" name="product_code" value="<?= htmlspecialchars($product["product_code"]) ?>">
                <button type="submit" name="add_to_wishlist">Add to Wishlist</button>
            </form>

            <a href="<?= htmlspecialchars($previous_page) ?>" class="back-link">&larr; Back</a>
        </div>
    </div>


</body>
<script>
function updateQuantity(delta) {
    const el = document.getElementById('qty'),
        min = parseInt(el.min, 10) || 1,
        max = parseInt(el.max, 10) || Infinity,
        val = Math.min(max, Math.max(min, parseInt(el.value, 10) + delta));
    el.value = val;
}
</script>

</html>