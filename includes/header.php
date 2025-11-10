<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXUS</title>
    <link rel="icon" href="../assets/images/nexus.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/header.css">
</head>

<body>

    <header>
        <div class="logo"></div>
        <div class="top-nav">
            <div class="nav-left">
                <a href="../pages/category.php?category=men">Men</a>
                <a href="../pages/category.php?category=women">Women</a>
                <a href="../pages/category.php?category=kids">Kids</a>

            </div>
            <div class="logo-text">
                <a href="index.php" title="Home">
                    <img src="../assets/images/nexus.png" alt="NEXUS Logo" class="logo-img">
                </a>
            </div>

            <div class="nav-right">
                <button id="search-toggle" title="Search"><i class="fas fa-search"></i></button>
                <a href="cart.php" title="Cart"><i class="fas fa-shopping-cart"></i></a>
                <a href="wishlist.php" title="Wishlist"><i class="fas fa-heart"></i></a>
                <?php if (isset($_SESSION['user'])): ?>
                <a href="account.php" title="My Account"><i class="fas fa-user-circle"></i></a>
                <a href="logout.php" title="Logout"><i class="fas fa-sign-out-alt"></i></a>
                <?php else: ?>
                <a href="login.php" title="Login"><i class="fas fa-user"></i></a>
                <?php endif; ?>

            </div>
        </div>


        <div class="search-slide" id="search-bar">
            <form action="search.php" method="get">
                <input type="text" name="q" placeholder="Search for products..." required />
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
    </header>

    <script src="../assets/js/headerScroll.js"></script>
    <script src="../assets/js/togglescript.js"></script>
</body>

</html>