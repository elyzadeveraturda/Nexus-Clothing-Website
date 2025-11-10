<?php
include("includes/database.php");

$file = fopen(__DIR__ . "/products.csv", "r");
if (!$file) {
    die("❌ Could not open file.");
}

fgetcsv($file); // skip header

while (($data = fgetcsv($file)) !== FALSE) {
    if (empty(array_filter($data))) continue;

    // Assign values
    $product_code = $conn->real_escape_string($data[0]);
    $name         = $conn->real_escape_string($data[1]);
    $description  = $conn->real_escape_string($data[2]);
    $price        = (float) $data[3];
    $category     = $conn->real_escape_string($data[4]);
    $img1         = $conn->real_escape_string($data[5]);
    $img2         = $conn->real_escape_string($data[6]);
    $img3         = $conn->real_escape_string($data[7]);
    $sizes_raw    = $data[8]; // e.g. "XL:12,M:14,XS:13"

    // Insert into products (using img1 as main image)
    $main_image_path = "assets/images/{$category}/{$img1}";
    $sql = "INSERT INTO products 
        (product_code, name, description, price, category, image_url)
        VALUES ('$product_code', '$name', '$description', $price, '$category', '$main_image_path')
        ON DUPLICATE KEY UPDATE 
            name='$name', description='$description', price=$price, category='$category', image_url='$main_image_path'";
    
    if ($conn->query($sql)) {
        echo "✅ Product added: $product_code<br>";
    } else {
        echo "❌ Error inserting product $product_code: " . $conn->error . "<br>";
        continue;
    }

    // Insert images (only if not already existing)
    foreach ([$img1, $img2, $img3] as $img) {
        if ($img) {
            $img_path = "assets/images/{$category}/{$img}";

            // Check if image already exists
            $check = $conn->query("SELECT * FROM product_images WHERE product_code='$product_code' AND image_url='$img_path'");
            if ($check->num_rows === 0) {
                $conn->query("INSERT INTO product_images (product_code, image_url) VALUES ('$product_code', '$img_path')");
            }
        }
    }

    // Insert sizes
    $sizePairs = explode(",", $sizes_raw); // ["XL:12", "M:14", "XS:13"]
    foreach ($sizePairs as $pair) {
        if (strpos($pair, ":") !== false) {
            [$size, $stock] = explode(":", $pair);
            $size = trim($conn->real_escape_string($size));
            $stock = (int) $stock;

            // Check if size already exists
            $check = $conn->query("SELECT * FROM product_sizes WHERE product_code='$product_code' AND size='$size'");
            if ($check->num_rows === 0) {
                $conn->query("INSERT INTO product_sizes (product_code, size, stock) VALUES ('$product_code', '$size', $stock)");
            }
        }
    }
}

fclose($file);
echo "<br>🎉 Import finished.";
?>