<?php
require_once("includes/config.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
    if(mysqli_query($conn, $query)) {
        header("Location: login.php");
    } else {
        $error = "Registration failed";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up - YouTube Clone</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="auth-container">
        <h2>Sign Up</h2>
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>