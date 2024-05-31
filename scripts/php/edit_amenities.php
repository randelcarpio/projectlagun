<?php
require_once('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize user input
    $amenity_id = $_POST['amenity_id'];
    $amenity_name = mysqli_real_escape_string($conn, $_POST['edit_amenity_name']);
    $description = mysqli_real_escape_string($conn, $_POST['edit_description']);
    $category = mysqli_real_escape_string($conn, $_POST['edit_category']);
    $icon = mysqli_real_escape_string($conn, $_POST['edit_icon']);
    $is_available = mysqli_real_escape_string($conn, $_POST['edit_is_available']);

    // Construct the SQL query to update the amenity in the database
    $sql = "UPDATE tbl_amenities
            SET amenity_name = '$amenity_name', description = '$description', category = '$category', icon = '$icon', is_available = '$is_available'
            WHERE amenity_id = $amenity_id";

    if (mysqli_query($conn, $sql)) {
        echo 'Amenity edited successfully!';
    } else {
        echo 'Error editing amenity: ' . mysqli_error($conn);
    }
} else {
    echo 'Invalid request method.';
}

// Close the database connection
mysqli_close($conn);
?>
