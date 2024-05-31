<?php
require_once('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize user input
    $amenity_name = mysqli_real_escape_string($conn, $_POST['amenity_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $icon = mysqli_real_escape_string($conn, $_POST['icon']);
    $is_available = mysqli_real_escape_string($conn, $_POST['is_available']);
    $created_at = date('Y-m-d H:i:s'); // Current timestamp

    // Insert the amenity into the database
    $sql = "INSERT INTO tbl_amenities (amenity_name, description, category, icon, is_available, created_at)
            VALUES ('$amenity_name', '$description', '$category', '$icon', '$is_available', '$created_at')";

    if (mysqli_query($conn, $sql)) {
        echo 'Amenity added successfully!';
    } else {
        echo 'Error adding amenity: ' . mysqli_error($conn);
    }
} else {
    echo 'Invalid request method.';
}

// Close the database connection
mysqli_close($conn);
?>
