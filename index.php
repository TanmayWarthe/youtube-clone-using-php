<?php
require_once("includes/config.php");
require_once("includes/functions.php");
session_start();

// Fetch all videos with user information
$query = "SELECT v.*, u.username, u.profile_image 
          FROM videos v 
          LEFT JOIN users u ON v.user_id = u.user_id 
          ORDER BY v.upload_date DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Check if there are any videos
$has_videos = mysqli_num_rows($result) > 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>YouTube Clone</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dark-theme.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include('includes/header.php'); ?>

    <div class="content-area">
        <div class="video-grid">
            <?php if ($has_videos): ?>
                <?php while($video = mysqli_fetch_assoc($result)): ?>
                    <div class="video-card">
                        <a href="watch.php?id=<?php echo htmlspecialchars($video['id']); ?>">
                            <div class="thumbnail">
                                <img src="<?php echo htmlspecialchars($video['thumbnail'] ?? 'assets/images/default-thumbnail.jpg'); ?>" 
                                     alt="<?php echo htmlspecialchars($video['title']); ?>">
                            </div>
                            <div class="video-info">
                                <h3><?php echo htmlspecialchars($video['title']); ?></h3>
                                <div class="channel-name"><?php echo htmlspecialchars($video['username']); ?></div>
                                <div class="video-meta">
                                    <?php echo isset($video['views']) ? number_format($video['views']) . ' views' : '0 views'; ?> • 
                                    <?php echo time_elapsed_string($video['upload_date']); ?>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-videos">
                    <i class="fas fa-video-slash"></i>
                    <h2>No videos available</h2>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <p>Be the first to <a href="upload.php">upload a video</a>!</p>
                    <?php else: ?>
                        <p>Please <a href="login.php">sign in</a> to upload videos.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>