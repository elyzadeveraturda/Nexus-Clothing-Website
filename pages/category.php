<?php
session_start();
include("../includes/database.php");
include("../includes/header.php");

$user_id = $_SESSION['user_id'] ?? null;

$category = $_GET['category'] ?? '';

$allowed_categories = ['men', 'women', 'kids'];
if (!in_array($category, $allowed_categories)) {
    die("Invalid category.");
}

// Handle wishlist toggle
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["product_code"])) {
    if (!$user_id) {
        header("Location: login.php");
        exit();
    }

    $product_code = $_POST["product_code"];

    // Check if product is already in wishlist
    $check_sql = "SELECT * FROM wishlist WHERE user_id = ? AND product_code = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("is", $user_id, $product_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $delete_sql = "DELETE FROM wishlist WHERE user_id = ? AND product_code = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("is", $user_id, $product_code);
        $stmt->execute();
    } else {
        $insert_sql = "INSERT INTO wishlist (user_id, product_code) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("is", $user_id, $product_code);
        $stmt->execute();
    }

    header("Location: " . $_SERVER['PHP_SELF'] . "?category=" . $category);
    exit();
}

// Fetch products by category
$sql = "SELECT * FROM products WHERE category = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();
$products = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $is_favorite = false;
        if ($user_id) {
            $wishlist_sql = "SELECT * FROM wishlist WHERE user_id = ? AND product_code = ?";
            $wishlist_stmt = $conn->prepare($wishlist_sql);
            $wishlist_stmt->bind_param("is", $user_id, $row['product_code']);
            $wishlist_stmt->execute();
            $wishlist_result = $wishlist_stmt->get_result();
            $is_favorite = ($wishlist_result->num_rows > 0);
        }
        $row['is_favorite'] = $is_favorite;
        $products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title><?= ucfirst($category) ?> Products</title>
    <link rel="stylesheet" href="../assets/css/category.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <br>
    <br>
    <br>
    <h2 style="text-align:center"><?= ucfirst($category) ?> Products</h2>

    <div class="product-grid">
        <?php foreach ($products as $product): ?>
        <div class="product-card">
            <form method="POST" action="category.php?category=<?= urlencode($category) ?>" class="wishlist-form">
                <input type="hidden" name="product_code" value="<?= htmlspecialchars($product['product_code']) ?>">
                <button type="submit" class="wishlist-btn">
                    <i class="<?= $product['is_favorite']
                       ? 'fa-solid fa-heart fave-icon filled'
                       : 'fa-regular fa-heart fave-icon' ?>"></i>
                </button>
            </form>

            <a href="product.php?code=<?= urlencode($product['product_code']) ?>">
                <img src="../<?= htmlspecialchars($product['image_url']) ?>"
                    alt="<?= htmlspecialchars($product['name']) ?>" loading="lazy">
            </a>

            <div class="product-info">
                <div class="product-name"><?= htmlspecialchars($product['name']) ?></div>
                <div class="product-price">₱<?= number_format($product['price'], 2) ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>


</body>

</html>