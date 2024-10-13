<?php 
include 'connection.php';

$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];
$role = $_POST['role']; 

$queryRegister = "SELECT * FROM user WHERE username = '".$username."'";
$msql = mysqli_query($connection, $queryRegister);
$result = mysqli_num_rows($msql);

if (!empty($username) && !empty($password) && !empty($email) && !empty($role)) {
    if($result == 0) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $regis = "INSERT INTO user (username, password, email, role)
        VALUES ('$username', '$hashedPassword', '$email', '$role')";

        $msqlRegis = mysqli_query($connection, $regis);

        echo "Successfully registered";
    } else {
        echo "Username is already in use";
    }
} else {
    echo "Please fill in all your information";
}
