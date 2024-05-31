<?php
include 'db_connect.php';

$booking_id = $_POST['booking_id'];
$room_id = $_POST['room_id'];
$number_of_adults = $_POST['number_of_adults'];
$number_of_children = $_POST['number_of_children'];
$note = $_POST['note'];
$bill_id = $_POST['bill_id'];
$description = $_POST['description'];
$price = $_POST['price'];

$stmt1 = $conn->prepare("INSERT INTO tbl_booking_details (booking_id, room_id, number_of_adults, number_of_children, note) VALUES (?, ?, ?, ?, ?)");
$stmt1->bind_param("iiiss", $booking_id, $room_id, $number_of_adults, $number_of_children, $note);

$stmt2 = $conn->prepare("INSERT INTO tbl_billing_items (description, price, bill_id, room_id) VALUES (?, ?, ?, ?)");
$stmt2->bind_param("siii", $description, $price, $bill_id, $room_id);

if ($stmt1->execute() && $stmt2->execute()) {
    echo "New records created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
