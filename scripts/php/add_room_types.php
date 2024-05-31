<?php
require_once('db_connect.php'); // Include the database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize POST data
    $roomTypeName = mysqli_real_escape_string($conn, $_POST['room_type_name']);
    $roomTypeDescription = mysqli_real_escape_string($conn, $_POST['room_type_description']);
    $roomTypePrice = floatval($_POST['room_type_price']);
    $maxCapacityAdults = intval($_POST['room_type_max_capacity_adults']);
    $maxCapacityKids = intval($_POST['room_type_max_capacity_kids']);

    // Create the pictureFolderPath
    $pictureFolderPath = "images/" . $roomTypeName;

    // SQL query to insert a new room type
    $sql = "INSERT INTO tbl_room_types (room_type_name, room_type_description, room_type_price, room_type_max_capacity_adults, room_type_max_capacity_kids, room_type_picture_folder_path)
            VALUES ('$roomTypeName', '$roomTypeDescription', $roomTypePrice, $maxCapacityAdults, $maxCapacityKids, '$pictureFolderPath')";
    
    if (mysqli_query($conn, $sql)) {
        echo "Room type added successfully.";

        // Create the directory if it doesn't exist
        $dirPath = "../../" . $pictureFolderPath;
        if (!file_exists($dirPath)) {
            mkdir($dirPath, 0777, true);
        }
    } else {
        echo "Error adding room type: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request.";
}

mysqli_close($conn);
?>
