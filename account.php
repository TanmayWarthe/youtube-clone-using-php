<?php
require_once("includes/config.php");
require_once("includes/functions.php");
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user data
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// Fetch user stats
$videos_query = "SELECT COUNT(*) as video_count FROM videos WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $videos_query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$video_count = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['video_count'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Account - YouTube Clone</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include('includes/header.php'); ?>

    <div class="account-container">
        <div class="account-header">
            <div class="account-icon">
                <i class="fas fa-user"></i>
            </div>
            <div class="account-info">
                <h1 class="account-name"><?php echo htmlspecialchars($user['username']); ?></h1>
                <div class="account-email"><?php echo htmlspecialchars($user['email']); ?></div>
            </div>
        </div>

        <div class="account-stats">
            <div class="stat-item">
                <div class="stat-value"><?php echo $video_count; ?></div>
                <div class="stat-label">Videos</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?php echo number_format($user['total_views'] ?? 0); ?></div>
                <div class="stat-label">Total Views</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">
                    <?php 
                        $join_date = isset($user['join_date']) ? $user['join_date'] : date('Y-m-d H:i:s');
                        echo date('M Y', strtotime($join_date));
                    ?>
                </div>
                <div class="stat-label">Joined</div>
            </div>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>