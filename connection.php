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

$tableSqlLocations = "CREATE TABLE IF NOT EXISTS locations (
    id_location INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_user INT(11) NOT NULL,
    latitude DOUBLE NOT NULL,
    longitude DOUBLE NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE CASCADE
)";

if (!mysqli_query($connection, $tableSqlLocations)) {
    die("Error creating locations table: " . mysqli_error($connection));
}

?>