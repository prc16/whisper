SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+05:30";


DROP DATABASE IF EXISTS whisper_db;

CREATE DATABASE IF NOT EXISTS `whisper_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `whisper_db`;


CREATE TABLE `users` (
    `uid` int(11) NOT NULL AUTO_INCREMENT,
    `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `password_hash` varchar(60) NOT NULL,
    PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='users';
