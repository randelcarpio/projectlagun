<?php
// Include the database connection file
require_once('db_connect.php');

// Get the room number from the AJAX request
$room_number = $_POST['room_number'];

// Fetch unique categories from the database
$category_query = "SELECT DISTINCT category FROM tbl_amenities WHERE room_number = '$room_number' OR room_number IS NULL";
$category_result = $conn->query($category_query);

$amenities = array();

while ($category_row = $category_result->fetch_assoc()) {
    $category = $category_row['category'];
    
    // Fetch amenities for the current category
    $amenity_query = "SELECT * FROM tbl_amenities WHERE category = '$category' AND (room_number = '$room_number' OR room_number IS NULL)";
    $amenity_result = $conn->query($amenity_query);
    
    while ($amenity_row = $amenity_result->fetch_assoc()) {
        $amenities[$category][] = $amenity_row;
    }
}

// Close the database connection
$conn->close();

// Return the amenities as a JSON object
echo json_encode($amenities);
?>
