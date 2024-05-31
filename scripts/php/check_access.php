<?php
// This function checks if the user's access level is allowed
function checkAccessLevel($allowed_levels) {
    // Check if the user is logged in by checking if 'access_level' is set in the cookie
    if (!isset($_COOKIE['access_level'])) {
        // If 'access_level' is not set in the cookie, the user is not logged in
        // Redirect the user to the login page
        header('Location: login.php');
        exit();
    }

    // Check if the user's access level is in the array of allowed levels
    if (!in_array($_COOKIE['access_level'], $allowed_levels)) {
        // If the user's access level is not in the array of allowed levels, they are not allowed to access the page
        // Redirect the user to the login page
        header('Location: login.php');
        exit();
    }

    // If the user's access level is in the array of allowed levels, they are allowed to access the page
    // Continue loading the page
}
?>
