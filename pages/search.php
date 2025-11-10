<?php
session_start();
ini_set('display_errors',1);
error_reporting(E_ALL);


include("../includes/database.php");
include("../includes/header.php");

$searchQuery = $_GET['q'] ?? '';

if (!$searchQuery) {
    echo "<p style='text-align:center;margin-top:100px;'>Please enter a search term.</p>";
    exit();
}

$searchQuery = $conn->real_escape_string($searchQuery);

$sql = "
    SELECT * FROM products
    WHERE name LIKE '%$searchQuery%'
       OR description LIKE '%$searchQuery%'
       OR category LIKE '%$searchQuery%'
";

$result = $conn->query($sql);
?>
<html>

<head>
    <title>Search Results - NEXUS</title>
    <link rel="stylesheet" href="../assets/css/product.css">

<body>



    <div style="padding: 40px;">
        <h2>Search results for: <strong><?= htmlspecialchars($searchQuery) ?></strong></h2>

        <?php if ($result && $result->num_rows > 0): ?>
        <div style="display: flex; flex-wrap: wrap; gap: 20px; margin-top: 30px;">
            <?php while ($row = $result->fetch_assoc()): ?>
            <div style="width: 200px; border: 1px solid #ccc; border-radius: 10px; padding: 10px;">
                <a href="product.php?code=<?= $row['product_code'] ?>">
                    <img src="../<?= $row['image_url'] ?>" alt="<?= $row['name'] ?>"
                        style="width: 100%; height: auto; border-radius: 8px;">
                    <h4 style="margin: 10px 0 5px;"><?= $row['name'] ?></h4>
                    <p>₱<?= number_format($row['price'], 2) ?></p>
                    <p style="font-size: 0.85em; color: gray;"><?= ucfirst($row['category']) ?></p>
                </a>
            </div>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <p>No products found matching your search.</p>
        <?php endif; ?>
    </div>
</body>

</html>