<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 'On');
ini_set('error_log', 'php_errors.log');

// Log incoming request data
error_log("Request Method: " . $_SERVER['REQUEST_METHOD']);
error_log("GET Data: " . print_r($_GET, true));
error_log("POST Data: " . print_r($_POST, true));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (strpos($contentType, 'application/json') !== false) {
        $input = file_get_contents('php://input');
        $_POST = json_decode($input, true); 
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("JSON Decode Error: " . json_last_error_msg());
            echo json_encode(['status' => 'error', 'message' => 'Invalid JSON']);
            exit();
        }
        error_log("Decoded JSON POST Data: " . print_r($_POST, true)); 
    }
}

$hostName = "localhost";
$userName = "root";
$password = "";
$dbName = "helpafriend";

$connection = mysqli_connect($hostName, $userName, $password, $dbName);

if (!$connection) {
    error_log("Database connection failed: " . mysqli_connect_error());
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit();
}

// Handle POST request for Like/Unlike
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $postId = intval($_POST['post_id'] ?? 0);
    $userId = intval($_POST['user_id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if (!$postId || !$userId) {
        echo json_encode(['status' => 'error', 'message' => 'Missing post_id or user_id']);
        exit();
    }

    if ($action === 'like') {
        error_log("Like action received: post_id=$postId, user_id=$userId");
        $sql = "INSERT INTO likes (id_post, id_user) VALUES (?, ?) 
                ON DUPLICATE KEY UPDATE created_at = CURRENT_TIMESTAMP";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ii", $postId, $userId);

        if ($stmt->execute()) {
            // Get the updated like count
            $likeCountSql = "SELECT COUNT(*) AS like_count FROM likes WHERE id_post = ?";
            $likeCountStmt = $connection->prepare($likeCountSql);
            $likeCountStmt->bind_param("i", $postId);

            if ($likeCountStmt->execute()) {
                $result = $likeCountStmt->get_result();
                $likeCount = $result->fetch_assoc()['like_count'] ?? 0;
                echo json_encode(['status' => 'liked', 'like_count' => $likeCount]);
            } else {
                error_log("SQL Execution Error (Like Count): " . $likeCountStmt->error);
                echo json_encode(['status' => 'error', 'message' => 'Failed to fetch like count']);
            }
            $likeCountStmt->close();
        } else {
            error_log("SQL Error: " . $stmt->error);
            echo json_encode(['status' => 'error', 'message' => 'Error liking the post']);
        }
        $stmt->close();
    } elseif ($action === 'unlike') {
        error_log("Unlike action received: post_id=$postId, user_id=$userId");
        $sql = "DELETE FROM likes WHERE id_post = ? AND id_user = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ii", $postId, $userId);

        if ($stmt->execute()) {
            // Get the updated like count
            $likeCountSql = "SELECT COUNT(*) AS like_count FROM likes WHERE id_post = ?";
            $likeCountStmt = $connection->prepare($likeCountSql);
            $likeCountStmt->bind_param("i", $postId);

            if ($likeCountStmt->execute()) {
                $result = $likeCountStmt->get_result();
                $likeCount = $result->fetch_assoc()['like_count'] ?? 0;
                echo json_encode(['status' => 'unliked', 'like_count' => $likeCount]);
            } else {
                error_log("SQL Execution Error (Like Count): " . $likeCountStmt->error);
                echo json_encode(['status' => 'error', 'message' => 'Failed to fetch like count']);
            }
            $likeCountStmt->close();
        } else {
            error_log("SQL Execution Error: " . $stmt->error);
            echo json_encode(['status' => 'error', 'message' => 'Error unliking the post']);
        }
        $stmt->close();
    } else {
        error_log("Invalid action: $action");
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    }
    exit();
}

// Handle GET request for fetching posts with like status
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['user_id'])) {
    $userId = intval($_GET['user_id']);
    $sql = "SELECT f.*, 
                   (SELECT COUNT(*) > 0 FROM likes WHERE likes.id_post = f.id_post AND likes.id_user = $userId) AS is_liked,
                   (SELECT COUNT(*) FROM likes WHERE likes.id_post = f.id_post) AS like_count
            FROM forum f
            ORDER BY f.created_at DESC";
    error_log("Executing SQL: $sql");

    $result = mysqli_query($connection, $sql);
    if ($result) {
        $posts = [];
        while ($row = mysqli_fetch_assoc($result)) {
            error_log("Post Data: " . print_r($row, true)); 
            $posts[] = [
                'id_post' => $row['id_post'],
                'title' => $row['title'],
                'content' => $row['content'],
                'created_at' => $row['created_at'],
                'like_count' => intval($row['like_count']),
                'is_liked' => boolval($row['is_liked'])
            ];
        }
        echo json_encode($posts);
    } else {
        error_log("SQL Error: " . mysqli_error($connection));
        echo json_encode(['status' => 'error', 'message' => 'Error fetching posts']);
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['post_id'])) {
    $postId = intval($_GET['post_id']);
    $sql = "SELECT COUNT(*) AS like_count FROM likes WHERE id_post = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $postId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $likeCount = $result->fetch_assoc()['like_count'];
        echo json_encode(['like_count' => $likeCount]);
    } else {
        error_log("SQL Error: " . $stmt->error);
        echo json_encode(['status' => 'error', 'message' => 'Error fetching like count']);
    }
    $stmt->close();
    exit();
}

echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
mysqli_close($connection);
?>
