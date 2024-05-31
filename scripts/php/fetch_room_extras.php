<?php
require_once('db_connect.php'); // Include the database connection file

// Check if an id parameter is provided via GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare a SQL query to fetch room extra details by id
    $sql = "SELECT id, identification, room_no, item_name, description, price FROM tbl_room_extras WHERE id = ?";
    
    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            
            if ($result->num_rows == 1) {
                // Fetch the room extra details
                $row = $result->fetch_assoc();
                // Close the database connection
                $stmt->close();
                $conn->close();
                // Return the room extra details as JSON
                header('Content-Type: application/json');
                echo json_encode($row);
                exit;
            }
        }
        $stmt->close();
    }
}

// If no specific id is provided or if there was an error, return the list of room extras
$sql = "SELECT id, identification, room_no, item_name, description, price FROM tbl_room_extras";
$result = $conn->query($sql);

$data = array();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Close the database connection
$conn->close();

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
