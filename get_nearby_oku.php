<?php
// Include the database connection
require_once('connection.php');

// Query to get all OKU locations from the database
$sql = "SELECT id_location, latitude, longitude FROM locations WHERE status = 'pending'";
$result = mysqli_query($connection, $sql);

$locations = array();

// Fetch all the rows and add them to the locations array
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $locations[] = $row;
    }
}

// Set the content type to JSON and output the results
header('Content-Type: application/json');
echo json_encode($locations);

// Close the database connection
mysqli_close($connection);
?>
