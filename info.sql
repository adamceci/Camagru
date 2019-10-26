-- Database creation
CREATE DATABASE IF NOT EXISTS db_camagru;
USE db_camagru;

-- Get errors when NOT NULL is not respected
SET sql_mode = "strict_all_tables";

-- Users table creation
CREATE TABLE IF NOT EXISTS users (
    `user_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    `login` VARCHAR(26) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    profile_pic VARCHAR(255) NOT NULL,
    verif_hash CHAR(32) NOT NULL,
    active TINYINT(1) NOT NULL DEFAULT 0,
    `date_of_creation` DATETIME NOT NULL default NOW(),
    `notification_email` VARCHAR(255) NOT NULL UNIQUE
);

-- Posts table creation
CREATE TABLE IF NOT EXISTS posts (
    post_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `image` VARCHAR(255) NOT NULL,
    creation_date DATETIME NOT NULL
);

CREATE TABLE IF NOT EXISTS filters (
    `filter_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `image_src` VARCHAR(255) NOT NULL
)
