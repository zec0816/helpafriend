<?php
include 'connection.php';

$username = $_GET['username'];
$password = $_GET['password'];

$cek = "SELECT * FROM user WHERE username = '$username'";
$msql = mysqli_query($connection, $cek);
$user = mysqli_fetch_assoc($msql);

if (!empty($username) && !empty($password)){
    if ($user) {
        if (password_verify($password, $user['password'])) {
            echo "Welcome";
        } else {
            echo "Invalid password";
        }
    } else {
        echo "0"; 
    }
} else {
    echo "Please fill in your info";
}

//testing