<?php

$hostName = "localhost";
$userName = "root";
$password = "";
$dbName = "helpafriend";

$connection = mysqli_connect($hostName, $userName, $password);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create the database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbName";
if (!mysqli_query($connection, $sql)) {
    die("Error creating database: " . mysqli_error($connection));
}

mysqli_select_db($connection, $dbName);

// Create user table
$tableSqlUser = "CREATE TABLE IF NOT EXISTS user (
    id_user INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    email VARCHAR(300) NOT NULL UNIQUE,
    role ENUM('volunteer', 'OKU') NOT NULL
)";

if (!mysqli_query($connection, $tableSqlUser)) {
    die("Error creating user table: " . mysqli_error($connection));
}

// Create forum table
$tableSqlForum = "CREATE TABLE IF NOT EXISTS forum (
    id_post INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_user INT(11) NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE CASCADE
)";

if (!mysqli_query($connection, $tableSqlForum)) {
    die("Error creating forum table: " . mysqli_error($connection));
}

// Create locations table
$tableSqlLocations = "CREATE TABLE IF NOT EXISTS locations (
    id_location INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_user INT(11) NOT NULL,
    latitude DOUBLE NOT NULL,
    longitude DOUBLE NOT NULL,
    status ENUM('pending', 'accepted', 'cancelled') DEFAULT 'pending',
    accepted_by INT(11) DEFAULT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE CASCADE,
    FOREIGN KEY (accepted_by) REFERENCES user(id_user) ON DELETE SET NULL
)";

if (!mysqli_query($connection, $tableSqlLocations)) {
    die("Error creating locations table: " . mysqli_error($connection));
}

// Create leaderboard table
$tableSqlLeaderboard = "CREATE TABLE IF NOT EXISTS leaderboard (
    id_user INT(11) PRIMARY KEY,
    num_helped INT(11) DEFAULT 0,
    FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE CASCADE
)";

if (!mysqli_query($connection, $tableSqlLeaderboard)) {
    die("Error creating leaderboard table: " . mysqli_error($connection));
}

// Create comment table
$tableSqlComment = "CREATE TABLE IF NOT EXISTS comment (
    id_comment INT(11) AUTO_INCREMENT PRIMARY KEY,        
    id_post INT NOT NULL,                                
    id_user INT NOT NULL,                                  
    comment TEXT NOT NULL,                                  
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,         
    FOREIGN KEY (id_post) REFERENCES forum(id_post) ON DELETE CASCADE, 
    FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE CASCADE   
)";

if (!mysqli_query($connection, $tableSqlComment)) {
    die("Error creating comment table: " . mysqli_error($connection));
}

// Create likes table
$tableSqlLike = "CREATE TABLE IF NOT EXISTS likes (
    id_like INT(11) AUTO_INCREMENT PRIMARY KEY,           
    id_post INT NOT NULL,                                 
    id_user INT NOT NULL,                                 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,       
    FOREIGN KEY (id_post) REFERENCES forum(id_post) ON DELETE CASCADE, 
    FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE CASCADE   
)";

if (!mysqli_query($connection, $tableSqlLike)) {
    die("Error creating likes table: " . mysqli_error($connection));
}

?>