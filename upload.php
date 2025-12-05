<?php
require_once("includes/config.php");
require_once("includes/functions.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $user_id = $_SESSION['user_id'];
    
    // Handle video upload
    if (isset($_FILES['video']) && $_FILES['video']['error'] == 0) {
        $video_tmp = $_FILES['video']['tmp_name'];
        $video_name = uniqid() . '_' . time() . '_' . $_FILES['video']['name'];
        $video_path = "uploads/videos/" . $video_name;
        
        // Handle thumbnail upload
        $thumbnail_path = "uploads/thumbnails/default.jpg";
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
            $thumbnail_tmp = $_FILES['thumbnail']['tmp_name'];
            $thumbnail_name = uniqid() . '_' . time() . '_' . $_FILES['thumbnail']['name'];
            $thumbnail_path = "uploads/thumbnails/" . $thumbnail_name;
            move_uploaded_file($thumbnail_tmp, $thumbnail_path);
        }
        
        if (move_uploaded_file($video_tmp, $video_path)) {
            $query = "INSERT INTO videos (title, description, video_path, thumbnail, user_id) 
                      VALUES ('$title', '$description', '$video_path', '$thumbnail_path', '$user_id')";
            
            if (mysqli_query($conn, $query)) {
                $message = "Video uploaded successfully!";
                header("Location: watch.php?id=" . mysqli_insert_id($conn));
                exit();
            } else {
                $error = "Database error: " . mysqli_error($conn);
            }
        } else {
            $error = "Failed to upload video file.";
        }
    } else {
        $error = "Please select a video file.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Video - YouTube Clone</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dark-theme.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
</head>
<body>
    <?php include('includes/header.php'); ?>

    <div class="upload-container">
        <h2>Upload Video</h2>
        <?php if($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if($message): ?>
            <div class="success"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" required>
            </div>
            
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="4"></textarea>
            </div>
            
            <div class="form-group">
                <label>Video File</label>
                <input type="file" name="video" accept="video/*" required>
            </div>
            
            <div class="form-group">
                <label>Thumbnail (Optional)</label>
                <input type="file" name="thumbnail" accept="image/*">
            </div>
            
            <button type="submit">Upload Video</button>
        </form>
    </div>

    <!-- Add this after the form opening tag -->
    <div class="upload-progress">
        <div class="progress-bar"></div>
    </div>

    <!-- Add this before closing body tag -->
    <script src="assets/js/upload.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>