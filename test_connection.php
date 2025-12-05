<?php
require_once("includes/config.php");
if($conn) {
    echo "Database connected successfully!";
} else {
    echo "Connection failed!";
}
?>