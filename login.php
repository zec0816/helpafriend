<?php

include 'connection.php';

$username = $_GET['username'];
$password = $_GET['password'];

$cek = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
$msql = mysqli_query($connection, $cek);
$result = mysqli_num_rows($msql);

if (!empty($username) && !empty($password)){

    if($result == 0){
        echo "0";
    }else{
        echo "Welcome";
    }
}else{
    echo "Please fill in your info";
}