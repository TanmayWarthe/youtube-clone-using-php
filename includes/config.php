<?php
$db_host = "localhost";  // or try "127.0.0.1"
$db_user = "root";
$db_pass = "";          // default XAMPP password is blank
$db_name = "youtube_clone";

try {
    $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
    if (!$conn) {
        throw new Exception(mysqli_connect_error());
    }
} catch (Exception $e) {
    die("Connection failed: " . $e->getMessage());
}
?>