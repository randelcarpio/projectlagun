<?php
require_once('db_connect.php'); // Include the database connection file

// Check if a room_type_id parameter is provided via GET
if (isset($_GET['room_type_id'])) {
    $room_type_id = $_GET['room_type_id'];

    // Prepare a SQL query to fetch a specific room type by room_type_id
    $sql = "SELECT * FROM tbl_room_types WHERE room_type_id = ?";
    
    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("i", $room_type_id);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            
            if ($result->num_rows == 1) {
                // Fetch the room type details
                $row = $result->fetch_assoc();
                // Close the database connection
                $stmt->close();
                $conn->close();
                // Return the room type details as JSON
                header('Content-Type: application/json');
                echo json_encode($row);
                exit;
            }
        }
        $stmt->close();
    }
}

// If no specific room_type_id is provided or if there was an error, return the list of room types
$sql = "SELECT * FROM tbl_room_types";
$result = $conn->query($sql);

$data = array();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Close the database connection
$conn->close();

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
