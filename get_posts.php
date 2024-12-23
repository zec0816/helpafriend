<?php
$hostName = "localhost";
$userName = "root";
$password = "";
$dbName = "helpafriend";

$connection = mysqli_connect($hostName, $userName, $password, $dbName);
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$query = "SELECT title, content, created_at FROM forum ORDER BY created_at DESC";
$result = mysqli_query($connection, $query);

$posts = array();
while ($row = mysqli_fetch_assoc($result)) {
    $posts[] = $row;
}

echo json_encode($posts);
?>
