<?php
include 'connection.php';

// Get POST parameters
$old_username = $_POST['old_username'];
$new_username = $_POST['new_username'];
$new_password = $_POST['new_password'];

// Check if old username exists
$check_user = "SELECT * FROM user WHERE username = '$old_username'";
$result = mysqli_query($connection, $check_user);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo "User not found";
    exit();
}

// Initialize query parts
$updates = array();
$success = false;

// Handle username update if provided
if (!empty($new_username)) {
    // Check if new username already exists (skip if it's the same as old username)
    if ($new_username !== $old_username) {
        $check_username = "SELECT * FROM user WHERE username = '$new_username'";
        $username_result = mysqli_query($connection, $check_username);
        if (mysqli_num_rows($username_result) > 0) {
            echo "Username already taken";
            exit();
        }
        $updates[] = "username = '$new_username'";
    }
}

// Handle password update if provided
if (!empty($new_password)) {
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $updates[] = "password = '$hashed_password'";
}

// If there are updates to make
if (!empty($updates)) {
    $update_query = "UPDATE user SET " . implode(", ", $updates) . " WHERE username = '$old_username'";
    
    if (mysqli_query($connection, $update_query)) {
        echo "Profile updated";
    } else {
        echo "Error updating profile";
    }
} else {
    echo "No changes requested";
}

mysqli_close($connection);
?>