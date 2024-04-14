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
    UNIQUE KEY `unique_username` (username),
    CONSTRAINT `username_not_anonymous` CHECK (username != 'Anonymous')
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create Table: Keys
CREATE TABLE `keys` (
    id INT UNSIGNED AUTO_INCREMENT,
    user_id CHAR(16) NOT NULL,
    key_pair_id CHAR(16) NOT NULL,
    public_key_jwk VARCHAR(2048) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY `unique_user_id` (user_id),
    UNIQUE KEY `unique_key_pair_id` (key_pair_id),
    CONSTRAINT `fk_keys_users` FOREIGN KEY (user_id) REFERENCES `users` (user_id) ON DELETE CASCADE
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
    anon_post BOOLEAN NOT NULL DEFAULT FALSE,
    post_text TEXT NOT NULL,
    media_file_id CHAR(16),
    media_file_ext VARCHAR(5),
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    expire_at DATETIME,
    vote_count INT NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    UNIQUE KEY `unique_post_id` (post_id),
    UNIQUE KEY `unique_media_file_id` (media_file_id),
    CONSTRAINT `fk_posts_users` FOREIGN KEY (user_id) REFERENCES `users` (user_id) ON DELETE CASCADE,
    CONSTRAINT `check_content_media`  CHECK (post_text != '' OR (media_file_id IS NOT NULL AND media_file_ext IS NOT NULL)),
    CONSTRAINT `check_media` CHECK ((media_file_id IS NOT NULL AND media_file_ext IS NOT NULL) OR (media_file_id IS NULL AND media_file_ext IS NULL))
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
    encrypted_message VARCHAR(2048) NOT NULL,
    initialization_vector VARCHAR(100) NOT NULL,
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

INSERT INTO `users` (`user_id`, `username`, `password_hash`) VALUES
('Alphatester2----', 'Alphatester2', '$2y$10$2EQhA1F5zL49jWxCOz4ZpOHLUaGg.H99nEkoOdA/wzPERFRoxTxZa');

INSERT INTO `users` (`user_id`, `username`, `password_hash`) VALUES
('Alphatester3----', 'Alphatester3', '$2y$10$2EQhA1F5zL49jWxCOz4ZpOHLUaGg.H99nEkoOdA/wzPERFRoxTxZa');

INSERT INTO `users` (`user_id`, `username`, `password_hash`) VALUES
('Alphatester4----', 'Alphatester4', '$2y$10$2EQhA1F5zL49jWxCOz4ZpOHLUaGg.H99nEkoOdA/wzPERFRoxTxZa');

INSERT INTO `users` (`user_id`, `username`, `password_hash`) VALUES
('Alphatester5----', 'Alphatester5', '$2y$10$2EQhA1F5zL49jWxCOz4ZpOHLUaGg.H99nEkoOdA/wzPERFRoxTxZa');

INSERT INTO `users` (`user_id`, `username`, `password_hash`) VALUES
('Alphatester6----', 'Alphatester6', '$2y$10$2EQhA1F5zL49jWxCOz4ZpOHLUaGg.H99nEkoOdA/wzPERFRoxTxZa');

INSERT INTO `users` (`user_id`, `username`, `password_hash`) VALUES
('Alphatester7----', 'Alphatester7', '$2y$10$2EQhA1F5zL49jWxCOz4ZpOHLUaGg.H99nEkoOdA/wzPERFRoxTxZa');

INSERT INTO `users` (`user_id`, `username`, `password_hash`) VALUES
('Alphatester8----', 'Alphatester8', '$2y$10$2EQhA1F5zL49jWxCOz4ZpOHLUaGg.H99nEkoOdA/wzPERFRoxTxZa');

INSERT INTO `users` (`user_id`, `username`, `password_hash`) VALUES
('Alphatester9----', 'Alphatester9', '$2y$10$2EQhA1F5zL49jWxCOz4ZpOHLUaGg.H99nEkoOdA/wzPERFRoxTxZa');

-- Insert test records into the posts table
INSERT INTO `posts` (`post_id`, `user_id`, `post_text`, `expire_at`)
VALUES ('Alphatester0---0', 'Alphatester0----', 'Test 1 Post by Alphatester0', DATE_ADD(NOW(), INTERVAL 1 MINUTE));

INSERT INTO `posts` (`post_id`, `user_id`, `post_text`, `expire_at`)
VALUES ('Alphatester1---0', 'Alphatester1----', 'Test 1 Post by Alphatester1', DATE_ADD(NOW(), INTERVAL 1 MINUTE));

INSERT INTO `followers` (`follower_id`, `followee_id`) VALUES
('Alphatester1----', 'Alphatester0----');

INSERT INTO `followers` (`follower_id`, `followee_id`) VALUES
('Alphatester3----', 'Alphatester0----');

INSERT INTO `followers` (`follower_id`, `followee_id`) VALUES
('Alphatester5----', 'Alphatester0----');

INSERT INTO `followers` (`follower_id`, `followee_id`) VALUES
('Alphatester7----', 'Alphatester0----');

INSERT INTO `followers` (`follower_id`, `followee_id`) VALUES
('Alphatester9----', 'Alphatester0----');

INSERT INTO `followers` (`follower_id`, `followee_id`) VALUES
('Alphatester0----', 'Alphatester1----');

INSERT INTO `followers` (`follower_id`, `followee_id`) VALUES
('Alphatester0----', 'Alphatester2----');

INSERT INTO `followers` (`follower_id`, `followee_id`) VALUES
('Alphatester0----', 'Alphatester3----');

INSERT INTO `followers` (`follower_id`, `followee_id`) VALUES
('Alphatester0----', 'Alphatester4----');

INSERT INTO `followers` (`follower_id`, `followee_id`) VALUES
('Alphatester0----', 'Alphatester5----');

INSERT INTO `followers` (`follower_id`, `followee_id`) VALUES
('Alphatester0----', 'Alphatester6----');

INSERT INTO `followers` (`follower_id`, `followee_id`) VALUES
('Alphatester0----', 'Alphatester7----');

INSERT INTO `followers` (`follower_id`, `followee_id`) VALUES
('Alphatester0----', 'Alphatester8----');

INSERT INTO `followers` (`follower_id`, `followee_id`) VALUES
('Alphatester0----', 'Alphatester9----');

