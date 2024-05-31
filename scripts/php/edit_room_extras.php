<?php
require_once('db_connect.php'); // Include the database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $identification = $_POST['edit_identification'];
    $item_name = $_POST['edit_item_name'];
    $description = $_POST['edit_description'];
    $price = $_POST['edit_price'];
    $room_no = $_POST['edit_room'];

    $query = "UPDATE tbl_room_extras 
                SET identification = '$identification', item_name = '$item_name', 
                    description = '$description', price = $price, room_no = '$room_no'
                WHERE id = $id;
                ";

    if (mysqli_query($conn, $query)) {
        echo "Room extra edited successfully!";
    } else {
        echo "Error editing room extra: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request";
}

mysqli_close($conn);
?>
