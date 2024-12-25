<?php
require_once('connection.php');  // Include your database connection file

// Check if 'username' is passed in the GET request
if (isset($_GET['username'])) {
    $username = $_GET['username'];

    // Query to fetch id_user for the given username
    $userQuery = "SELECT id_user FROM user WHERE username = ?";
    $stmt = $connection->prepare($userQuery);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $userResult = $stmt->get_result();

    // If user exists, proceed to fetch num_helped
    if ($userResult->num_rows > 0) {
        $userRow = $userResult->fetch_assoc();
        $id_user = $userRow['id_user'];

        // Query to fetch num_helped for the given id_user
        $leaderboardQuery = "SELECT num_helped FROM leaderboard WHERE id_user = ?";
        $stmt = $connection->prepare($leaderboardQuery);
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $leaderboardResult = $stmt->get_result();

        // Check if there is a record in the leaderboard
        if ($leaderboardResult->num_rows > 0) {
            $leaderboardRow = $leaderboardResult->fetch_assoc();
            $num_helped = $leaderboardRow['num_helped'];

            // Return num_helped as JSON
            echo json_encode(['num_helped' => $num_helped]);
        } else {
            // If no leaderboard data found, return num_helped as 0
            echo json_encode(['num_helped' => 0]);
        }
    } else {
        // If the username doesn't exist in the user table
        echo json_encode(['error' => 'User not found']);
    }

    $stmt->close();  // Close the statement
} else {
    // If no username is provided
    echo json_encode(['error' => 'username not provided']);
}

mysqli_close($connection);  // Close the database connection
?>