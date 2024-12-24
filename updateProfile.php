<?php
include 'connection.php';

// Initialize the response array
$response = array(
    'success' => false,
    'message' => ''
);

// Get POST parameters
$old_username = $_POST['old_username'] ?? '';
$new_username = $_POST['new_username'] ?? '';
$new_password = $_POST['new_password'] ?? '';

// Check if old username exists
$check_user = "SELECT * FROM user WHERE username = '$old_username'";
$result = mysqli_query($connection, $check_user);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    $response['message'] = "User not found";
    echo json_encode($response);
    exit();
}

// Initialize query parts
$updates = array();

// Handle username update if provided
if (!empty($new_username)) {
    // Check if new username already exists (skip if it's the same as old username)
    if ($new_username !== $old_username) {
        $check_username = "SELECT * FROM user WHERE username = '$new_username'";
        $username_result = mysqli_query($connection, $check_username);
        if (mysqli_num_rows($username_result) > 0) {
            $response['message'] = "Username already taken";
            echo json_encode($response);
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
        $response['success'] = true;
        $response['message'] = "Profile updated successfully";
    } else {
        $response['message'] = "Error updating profile: " . mysqli_error($connection);
    }
} else {
    $response['message'] = "No changes requested";
}

// Set header to indicate JSON response
header('Content-Type: application/json');

// Send JSON response
echo json_encode($response);

mysqli_close($connection);
?>