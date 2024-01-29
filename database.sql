-- @block phpmyadmin stuff
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+05:30";

-- --------------------------------------------------------

-- @block Database: whisper_db

DROP DATABASE IF EXISTS whisper_db;

CREATE DATABASE `whisper_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `whisper_db`;

-- --------------------------------------------------------

-- @block Table: `users`
CREATE TABLE `users` (
    id INT AUTO_INCREMENT,
    user_id VARCHAR(50) UNIQUE NOT NULL,
    `email` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `password_hash` VARCHAR(60) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='users';

-- --------------------------------------------------------

-- @block Table: posts
CREATE TABLE `posts` (
    id INT AUTO_INCREMENT,
    user_id VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_bin,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    votes INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='posts';

-- --------------------------------------------------------