<?php
require_once("includes/config.php");
require_once("includes/functions.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    die(json_encode(['success' => false, 'error' => 'Not logged in']));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $response = ['success' => false];
    
    try {
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $user_id = $_SESSION['user_id'];
        
        if (isset($_FILES['video']) && $_FILES['video']['error'] == 0) {
            $video_tmp = $_FILES['video']['tmp_name'];
            $video_name = uniqid() . '_' . time() . '_' . $_FILES['video']['name'];
            $video_path = "uploads/videos/" . $video_name;
            
            // Handle thumbnail
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
                    $video_id = mysqli_insert_id($conn);
                    $response = [
                        'success' => true,
                        'redirect' => "watch.php?id=" . $video_id
                    ];
                } else {
                    throw new Exception("Database error: " . mysqli_error($conn));
                }
            } else {
                throw new Exception("Failed to upload video file");
            }
        } else {
            throw new Exception("Please select a video file");
        }
    } catch (Exception $e) {
        $response = [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
    
    echo json_encode($response);
    exit;
}