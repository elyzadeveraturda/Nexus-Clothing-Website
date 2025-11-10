<!-- Category Filter + Delete Products -->
<section class="card">
    <h2>Filter Products</h2>
    <form method="get">
        <select name="category">
            <option value="">-- Choose Category --</option>
            <option value="men">Men</option>
            <option value="women">Women</option>
            <option value="kids">Kids</option>
        </select>
        <button type="submit">Filter</button>
    </form>

    <?php if (isset($_GET['category']) && in_array($_GET['category'], ['men', 'women', 'kids'])): ?>
    <h3>Category: <?= ucfirst($_GET['category']) ?></h3>
    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Price</th>
                <th>Main Image</th>
                <th>Size & Stock</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $cat = $_GET['category'];
                $res = $conn->query("SELECT * FROM products WHERE category = '$cat'");
                while ($row = $res->fetch_assoc()):
                ?>
            <tr>
                <td><?= $row['product_code'] ?></td>
                <td><?= $row['name'] ?></td>
                <td>₱<?= $row['price'] ?></td>
                <td><img src="../<?= $row['image_url'] ?>" width="50"></td>
                <td>
                    <form method="post" action="update_sizes.php">
                        <input type="hidden" name="product_code" value="<?= $row['product_code'] ?>">
                        <label>Size:
                            <input type="text" name="size" required>
                        </label><br><br>
                        <label>Stock:
                            <input type="number" name="stock" required>
                        </label>
                        <button type="submit">Update Stock</button>
                    </form>

                </td>
                <td>
                    <form method="post" action="delete_product.php">
                        <input type="hidden" name="code" value="<?= $row['product_code'] ?>">
                        <button type="submit" class="danger">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php endif; ?>
</section>