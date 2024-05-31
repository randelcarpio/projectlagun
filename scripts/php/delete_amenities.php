<?php
require_once('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amenityId = $_POST['amenity_id'];

    $sql = "DELETE FROM tbl_amenities WHERE amenity_id = $amenityId";

    if (mysqli_query($conn, $sql)) {
        echo 'Amenity deleted successfully!';
    } else {
        echo 'Error deleting amenity: ' . mysqli_error($conn);
    }
} else {
    echo 'Invalid request method.';
}

// Close the database connection
mysqli_close($conn);
?>
