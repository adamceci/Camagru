-- Database creation
CREATE DATABASE IF NOT EXISTS db_camagru;
USE db_camagru;

-- Users table creation
CREATE TABLE IF NOT EXISTS users (
    `user_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    `login` VARCHAR(26) NOT NULL UNIQUE,
    passwd VARCHAR(255) NOT NULL,
    profile_pic VARCHAR(255) NOT NULL
);

-- Posts table creation
CREATE TABLE IF NOT EXISTS posts (
    post_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `image` VARCHAR(255) NOT NULL,
    creation_date DATETIME NOT NULL
)