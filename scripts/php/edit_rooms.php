<?php
require_once('db_connect.php'); // Include the database connection file

// Get data sent from AJAX
$roomId = $_POST['room_id']; // You'll need the ID of the room you want to update
$roomNumber = $_POST['edit_room_no'];
$availabilityStatus = $_POST['edit_availability_status'];
$roomTypeId = $_POST['edit_room_type_id'];

// Prepare and execute an SQL UPDATE statement
$sql = "UPDATE tbl_rooms SET room_no = ?, availability_status = ?, room_type_id = ? WHERE room_id = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("ssii", $roomNumber, $availabilityStatus, $roomTypeId, $roomId);
    if ($stmt->execute()) {
        // Update successful
        $response = array("success" => true, "message" => "Room updated successfully.");
    } else {
        // Update failed
        $response = array("success" => false, "message" => "Error updating room: " . $stmt->error);
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
