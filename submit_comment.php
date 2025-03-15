<?php
$hostName = "localhost";
$userName = "root";
$password = "";
$dbName = "helpafriend";

$connection = mysqli_connect($hostName, $userName, $password, $dbName);

if (!$connection) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $comment = mysqli_real_escape_string($connection, $_POST['comment']);
    $id_post = mysqli_real_escape_string($connection, $_POST['id_post']); 

    $userCheckQuery = "SELECT id_user FROM user WHERE username = '$username'";
    $userResult = mysqli_query($connection, $userCheckQuery);
    $user = mysqli_fetch_assoc($userResult);

    if ($user) {
        $id_user = $user['id_user'];
        // Insert the comment
        $sql = "INSERT INTO comment (id_user, id_post, comment) VALUES ('$id_user', '$id_post', '$comment')";
        if (mysqli_query($connection, $sql)) {
            echo json_encode(['status' => 'success', 'message' => 'Comment submitted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => mysqli_error($connection)]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

mysqli_close($connection);
?>
