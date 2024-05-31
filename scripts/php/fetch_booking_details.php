<?php
include 'db_connect.php';

// Get the booking_id from the request
$booking_id = $_GET['booking_id'];

// Initialize an array to hold the data
$data = array();

// Fetch booking details
$query = "SELECT * FROM tbl_booking_details WHERE booking_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();
$booking_details = $result->fetch_all(MYSQLI_ASSOC);

// Fetch room type names for each booking detail
foreach ($booking_details as $key => $booking_detail) {
    $query = "SELECT rt.room_type_name FROM tbl_room_types rt JOIN tbl_rooms r ON rt.room_type_id = r.room_type_id WHERE r.room_no = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $booking_detail['room_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $room_type = $result->fetch_assoc();
    $booking_details[$key]['room_type_name'] = $room_type['room_type_name'];
}
$data['booking_details'] = $booking_details;

// Fetch billing items
$query = "SELECT * FROM tbl_billing_items WHERE bill_id IN (SELECT bill_id FROM tbl_bills WHERE booking_id = ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();
$data['billing_items'] = $result->fetch_all(MYSQLI_ASSOC);

// Fetch payments
$query = "SELECT * FROM tbl_payments WHERE booking_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();
$data['payments'] = $result->fetch_all(MYSQLI_ASSOC);

// Fetch booking
$query = "SELECT * FROM tbl_bookings WHERE booking_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();
$data['booking'] = $result->fetch_assoc();

// Close the database connection
$stmt->close();
$conn->close();

// Return the data as a JSON string
echo json_encode($data);
?>
