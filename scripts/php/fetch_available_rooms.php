<?php
require_once('db_connect.php'); // Include the database connection file
// Check if check_in_date and check_out_date parameters are provided via GET
$check_in_date_exists = array_key_exists('check_in_date', $_GET);
$check_out_date_exists = array_key_exists('check_out_date', $_GET);


if ($check_in_date_exists && $check_out_date_exists) {
    $check_in_date = $_GET['check_in_date'];
    $check_out_date = $_GET['check_out_date'];

    $sql = "SELECT rt.*, GROUP_CONCAT(r.room_no) as room_numbers
        FROM tbl_room_types rt
        JOIN tbl_rooms r ON rt.room_type_id = r.room_type_id
        WHERE r.room_no NOT IN (
            SELECT bd.room_id FROM tbl_bookings b
            JOIN tbl_booking_details bd ON b.booking_id = bd.booking_id
            WHERE (
                (? >= b.check_in_date AND ? < b.check_out_date)
                OR
                (? > b.check_in_date AND ? <= b.check_out_date)
            )
            AND b.status != 'cancelled'
        )
        AND r.availability_status = 'available'
        GROUP BY rt.room_type_id
        ";

    $stmt = $conn->prepare($sql);
    
    
    /* Use prepared statements to prevent SQL injection
    
    if ($stmt === false) {
        var_dump($conn->error);
    } else {
        var_dump($stmt);
    }
    */

    
    if ($stmt) {
        $stmt->bind_param("ssss", $check_in_date, $check_in_date, $check_out_date, $check_out_date);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                // Fetch the available room types and room numbers
                $data = array();
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                // Close the database connection
                $stmt->close();
                $conn->close();
                // Return the data as JSON
                header('Content-Type: application/json');
                echo json_encode($data);
                exit;
            }
        }
        $stmt->close();
    }
}

// If no specific check_in_date and check_out_date are provided or if there was an error, return an error message
header('Content-Type: application/json');
echo json_encode(array('error' => 'Please provide both check_in_date and check_out_date.'));
?>
