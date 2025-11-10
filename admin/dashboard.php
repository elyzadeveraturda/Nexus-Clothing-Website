<?php
session_start();
include("../includes/database.php");

if (!isset($_SESSION['user']) || $_SESSION['user'] !== 'admin@gmail.com') {
    header("Location: ../pages/login.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>



<body>
    <header>
        <h1>Welcome, Admin</h1>
        <div class="logo-text">
            <img src="../assets/images/nexus.png" alt="NEXUS Logo" class="logo-img">
        </div>
        <div class="nav-right">
            <a href="../pages/logout.php" title="Logout"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </header>


    <?php include("update_status.php"); ?>
    <?php include("filter_product.php"); ?>
    <?php include("add_product.php"); ?>
</body>


</html>