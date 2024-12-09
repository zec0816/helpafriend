<?php
$hostName = "localhost";
$userName = "root";
$password = "";
$dbName = "helpafriend";

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', 0);
header('Content-Type: application/json');

// Retrieve and validate the input data
$data = json_decode(file_get_contents('php://input'), true);

// Debugging: Check if data is received correctly
if (!$data) {
    echo json_encode(["error" => "Invalid or missing JSON input.", "rawData" => file_get_contents('php://input')]);
    exit();
}

// Check if the necessary fields are set
if (!isset($data['postId'], $data['title'], $data['isLoved'])) {
    echo json_encode(["error" => "Missing required fields: 'postId', 'title' or 'isLoved'."]);
    exit();
}

$postId = $data['postId']; // Now you can safely use $postId

// Validate 'title' and 'isLoved'
if (!isset($data['title'], $data['isLoved'])) {
    echo json_encode(["error" => "Missing required fields: 'title' or 'isLoved'."]);
    exit();
}

$title = $data['title'];
$isLoved = $data['isLoved'];

if (!is_string($title) || !is_bool($isLoved)) {
    echo json_encode(["error" => "'title' must be a string and 'isLoved' must be a boolean."]);
    exit();
}

// Your database and processing logic here

// Send a success response
$updatedLikeCount = 0; // Replace this with your logic
echo json_encode([
    "message" => "Like state updated successfully.",
    "updatedLikeCount" => $updatedLikeCount
]);

exit();
?>
