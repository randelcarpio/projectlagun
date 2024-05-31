<?php
require_once('db_connect.php'); // Include the database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['room_type_id'])) {
    $roomTypeId = intval($_POST['room_type_id']);

    // SQL query to delete a room type by room_type_id
    $sql = "DELETE FROM tbl_room_types WHERE room_type_id = $roomTypeId";

    if (mysqli_query($conn, $sql)) {
        echo "Room type deleted successfully.";
    } else {
        echo "Error deleting room type: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request.";
}

mysqli_close($conn);
?>
