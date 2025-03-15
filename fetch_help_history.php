<?php
require_once('connection.php');

if (isset($_GET['username'])) {
    $username = $_GET['username'];

    // Get the volunteer's id_user using the provided username
    $query = "SELECT id_user FROM user WHERE username = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Volunteer found, get their id_user
        $userRow = $result->fetch_assoc();
        $volunteer_id = $userRow['id_user'];

        // Fetch OKU's id_user and updated_at (help date) where accepted_by = volunteer_id
        $query = "
            SELECT l.id_user AS oku_id, l.updated_at AS help_date, u.username AS oku_name
            FROM locations l
            JOIN user u ON l.id_user = u.id_user
            WHERE l.accepted_by = ? AND l.status = 'accepted'
        ";

        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $volunteer_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $helpedList = [];
        while ($row = $result->fetch_assoc()) {
            $helpedList[] = [
                'oku_id' => $row['oku_id'],
                'oku_name' => $row['oku_name'],
                'help_date' => $row['help_date'] 
            ];
        }

        $response = [
            'total_helped' => $result->num_rows,
            'helped_list' => $helpedList
        ];
        echo json_encode($response);
    } else {
        echo json_encode(['error' => 'Volunteer not found']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'username not provided']);
}

mysqli_close($connection);
?>