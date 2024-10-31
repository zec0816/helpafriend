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
    $username = mysqli_real_escape_string($connection, $_POST['username']); // Get username from POST request
    $title = mysqli_real_escape_string($connection, $_POST['title']);
    $content = mysqli_real_escape_string($connection, $_POST['content']);

    // Retrieve the user ID based on the username
    $userCheckQuery = "SELECT id_user FROM user WHERE username = '$username'";
    $userResult = mysqli_query($connection, $userCheckQuery);
    $user = mysqli_fetch_assoc($userResult);

    if ($user) {
        $id_user = $user['id_user']; // Get the user ID

        $sql = "INSERT INTO forum (id_user, title, content) VALUES ('$id_user', '$title', '$content')";
        
        if (mysqli_query($connection, $sql)) {
            echo "Post submitted successfully";
        } else {
            echo "Error: " . mysqli_error($connection);
        }
    } else {
        echo "User not found";
    }
}
?>
