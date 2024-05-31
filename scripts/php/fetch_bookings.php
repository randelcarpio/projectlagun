<?php
require_once('db_connect.php'); // Include the database connection file

// Perform a query to fetch data from the tbl_bookings table and join it with the tbl_customers table
$query = "SELECT b.booking_id, CONCAT(c.first_name, ' ', c.last_name) AS customer_name, b.check_in_date, b.check_out_date, b.status FROM tbl_bookings b
          INNER JOIN tbl_customers c ON b.customer_id = c.customer_id";

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

mysqli_close($conn);
?>
