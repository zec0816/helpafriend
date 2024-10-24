<?php

$hostName = "localhost";
$userName = "root";
$password = "";
$dbName = "helpafriend";

$connection = mysqli_connect($hostName, $userName, $password);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "CREATE DATABASE IF NOT EXISTS $dbName";
if (!mysqli_query($connection, $sql)) {
    die("Error creating database: " . mysqli_error($connection));
}

mysqli_select_db($connection, $dbName);

$tableSql = "CREATE TABLE IF NOT EXISTS user (
    id_user INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    email VARCHAR(300) NOT NULL UNIQUE,
    role ENUM('volunteer', 'OKU') NOT NULL
)";

if (!mysqli_query($connection, $tableSql)) {
    die("Error creating table: " . mysqli_error($connection));
}

?>

//testing