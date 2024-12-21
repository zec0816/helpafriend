<?php
require 'connection.php';

$id_location = $_POST['id_location'];
$status = $_POST['status'];

// Update the status of the location
$sql = "UPDATE locations SET status = '$status' WHERE id_location = $id_location";
if (mysqli_query($connection, $sql)) {
    echo json_encode(["success" => true, "message" => "Status updated successfully"]);
} else {
    echo json_encode(["success" => false, "message" => mysqli_error($connection)]);
}
?>
