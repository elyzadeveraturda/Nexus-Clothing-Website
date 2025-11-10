<?php
session_start();
require '../includes/database.php'; 
include '../includes/header.php';

$error = "";

// Show errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        if (!$stmt) {
            die("Database error: " . $conn->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION["user"] = $email;
                $_SESSION['user_id'] = $user['id'];

                // ✅ Redirect admin to dashboard
                if ($email === 'admin@gmail.com') {
                    header("Location: ../admin/dashboard.php");
                } else {
                    header("Location: ../pages/index.php");
                }
                exit();
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "User not found.";
        }

        $stmt->close();
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Login - NEXUS</title>
    <link rel="stylesheet" href="../assets/css/login.css">
</head>

<body>

    <div class="container">
        <form method="post">
            <h2>Login</h2>

            <?php if (!empty($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>

            <label for="email">Gmail:</label>
            <input type="email" name="email" id="email" required pattern=".+@gmail\.com" placeholder="E-mail">

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required placeholder="Password">

            <button type="submit">Login</button>
            <a href="signup.php">Don't have an account? Signup here.</a>
        </form>
    </div>

</body>

</html>