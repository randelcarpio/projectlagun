<?php
include 'db_connect.php';

if (isset($_POST['description'])) {
    // Add new billing item
    $bill_id = $_POST['bill_id'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $room_number = $_POST['room_number'];

    $stmt = $conn->prepare("INSERT INTO tbl_billing_items (description, price, bill_id, room_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siii", $description, $price, $bill_id, $room_number);

    if ($stmt->execute()) {
        echo "New billing item created successfully";
        
        // Retrieve all items with the same bill_id and sum their prices
        $stmt = $conn->prepare("SELECT SUM(price) AS total_price FROM tbl_billing_items WHERE bill_id = ?");
        $stmt->bind_param("i", $bill_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $total_price = $row['total_price'];

        // Update the total_amount column in tbl_bills
        $stmt = $conn->prepare("UPDATE tbl_bills SET total_amount = ? WHERE bill_id = ?");
        $stmt->bind_param("ii", $total_price, $bill_id);
        $stmt->execute();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} elseif (isset($_POST['amount'])) {
    // Add new payment
    $booking_id = $_POST['booking_id'];
    $amount = $_POST['amount'];
    $payment_method = $_POST['payment_method'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO tbl_payments (booking_id, amount, payment_method, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("idss", $booking_id, $amount, $payment_method, $status);

    $status = 'None'; // replace with your default status

    if ($stmt->execute()) {
        echo "New payment created successfully";
        
        // Retrieve all payments with the same booking_id and sum their amounts
        $stmt = $conn->prepare("SELECT SUM(amount) AS total_amount FROM tbl_payments WHERE booking_id = ?");
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $total_amount = $row['total_amount'];

        // Update the total_paid column in tbl_bills
        $stmt = $conn->prepare("UPDATE tbl_bills SET total_paid = ? WHERE booking_id = ?");
        $stmt->bind_param("ii", $total_amount, $booking_id);
        $stmt->execute();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
