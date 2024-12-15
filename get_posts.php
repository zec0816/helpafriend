<?php
$hostName = "localhost";
$userName = "root";
$password = "";
$dbName = "helpafriend";

$connection = mysqli_connect($hostName, $userName, $password, $dbName);
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

//changes 
$query = "SELECT forum.id_post, forum.title, forum.content, forum.created_at, user.username
FROM forum
INNER JOIN user ON forum.id_user = user.id_user
ORDER BY forum.created_at DESC;
"; //changes 
$result = mysqli_query($connection, $query);

$posts = array();
while ($row = mysqli_fetch_assoc($result)) {
    $posts[] = $row;
}

echo json_encode($posts);
?>
