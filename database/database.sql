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
    email VARCHAR(100) NOT NULL,
    password_hash CHAR(60) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY `unique_user_id` (user_id),
    UNIQUE KEY `unique_email` (email)
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create Table: usernames
CREATE TABLE `usernames` (
    id INT UNSIGNED AUTO_INCREMENT,
    user_id CHAR(16) NOT NULL,
    username VARCHAR(16) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY `unique_user_id` (user_id),
    UNIQUE KEY `unique_username` (username),
    CONSTRAINT `fk_usernames_users` FOREIGN KEY (user_id) REFERENCES `users` (user_id) ON DELETE CASCADE
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create Table: profile_pictures
CREATE TABLE `profile_pictures` (
    id INT UNSIGNED AUTO_INCREMENT,
    user_id CHAR(16) NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY `unique_user_id` (user_id),
    UNIQUE KEY `unique_file_name` (file_name),
    CONSTRAINT `fk_profile_pictures_users` FOREIGN KEY (user_id) REFERENCES `users` (user_id) ON DELETE CASCADE
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create Table: posts
CREATE TABLE `posts` (
    id BIGINT UNSIGNED AUTO_INCREMENT,
    post_id CHAR(16) NOT NULL,
    user_id CHAR(16) NOT NULL,
    file_name VARCHAR(255),
    content TEXT NOT NULL,
    votes INT NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    UNIQUE KEY `unique_post_id` (post_id),
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


-- Insert test records into the users table
-- Note: password = 'test'
INSERT INTO `users` (`user_id`, `email`, `password_hash`) VALUES
('Alphatester0----', 'Alphatester0@test.com', '$2y$10$2EQhA1F5zL49jWxCOz4ZpOHLUaGg.H99nEkoOdA/wzPERFRoxTxZa');

INSERT INTO `users` (`user_id`, `email`, `password_hash`) VALUES
('Alphatester1----', 'Alphatester1@test.com', '$2y$10$2EQhA1F5zL49jWxCOz4ZpOHLUaGg.H99nEkoOdA/wzPERFRoxTxZa');

-- Insert test records into the usernames table
INSERT INTO `usernames` (user_id, username) VALUES ('Alphatester0----', 'Alphatester0');

INSERT INTO `usernames` (user_id, username) VALUES ('Alphatester1----', 'Alphatester1');

