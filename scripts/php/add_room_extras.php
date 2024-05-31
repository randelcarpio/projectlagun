<?php
require_once('db_connect.php'); // Include the database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $identification = $_POST['identification'];
    $item_name = $_POST['item_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $query = "INSERT INTO tbl_room_extras (identification, item_name, description, price) 
              VALUES ('$identification', '$item_name', '$description', $price)";

    if (mysqli_query($conn, $query)) {
        echo "Room extra added successfully!";
    } else {
        echo "Error adding room extra: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request";
}

mysqli_close($conn);
?>
