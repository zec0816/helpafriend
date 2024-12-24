<?php
require_once('connection.php');

// Get the username from the POST request
$username = $_POST['username'];

// Get the user ID for the given username
$userQuery = "SELECT id_user FROM user WHERE username = '$username'";
$userResult = mysqli_query($connection, $userQuery);
$user = mysqli_fetch_assoc($userResult);

if ($user) {
    $id_user = $user['id_user'];

    // Get all accepted requests for the user
    $query = "SELECT id_location, latitude, longitude FROM locations WHERE id_user = $id_user AND status = 'accepted'";
    $result = mysqli_query($connection, $query);

    $requests = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $requests[] = $row;
        }
    }

    // Return the accepted requests as JSON
    header('Content-Type: application/json');
    echo json_encode($requests);
} else {
    echo json_encode(["success" => false, "message" => "User not found"]);
}

mysqli_close($connection);
?>
