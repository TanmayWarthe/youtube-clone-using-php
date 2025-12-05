<?php
require_once("includes/config.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['login'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = $_POST['password'];
        
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $query);
        
        if($user = mysqli_fetch_assoc($result)) {
            if(password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                echo "<script>window.location.href='index.php';</script>";
            } else {
                echo "<script>alert('Invalid password!');</script>";
            }
        } else {
            
            echo "<script>alert('User not found!');</script>";
        }
    }
    else if(isset($_POST['signup'])) {
        $username = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        // Check if email already exists
        $check_email = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $check_email);
        
        if(mysqli_num_rows($result) > 0) {
            echo "<script>alert('Email already exists!');</script>";
        } else {
            $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
            if(mysqli_query($conn, $query)) {
                echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
            } else {
                echo "<script>alert('Registration failed: " . mysqli_error($conn) . "');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - YouTube Clone</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body>
    <h2>Welcome to YouTube Clone</h2>
    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form method="POST">
                <h1>Create Account</h1>
                <input type="text" name="name" placeholder="Name" required />
                <input type="email" name="email" placeholder="Email" required />
                <input type="password" name="password" placeholder="Password" required />
                <button type="submit" name="signup">Sign Up</button>
            </form>
        </div>
        <div class="form-container sign-in-container">
            <form method="POST">
                <h1>Sign in</h1>
                <input type="email" name="email" placeholder="Email" required />
                <input type="password" name="password" placeholder="Password" required />
                <a href="#">Forgot your password?</a>
                <button type="submit" name="login">Sign In</button>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome Back!</h1>
                    <p>To keep connected with us please login with your personal info</p>
                    <button class="ghost" id="signIn">Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Hello, Friend!</h1>
                    <p>Enter your personal details and start journey with us</p>
                    <button class="ghost" id="signUp">Sign Up</button>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/auth.js"></script>
</body>
</html>