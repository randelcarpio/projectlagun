<?php
require_once('db_connect.php'); // Include the database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    $query = "DELETE FROM tbl_room_extras WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        echo "Room extra deleted successfully!";
    } else {
        echo "Error deleting room extra: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request";
}

mysqli_close($conn);
?>
