<?php
require_once('db_connect.php'); // Include the database connection file

// Get data sent from AJAX
$roomNumber = $_POST['room_no'];
$availabilityStatus = $_POST['availability_status'];
$roomTypeId = $_POST['room_type_id'];

// Prepare and execute an SQL INSERT statement
$sql = "INSERT INTO tbl_rooms (room_no, availability_status, room_type_id) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("ssi", $roomNumber, $availabilityStatus, $roomTypeId);
    if ($stmt->execute()) {
        // Insert successful
        $response = array("success" => true, "message" => "Room added successfully.");
    } else {
        // Insert failed
        $response = array("success" => false, "message" => "Error adding room: " . $stmt->error);
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
