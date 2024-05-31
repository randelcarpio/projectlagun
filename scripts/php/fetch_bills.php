<?php
require_once('db_connect.php'); // Include the database connection file

// Perform a query to fetch data from the tbl_bills table and join it with the tbl_bookings and tbl_customers tables
$query = "SELECT b.bill_id, b.booking_id, b.total_amount, b.total_paid, b.status, CONCAT(c.first_name, ' ', c.last_name) AS guest_name
          FROM tbl_bills b
          INNER JOIN tbl_bookings bk ON b.booking_id = bk.booking_id
          INNER JOIN tbl_customers c ON bk.customer_id = c.customer_id";

$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode(array()); // Return an empty array in case of an error
} else {
    $data = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    echo json_encode($data);
}

// No need to explicitly close the connection; PHP will automatically close it when the script ends
?>
