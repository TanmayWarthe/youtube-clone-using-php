<?php
require_once("includes/config.php");
require_once("includes/functions.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$user = mysqli_stmt_get_result($stmt)->fetch_assoc();

// Fetch user's videos
$videos_query = "SELECT * FROM videos WHERE user_id = ? ORDER BY upload_date DESC";
$stmt = mysqli_prepare($conn, $videos_query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$videos = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Channel - YouTube Clone</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dark-theme.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include('includes/header.php'); ?>

    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-info">
                <img src="<?php echo $user['profile_image'] ?? 'assets/images/default-avatar.png'; ?>" alt="Profile">
                <div class="profile-details">
                    <h1><?php echo htmlspecialchars($user['username']); ?></h1>
                    <p><?php echo number_format($user['subscribers'] ?? 0); ?> subscribers</p>
                </div>
            </div>
            <a href="settings.php" class="edit-profile-btn">
                <i class="fas fa-edit"></i> Edit Profile
            </a>
        </div>

        <div class="profile-content">
            <h2>Your Videos</h2>
            <div class="video-grid">
                <?php while($video = mysqli_fetch_assoc($videos)): ?>
                    <div class="video-card">
                        <a href="watch.php?id=<?php echo $video['id']; ?>">
                            <div class="thumbnail">
                                <img src="<?php echo htmlspecialchars($video['thumbnail']); ?>" alt="<?php echo htmlspecialchars($video['title']); ?>">
                                <span class="duration"><?php echo htmlspecialchars($video['duration']); ?></span>
                            </div>
                            <div class="video-info">
                                <div class="video-details">
                                    <h3><?php echo htmlspecialchars($video['title']); ?></h3>
                                    <div class="video-meta">
                                        <span><?php echo number_format($video['views']); ?> views</span>
                                        <span>•</span>
                                        <span><?php echo time_elapsed_string($video['upload_date']); ?></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</body>
</html>