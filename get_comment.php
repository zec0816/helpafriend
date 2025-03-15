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

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id_post']) && is_numeric($_GET['id_post'])) {
        $id_post = mysqli_real_escape_string($connection, $_GET['id_post']);

        $titleQuery = "SELECT title FROM forum WHERE id_post = '$id_post'";
        $titleResult = mysqli_query($connection, $titleQuery);

        if ($titleResult && mysqli_num_rows($titleResult) > 0) {
            $postTitle = mysqli_fetch_assoc($titleResult)['title'];

            // Fetch comments for the post
            $commentsQuery = "
                SELECT 
                    comment.id_comment AS comment_id, 
                    comment.comment, 
                    comment.created_at, 
                    user.username 
                FROM 
                    comment 
                INNER JOIN 
                    user 
                ON 
                    comment.id_user = user.id_user 
                WHERE 
                    comment.id_post = '$id_post' 
                ORDER BY 
                    comment.created_at DESC
            ";

            $commentsResult = mysqli_query($connection, $commentsQuery);

            $comments = [];
            if ($commentsResult) {
                while ($row = mysqli_fetch_assoc($commentsResult)) {
                    $comments[] = $row;
                }
            }

            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'postTitle' => $postTitle,
                'comments' => $comments
            ], JSON_PRETTY_PRINT);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Post not found']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid or missing id_post']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

mysqli_close($connection);
?>
