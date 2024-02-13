-- @block Drop Database: whisper_db
DROP DATABASE IF EXISTS whisper_db;


-- @block Create Database: whisper_db
CREATE DATABASE `whisper_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;


-- @block Select Database: whisper_db
USE `whisper_db`;


-- @block Create Table: users
CREATE TABLE `users` (
    id INT UNSIGNED AUTO_INCREMENT,
    user_id VARCHAR(50) UNIQUE NOT NULL,
    `email` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `password_hash` VARCHAR(60) NOT NULL,
    PRIMARY KEY (id)
);


-- @block Create Table: posts
CREATE TABLE `posts` (
    id INT UNSIGNED AUTO_INCREMENT,
    user_id VARCHAR(50) NOT NULL,
    content VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    votes INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);


-- @block Create Table: votes
CREATE TABLE `votes` (
    user_id VARCHAR(50),
    post_id INT UNSIGNED,
    vote_type ENUM('upvote', 'downvote') NOT NULL,
    PRIMARY KEY (`user_id`, `post_id`),
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (post_id) REFERENCES posts(id)
);
