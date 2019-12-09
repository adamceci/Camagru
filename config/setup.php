<?php

require_once("database.php");

// $db = new PDO("mysql:host=localhost", $user, $password);
$db = new PDO("mysql:host=localhost", "root", "aceciora");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Database creation
$db->exec("CREATE DATABASE IF NOT EXISTS db_camagru");
$db->exec("USE db_camagru");

// Get errors when NOT NULL is not respected
$db->exec("SET sql_mode = 'strict_all_tables'");

// Users table creation
$db->exec(
    "CREATE TABLE IF NOT EXISTS users
    (
        `user_id` INT NOT NULL AUTO_INCREMENT,
        `email` VARCHAR(255) NOT NULL,
        `login` VARCHAR(26) NOT NULL,
        `password` VARCHAR(255) NOT NULL,
        `profile_pic` VARCHAR(255) NOT NULL,
        `verif_hash` CHAR(32) NOT NULL,
        `active` TINYINT(1) NOT NULL DEFAULT 0,
        `date_of_creation` DATETIME NOT NULL default NOW(),
        `notification_email` VARCHAR(255) NOT NULL,
        `notification_active` TINYINT(1) NOT NULL DEFAULT 1,
        PRIMARY KEY(`user_id`)
    );"
);

// Posts table creation
$db->exec(
    "CREATE TABLE IF NOT EXISTS posts
    (
        `post_id` INT NOT NULL AUTO_INCREMENT,
        `user_id` INT NOT NULL,
        `image` VARCHAR(255) NOT NULL,
        `creation_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `posted` TINYINT(1) DEFAULT 0,
        `posted_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`post_id`),
        FOREIGN KEY (`user_id`) REFERENCES users(`user_id`) ON DELETE CASCADE
    );"
);

// Comments table creation
$db->exec(
    "CREATE TABLE IF NOT EXISTS comments
    (
        `comment_id` INT NOT NULL AUTO_INCREMENT,
        `user_id` INT NOT NULL,
        `post_id` INT NOT NULL,
        `message` VARCHAR(255) NOT NULL,
        PRIMARY KEY (`comment_id`),
        FOREIGN KEY (`user_id`) REFERENCES users(`user_id`) ON DELETE CASCADE,
        FOREIGN KEY (`post_id`) REFERENCES posts(`post_id`) ON DELETE CASCADE
    );"
);

// Likes table creation
$db->exec(
    "CREATE TABLE IF NOT EXISTS likes
    (
        `like_id` INT NOT NULL AUTO_INCREMENT,
        `user_id` INT NOT NULL,
        `post_id` INT NOT NULL,
        `active` TINYINT(1) NOT NULL DEFAULT 0,
        PRIMARY KEY (`like_id`),
        FOREIGN KEY (`user_id`) REFERENCES users(`user_id`) ON DELETE CASCADE,
        FOREIGN KEY (`post_id`) REFERENCES posts(`post_id`) ON DELETE CASCADE
    );"
);

echo "database setup done";