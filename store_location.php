<?php
require 'connection.php';

$id_user = $_POST['id_user'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];

$sql = "INSERT INTO locations (id_user, latitude, longitude) VALUES ('$id_user', '$latitude', '$longitude') 
        ON DUPLICATE KEY UPDATE latitude='$latitude', longitude='$longitude'";

if (mysqli_query($connection, $sql)) {
    echo json_encode(["success" => true, "message" => "Location stored successfully"]);
} else {
    echo json_encode(["success" => false, "message" => mysqli_error($connection)]);
}
?>
