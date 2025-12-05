CREATE DATABASE IF NOT EXISTS youtube_clone;
USE youtube_clone;

CREATE TABLE IF NOT EXISTS `users` (
    `user_id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL,
    `email` VARCHAR(100) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `profile_image` VARCHAR(255) DEFAULT 'assets/images/default-avatar.png',
    `subscribers` INT DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `videos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `video_path` VARCHAR(255) NOT NULL,
    `thumbnail` VARCHAR(255),
    `views` INT DEFAULT 0,
    `likes` INT DEFAULT 0,
    `dislikes` INT DEFAULT 0,
    `upload_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `user_id` INT,
    FOREIGN KEY (`user_id`) REFERENCES users(`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `comments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `video_id` INT,
    `user_id` INT,
    `comment` TEXT NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`video_id`) REFERENCES videos(`id`),
    FOREIGN KEY (`user_id`) REFERENCES users(`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;