<?php
// Set header to indicate JSON response
header('Content-Type: application/json; charset=utf-8');

require_once 'connection.php';

// Ensure any errors are caught and returned as JSON
function returnError($message) {
    echo json_encode([
        'success' => false,
        'message' => $message
    ]);
    exit();
}

// Get username from POST request
if (!isset($_POST['username']) || empty($_POST['username'])) {
    returnError('Username is required');
}

$username = $_POST['username'];

// Start transaction
mysqli_begin_transaction($connection);

try {
    // Delete the user - cascade will handle related records
    $stmt = mysqli_prepare($connection, "DELETE FROM user WHERE username = ?");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt, "s", $username);
    
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Execute failed: " . mysqli_error($connection));
    }

    $affected_rows = mysqli_affected_rows($connection);
    
    if ($affected_rows > 0) {
        mysqli_commit($connection);
        echo json_encode([
            'success' => true,
            'message' => 'Account deleted successfully'
        ]);
    } else {
        mysqli_rollback($connection);
        echo json_encode([
            'success' => false,
            'message' => 'User not found'
        ]);
    }
    
    mysqli_stmt_close($stmt);

} catch (Exception $e) {
    mysqli_rollback($connection);
    returnError('Error deleting account: ' . $e->getMessage());
}

mysqli_close($connection);
?>