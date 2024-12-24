<?php
require_once('connection.php');

$query = "
    SELECT u.username, l.num_helped 
    FROM leaderboard l
    JOIN user u ON l.id_user = u.id_user
    ORDER BY l.num_helped DESC";

$result = mysqli_query($connection, $query);

$leaderboard = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $leaderboard[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($leaderboard);

mysqli_close($connection);
?>
