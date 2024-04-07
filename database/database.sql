-- Enable event scheduler
-- SET GLOBAL event_scheduler = ON;

-- Drop Database: whisper_db
DROP DATABASE IF EXISTS whisper_db;

-- Create Database: whisper_db
CREATE DATABASE `whisper_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Select Database: whisper_db
USE `whisper_db`;

-- Create Table: users
CREATE TABLE `users` (
    id INT UNSIGNED AUTO_INCREMENT,
    user_id CHAR(16) NOT NULL,
    username VARCHAR(16) NOT NULL,
    password_hash CHAR(60) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY `unique_user_id` (user_id),
    UNIQUE KEY `unique_username` (username)
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create Table: profile_pictures
CREATE TABLE `profile_pictures` (
    id INT UNSIGNED AUTO_INCREMENT,
    user_id CHAR(16) NOT NULL,
    profile_file_id CHAR(255) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY `unique_user_id` (user_id),
    UNIQUE KEY `unique_file_name` (profile_file_id),
    CONSTRAINT `fk_profile_pictures_users` FOREIGN KEY (user_id) REFERENCES `users` (user_id) ON DELETE CASCADE
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create Table: posts
CREATE TABLE `posts` (
    id BIGINT UNSIGNED AUTO_INCREMENT,
    post_id CHAR(16) NOT NULL,
    user_id CHAR(16) NOT NULL,
    content TEXT NOT NULL,
    media_file_id CHAR(16),
    media_file_ext VARCHAR(5),
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    expire_at DATETIME,
    vote_count INT NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    UNIQUE KEY `unique_post_id` (post_id),
    UNIQUE KEY `unique_media_file_id` (media_file_id),
    CONSTRAINT `fk_posts_users` FOREIGN KEY (user_id) REFERENCES `users` (user_id) ON DELETE CASCADE
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create Table: votes
CREATE TABLE `votes` (
    post_id CHAR(16) NOT NULL,
    user_id CHAR(16) NOT NULL,
    vote_type ENUM('upvote', 'downvote') NOT NULL,
    PRIMARY KEY (post_id, user_id),
    CONSTRAINT `fk_votes_posts` FOREIGN KEY (post_id) REFERENCES `posts` (post_id) ON DELETE CASCADE,
    CONSTRAINT `fk_votes_users` FOREIGN KEY (user_id) REFERENCES `users` (user_id) ON DELETE CASCADE
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create Table: messages
CREATE TABLE `messages` (
    id INT UNSIGNED AUTO_INCREMENT,
    sender_id CHAR(16) NOT NULL,
    receiver_id CHAR(16) NOT NULL,
    message TEXT NOT NULL,
    sent_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    CONSTRAINT `fk_messages_sender` FOREIGN KEY (sender_id) REFERENCES `users` (user_id) ON DELETE CASCADE,
    CONSTRAINT `fk_messages_receiver` FOREIGN KEY (receiver_id) REFERENCES `users` (user_id) ON DELETE CASCADE
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create Table: followers
CREATE TABLE `followers` (
    id INT UNSIGNED AUTO_INCREMENT,
    follower_id CHAR(16) NOT NULL,
    followee_id CHAR(16) NOT NULL,
    follow_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    CONSTRAINT `fk_followers_follower` FOREIGN KEY (follower_id) REFERENCES `users` (user_id) ON DELETE CASCADE,
    CONSTRAINT `fk_followers_followee` FOREIGN KEY (followee_id) REFERENCES `users` (user_id) ON DELETE CASCADE
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Insert test records into the users table
-- Note: password = 'test'
INSERT INTO `users` (`user_id`, `username`, `password_hash`) VALUES
('Alphatester0----', 'Alphatester0', '$2y$10$2EQhA1F5zL49jWxCOz4ZpOHLUaGg.H99nEkoOdA/wzPERFRoxTxZa');

INSERT INTO `users` (`user_id`, `username`, `password_hash`) VALUES
('Alphatester1----', 'Alphatester1', '$2y$10$2EQhA1F5zL49jWxCOz4ZpOHLUaGg.H99nEkoOdA/wzPERFRoxTxZa');

-- Insert test records into the posts table
-- INSERT INTO `posts` (`post_id`, `user_id`, `content`, `media_file_id`, `media_file_ext`, `expire_at`)
-- VALUES ('Alphatester1---0', 'Alphatester1----', 'Test 1 Post by Alphatester1', 'Alphatester1----', 'jpg', DATE_ADD(NOW(), INTERVAL 1 MINUTE));

