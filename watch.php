<?php
require_once("includes/config.php");
require_once("includes/functions.php");
session_start();

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$video_id = $_GET['id'];

// Fetch video details
$query = "SELECT v.*, u.username 
          FROM videos v 
          LEFT JOIN users u ON v.user_id = u.user_id 
          WHERE v.id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $video_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    header("Location: index.php");
    exit();
}

$video = mysqli_fetch_assoc($result);

// Update view count
$update_views = "UPDATE videos SET views = views + 1 WHERE id = ?";
$stmt = mysqli_prepare($conn, $update_views);
mysqli_stmt_bind_param($stmt, "i", $video_id);
mysqli_stmt_execute($stmt);

// Fetch related videos
$related_query = "SELECT v.*, u.username 
                 FROM videos v 
                 LEFT JOIN users u ON v.user_id = u.user_id 
                 WHERE v.id != ? 
                 ORDER BY RAND() 
                 LIMIT 10";
$stmt = mysqli_prepare($conn, $related_query);
mysqli_stmt_bind_param($stmt, "i", $video_id);
mysqli_stmt_execute($stmt);
$related_videos = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($video['title']); ?> - YouTube Clone</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dark-theme.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .watch-container {
            display: flex;
            gap: 24px;
            padding: 20px;
            margin-top: 20px;
        }
        .main-content {
            flex: 1;
        }
        .video-player {
            width: 100%;
            aspect-ratio: 16/9;
            background: #000;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 16px;
        }
        .video-player video {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .video-title {
            font-size: 20px;
            font-weight: 500;
            margin-bottom: 12px;
            line-height: 1.4;
        }
        .video-description {
            font-size: 13px;
            color: var(--text-secondary);
            white-space: pre-wrap;
            line-height: 1.5;
        }
        .related-videos {
            width: 350px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .related-video-card {
            display: flex;
            gap: 8px;
        }
        .related-video-card .thumbnail {
            width: 168px;
            aspect-ratio: 16/9;
            border-radius: 8px;
            overflow: hidden;
        }
        .related-video-card .thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .related-video-card .video-details {
            flex: 1;
        }
        .related-video-card h3 {
            font-size: 14px;
            margin-bottom: 4px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .related-video-card .channel-name {
            color: var(--text-secondary);
            font-size: 12px;
            display: block;
            margin-bottom: 4px;
        }
        .related-video-card .video-meta {
            color: var(--text-secondary);
            font-size: 12px;
        }
        @media (max-width: 1000px) {
            .watch-container {
                flex-direction: column;
            }
            .related-videos {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <?php include('includes/header.php'); ?>

    <div class="watch-container">
        <div class="main-content">
            <div class="video-player">
                <video src="<?php echo $video['video_path']; ?>" controls width="100%" autoplay></video>
            </div>
            
            <h1 class="video-title"><?php echo htmlspecialchars($video['title']); ?></h1>
            
            <div class="video-description">
                <?php echo nl2br(htmlspecialchars($video['description'] ?? '')); ?>
            </div>
        </div>
        
        <div class="related-videos">
            <h3>Related Videos</h3>
            <?php while($related = mysqli_fetch_assoc($related_videos)): ?>
                <div class="related-video-card">
                    <a href="watch.php?id=<?php echo $related['id']; ?>">
                        <div class="thumbnail">
                            <img src="<?php echo htmlspecialchars($related['thumbnail'] ?? 'assets/images/default-thumbnail.jpg'); ?>" alt="<?php echo htmlspecialchars($related['title']); ?>">
                        </div>
                    </a>
                    <div class="video-details">
                        <a href="watch.php?id=<?php echo $related['id']; ?>" style="text-decoration: none; color: inherit;">
                            <h3><?php echo htmlspecialchars($related['title']); ?></h3>
                        </a>
                        <span class="channel-name"><?php echo htmlspecialchars($related['username']); ?></span>
                        <div class="video-meta">
                            <?php echo isset($related['views']) ? number_format($related['views']) . ' views' : '0 views'; ?> • 
                            <?php echo time_elapsed_string($related['upload_date']); ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>