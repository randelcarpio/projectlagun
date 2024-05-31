<?php
require_once('db_connect.php'); // Include the database connection file

// Get data sent from AJAX
$roomId = $_POST['room_id']; // You'll need the ID of the room you want to delete

// Prepare and execute an SQL DELETE statement
$sql = "DELETE FROM tbl_rooms WHERE room_id = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $roomId);
    if ($stmt->execute()) {
        // Delete successful
        $response = array("success" => true, "message" => "Room deleted successfully.");
    } else {
        // Delete failed
        $response = array("success" => false, "message" => "Error deleting room: " . $stmt->error);
    }
    $stmt->close();
} else {
    // Statement preparation failed
    $response = array("success" => false, "message" => "Error preparing statement: " . $conn->error);
}

// Close the database connection
$conn->close();

// Send JSON response to the AJAX request
header('Content-Type: application/json');
echo json_encode($response);
?>
