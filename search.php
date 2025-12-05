<?php
require_once("includes/config.php");
require_once("includes/functions.php");
session_start();

// Check if search query is provided
if (!isset($_GET['q']) || empty($_GET['q'])) {
    header("Location: index.php");
    exit();
}

$search_query = $_GET['q'];

// Fetch videos matching the search query
$query = "SELECT v.*, u.username, u.profile_image 
          FROM videos v 
          LEFT JOIN users u ON v.user_id = u.user_id 
          WHERE v.title LIKE ? OR v.description LIKE ?
          ORDER BY v.upload_date DESC";
$stmt = mysqli_prepare($conn, $query);
$search_param = "%" . $search_query . "%";
mysqli_stmt_bind_param($stmt, "ss", $search_param, $search_param);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Check if there are any results
$has_results = mysqli_num_rows($result) > 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search: <?php echo htmlspecialchars($search_query); ?> - YouTube Clone</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dark-theme.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include('includes/header.php'); ?>

    <div class="content-area">
        <div class="search-results-header">
            <h2>Search results for "<?php echo htmlspecialchars($search_query); ?>"</h2>
        </div>
        
        <div class="video-grid">
            <?php if ($has_results): ?>
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
                    <i class="fas fa-search"></i>
                    <h2>No results found</h2>
                    <p>Try different keywords or check your spelling</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>