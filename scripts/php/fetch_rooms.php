<?php
require_once('db_connect.php'); // Include the database connection file

// Check if a room_id parameter is provided via GET
if (isset($_GET['room_id'])) {
    $room_id = $_GET['room_id'];

    // Prepare a SQL query to fetch the room details by room_id
    $sql = "SELECT room_id, room_no, availability_status, room_type_id FROM tbl_rooms WHERE room_id = ?";
    
    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("i", $room_id);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            
            if ($result->num_rows == 1) {
                // Fetch the room details
                $row = $result->fetch_assoc();
                // Close the database connection
                $stmt->close();
                $conn->close();
                // Return the room details as JSON
                header('Content-Type: application/json');
                echo json_encode($row);
                exit;
            }
        }
        $stmt->close();
    }
}

// If no specific room_id is provided or if there was an error, return the list of rooms as before
$sql = "SELECT r.room_id, r.room_no, r.availability_status, t.room_type_name
          FROM tbl_rooms r
          LEFT JOIN tbl_room_types t ON r.room_type_id = t.room_type_id";
$result = $conn->query($sql);

$data = array();

if ($result) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    } else {
        // If no records found, add a single-row with the message
        $data[] = array(
            "room_id" => "No Records Found",
            "room_no" => "No Records Found",
            "availability_status" => "No Records Found",
            "room_type_id" => "No Records Found"
        );
    }
}

// Close the database connection
$conn->close();

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
