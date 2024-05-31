<?php
require_once('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize user input (user_id)
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);

    // Construct the SQL query to delete the user from the database
    $sql = "DELETE FROM tbl_users WHERE user_id = $user_id";

    if (mysqli_query($conn, $sql)) {
        echo 'User deleted successfully!';
    } else {
        echo 'Error deleting user: ' . mysqli_error($conn);
    }
} else {
    echo 'Invalid request method.';
}

// Close the database connection
mysqli_close($conn);
?>
