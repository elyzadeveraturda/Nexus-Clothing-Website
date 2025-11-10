<?php
include("../includes/database.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and assign
    $product_code = $conn->real_escape_string($_POST['product_code']);
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = (float) $_POST['price'];
    $category = $conn->real_escape_string($_POST['category']);
    $sizes_raw = $_POST['sizes'];

    // Directory based on category
    $target_dir = "../assets/images/" . $category . "/";

    // Upload images
    $uploaded_images = [];
    foreach (['img1', 'img2', 'img3'] as $img_field) {
        if (!empty($_FILES[$img_field]['name'])) {
            $file_name = basename($_FILES[$img_field]['name']);
            $target_file = $target_dir . $file_name;

            // Check if file is an image
            $check = getimagesize($_FILES[$img_field]['tmp_name']);
            if ($check !== false) {
              if (move_uploaded_file($_FILES[$img_field]['tmp_name'], $target_file)) {
                $uploaded_images[] = "assets/images/{$category}/{$file_name}";
            } else {
                echo "❌ Failed to upload " . htmlspecialchars($file_name) . "<br>";
                echo "Target: " . htmlspecialchars($target_file) . "<br>";
                echo "Temp: " . htmlspecialchars($_FILES[$img_field]['tmp_name']) . "<br>";
                echo "Error code: " . $_FILES[$img_field]['error'] . "<br>";
                exit;
            }
            
            } else {
                die("❌ File " . htmlspecialchars($file_name) . " is not an image.");
            }
        }
    }

    // Main image path
    $main_image_path = $uploaded_images[0] ?? '';

    // Insert product
    $sql = "INSERT INTO products 
        (product_code, name, description, price, category, image_url)
        VALUES ('$product_code', '$name', '$description', $price, '$category', '$main_image_path')
        ON DUPLICATE KEY UPDATE 
            name='$name', description='$description', price=$price, category='$category', image_url='$main_image_path'";

    if ($conn->query($sql)) {
        echo "✅ Product added/updated: $product_code<br>";
    } else {
        die("❌ Product insert error: " . $conn->error);
    }

    // Insert additional images
    foreach ($uploaded_images as $img_url) {
        $check = $conn->query("SELECT * FROM product_images WHERE product_code='$product_code' AND image_url='$img_url'");
        if ($check->num_rows === 0) {
            $conn->query("INSERT INTO product_images (product_code, image_url) VALUES ('$product_code', '$img_url')");
        }
    }

    // Insert sizes
    $sizePairs = explode(",", $sizes_raw);
    foreach ($sizePairs as $pair) {
        if (strpos($pair, ":") !== false) {
            [$size, $stock] = explode(":", $pair);
            $size = $conn->real_escape_string(trim($size));
            $stock = (int)$stock;

            $check = $conn->query("SELECT * FROM product_sizes WHERE product_code='$product_code' AND size='$size'");
            if ($check->num_rows === 0) {
                $conn->query("INSERT INTO product_sizes (product_code, size, stock) VALUES ('$product_code', '$size', $stock)");
            }
        }
    }

    header("Location: dashboard.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>

<body>
    <section class="card">
        <h2>Add New Product</h2>
        <form method="post" action="add_product.php" enctype="multipart/form-data">
            <table>
                <thead>
                    <tr>
                        <th>Product Code</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Category</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" name="product_code" required></td>
                        <td><input type="text" name="name" required></td>
                        <td><textarea name="description" rows="3" required></textarea></td>
                        <td><input type="number" name="price" step="0.01" required></td>
                        <td>
                            <select name="category" required>
                                <option value="men">Men</option>
                                <option value="women">Women</option>
                                <option value="kids">Kids</option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table>
                <thead>
                    <tr>
                        <th>Image 1 (Main)</th>
                        <th>Image 2</th>
                        <th>Image 3</th>
                        <th>Sizes</th>
                        <th>Submit</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="file" name="img1" accept="image/*" required></td>
                        <td><input type="file" name="img2" accept="image/*"></td>
                        <td><input type="file" name="img3" accept="image/*"></td>
                        <td><input type="text" name="sizes" placeholder="M:10,L:5,XL:2" required></td>
                        <td><button type="submit">Add Product</button></td>
                    </tr>
                </tbody>
            </table>
        </form>
    </section>
</body>

</html>