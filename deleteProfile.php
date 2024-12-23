<?php
include 'connection.php';

$username = $_GET['username'];

if (empty($username)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Username is required'
    ]);
    exit();
}

// Check if user exists
$checkUser = "SELECT * FROM user WHERE username = '$username'";
$result = mysqli_query($connection, $checkUser);

if (mysqli_num_rows($result) > 0) {
    // User exists, proceed with deletion
    $deleteQuery = "DELETE FROM user WHERE username = '$username'";
    
    if (mysqli_query($connection, $deleteQuery)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Account deleted successfully'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error deleting account: ' . mysqli_error($connection)
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'User not found'
    ]);
}

mysqli_close($connection);
?>