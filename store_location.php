<?php
require 'connection.php';

$username = $_POST['username'];  
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];

$userCheckQuery = "SELECT id_user FROM user WHERE username = '$username'";
$userResult = mysqli_query($connection, $userCheckQuery);
$user = mysqli_fetch_assoc($userResult);

if ($user) {
    $id_user = $user['id_user'];

    // Insert or update the location for the user
    $sql = "INSERT INTO locations (id_user, latitude, longitude) VALUES ('$id_user', '$latitude', '$longitude') 
            ON DUPLICATE KEY UPDATE latitude='$latitude', longitude='$longitude'";

    if (mysqli_query($connection, $sql)) {
        echo json_encode(["success" => true, "message" => "Location stored successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => mysqli_error($connection)]);
    }
} else {
    echo json_encode(["success" => false, "message" => "User not found"]);
}
?>
