<?php
session_start();
include("../includes/database.php");
include '../includes/header.php';
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];

    if (empty($name) || empty($surname)) {
        $error = "Name and surname are required.";
    } elseif (!preg_match('/@gmail\.com$/', $email)) {
        $error = "Only Gmail addresses are allowed.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = "Email already registered.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (email, password, name, surname) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $email, $hash, $name, $surname);
            $stmt->execute();
            
            // Get the user ID
            $user_id = $conn->insert_id;
            
            $_SESSION['user'] = $email;
            $_SESSION['user_id'] = $user_id;
            header("Location: index.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Signup - NEXUS</title>
    <link rel="stylesheet" href="../assets/css/login.css">
</head>

<body>
    <div class="container">
        <h2>Sign up</h2>

        <?php if (!empty($error)): ?>
        <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="post">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required placeholder="First name">
            <label for="surname">Surname:</label>
            <input type="text" name="surname" id="surname" required placeholder="Surname">
            <label for="email">Gmail:</label>
            <input type="email" name="email" id="email" required pattern=".+@gmail\.com" placeholder="E-mail">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required placeholder="Password">

            <button type="submit">Sign up</button>
        </form>

        <a href="login.php">Already have an account? Login here.</a>
    </div>
</body>

</html>

