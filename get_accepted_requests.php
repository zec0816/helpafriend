<?php
require_once('connection.php');

$username = $_POST['username'];

$userQuery = "SELECT id_user FROM user WHERE username = '$username'";
$userResult = mysqli_query($connection, $userQuery);
$user = mysqli_fetch_assoc($userResult);

if ($user) {
    $id_user = $user['id_user'];

    // Get all accepted requests for the user, including volunteer details
    $query = "
        SELECT 
            l.id_location, 
            l.latitude, 
            l.longitude, 
            u.username AS volunteer_name 
        FROM 
            locations l
        JOIN 
            user u 
        ON 
            l.accepted_by = u.id_user
        WHERE 
            l.id_user = $id_user 
            AND l.status = 'accepted'
    ";
    $result = mysqli_query($connection, $query);

    $requests = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $requests[] = $row;
        }
    }

    header('Content-Type: application/json');
    echo json_encode($requests);
} else {
    echo json_encode(["success" => false, "message" => "User not found"]);
}

mysqli_close($connection);
?>