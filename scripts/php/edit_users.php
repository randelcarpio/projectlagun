<?php
require_once('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize user input
    $user_id = $_POST['user_id'];
    $first_name = mysqli_real_escape_string($conn, $_POST['edit_first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['edit_last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['edit_email']);
    $password = mysqli_real_escape_string($conn, $_POST['edit_password']);
    $access_level = mysqli_real_escape_string($conn, $_POST['edit_access_level']);
    $account_status = mysqli_real_escape_string($conn, $_POST['edit_account_status']);

    // Construct the SQL query to update the user in the database
    if (empty($password)) {
        // If the password field is empty, update the user without changing the password
        $sql = "UPDATE tbl_users
                SET first_name = '$first_name', last_name = '$last_name', email = '$email', access_level = '$access_level', account_status = '$account_status'
                WHERE user_id = $user_id";
    } else {
        // If the password field is not empty, update the user with a new password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the new password
        $sql = "UPDATE tbl_users
                SET first_name = '$first_name', last_name = '$last_name', email = '$email', password_hash = '$hashed_password', access_level = '$access_level', account_status = '$account_status'
                WHERE user_id = $user_id";
    }

    if (mysqli_query($conn, $sql)) {
        echo 'User edited successfully!';
    } else {
        echo 'Error editing user: ' . mysqli_error($conn);
    }
} else {
    echo 'Invalid request method.';
}

// Close the database connection
mysqli_close($conn);
?>
