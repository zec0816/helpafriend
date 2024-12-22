<?php
$hostName = "localhost";
$userName = "root";
$password = "";
$dbName = "helpafriend";

$connection = mysqli_connect($hostName, $userName, $password, $dbName);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_location = mysqli_real_escape_string($connection, $_POST['id_location']);
    $status = mysqli_real_escape_string($connection, $_POST['status']);
    $username = mysqli_real_escape_string($connection, $_POST['username']);

    // Retrieve the user ID based on the username
    $userCheckQuery = "SELECT id_user FROM user WHERE username = '$username'";
    $userResult = mysqli_query($connection, $userCheckQuery);
    $user = mysqli_fetch_assoc($userResult);

    if ($user) {
        $volunteer_id = $user['id_user'];

        // Update the status and set the volunteer_id
        $updateQuery = "UPDATE locations SET status = '$status', accepted_by = '$volunteer_id' WHERE id_location = '$id_location'";
        
        if (mysqli_query($connection, $updateQuery)) {
            echo "Request status updated successfully";
        } else {
            echo "Error: " . mysqli_error($connection);
        }
    } else {
        echo "User not found";
    }
}
?>
