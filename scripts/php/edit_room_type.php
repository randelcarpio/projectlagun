<?php
require_once('db_connect.php'); // Include the database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize POST data
    $roomTypeId = intval($_POST['edit_room_type_id']);
    $roomTypeName = mysqli_real_escape_string($conn, $_POST['edit_room_type_name']);
    $roomTypeDescription = mysqli_real_escape_string($conn, $_POST['edit_room_type_description']);
    $roomTypePrice = floatval($_POST['edit_room_type_price']);
    $maxCapacityAdults = intval($_POST['edit_room_type_max_capacity_adults']);
    $maxCapacityKids = intval($_POST['edit_room_type_max_capacity_kids']);
    //$pictureFolderPath = mysqli_real_escape_string($conn, $_POST['edit_room_type_picture_folder_path']);

    // SQL query to update an existing room type
    $sql = "UPDATE tbl_room_types
            SET room_type_name = '$roomTypeName', 
                room_type_description = '$roomTypeDescription', 
                room_type_price = $roomTypePrice, 
                room_type_max_capacity_adults = $maxCapacityAdults, 
                room_type_max_capacity_kids = $maxCapacityKids
            WHERE room_type_id = $roomTypeId;
            ";
    
    if (mysqli_query($conn, $sql)) {
        echo "Room type edited successfully.";
    } else {
        echo "Error editing room type: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request.";
}

mysqli_close($conn);
?>
