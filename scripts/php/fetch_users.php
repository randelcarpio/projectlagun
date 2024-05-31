<?php
require_once('db_connect.php'); // Include the database connection file

// Check if a user_id parameter is provided via GET
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Prepare a SQL query to fetch a specific user by user_id
    $sql = "SELECT * FROM tbl_users WHERE user_id = ?";
    
    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            
            if ($result->num_rows == 1) {
                // Fetch the user details
                $row = $result->fetch_assoc();
                // Close the database connection
                $stmt->close();
                $conn->close();
                // Return the user details as JSON
                header('Content-Type: application/json');
                echo json_encode($row);
                exit;
            }
        }
        $stmt->close();
    }
}

// If no specific user_id is provided or if there was an error, return the list of users
$sql = "SELECT * FROM tbl_users";
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
