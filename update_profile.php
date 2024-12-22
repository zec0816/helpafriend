<?php
include 'connection.php';

$oldUsername = $_POST['old_username'];
$newUsername = $_POST['new_username'];
$newPassword = $_POST['new_password'];

if (!empty($oldUsername)) {
    // Check if the user exists
    $checkUserSql = "SELECT * FROM user WHERE username = '$oldUsername'";
    $checkUserResult = mysqli_query($connection, $checkUserSql);

    if (mysqli_num_rows($checkUserResult) > 0) {
        $fieldsToUpdate = array();

        // Update the username if provided
        if (!empty($newUsername)) {
            // Check if the new username is already taken
            $checkNewUserSql = "SELECT * FROM user WHERE username = '$newUsername'";
            $checkNewUserResult = mysqli_query($connection, $checkNewUserSql);

            if (mysqli_num_rows($checkNewUserResult) > 0) {
                echo "Username already taken";
                exit;
            }
            $fieldsToUpdate[] = "username = '$newUsername'";
        }

        // Update the password if provided
        if (!empty($newPassword)) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $fieldsToUpdate[] = "password = '$hashedPassword'";
        }

        // If no changes detected
        if (empty($fieldsToUpdate)) {
            echo "No changes made";
            exit;
        }

        // Build and execute the UPDATE query
        $updateSql = "UPDATE user SET " . implode(", ", $fieldsToUpdate) . " WHERE username = '$oldUsername'";
        if (mysqli_query($connection, $updateSql)) {
            echo "Profile updated";
        } else {
            echo "Update failed: " . mysqli_error($connection);
        }
    } else {
        echo "User not found";
    }
} else {
    echo "Missing required fields";
}
?>
