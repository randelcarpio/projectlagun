<?php
require_once('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize user input
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $access_level = mysqli_real_escape_string($conn, $_POST['access_level']);
    $created_at = date('Y-m-d H:i:s'); // Current timestamp
    $account_status = 'enabled'; // You can set an initial status

    // Insert the user into the database
    $sql = "INSERT INTO tbl_users (first_name, last_name, email, password_hash, access_level, created_at, account_status)
            VALUES ('$first_name', '$last_name', '$email', '$password', '$access_level', '$created_at', '$account_status')";

    if (mysqli_query($conn, $sql)) {
        echo 'User added successfully!';
    } else {
        echo 'Error adding user: ' . mysqli_error($conn);
    }
} else {
    echo 'Invalid request method.';
}

// Close the database connection
mysqli_close($conn);
?>
