<?php
require_once('db_connect.php'); // Include the database connection file

// Function to fetch customer details by customer_id
function fetchCustomerDetails($customerId)
{
    global $conn;
    $query = "SELECT c.customer_id, c.first_name, c.last_name, c.email, c.cellphone_number, b.booking_id
              FROM tbl_customers c
              LEFT JOIN tbl_bookings b ON c.customer_id = b.customer_id
              WHERE c.customer_id = $customerId";

    $result = mysqli_query($conn, $query);

    if (!$result) {
        return null; // Return null in case of an error
    } else {
        return mysqli_fetch_assoc($result);
    }
}

// Function to update customer information
function updateCustomer($customerId, $firstName, $lastName, $email, $cellphoneNumber)
{
    global $conn;
    $query = "UPDATE tbl_customers
              SET first_name = '$firstName', last_name = '$lastName', email = '$email', cellphone_number = '$cellphoneNumber'
              WHERE customer_id = $customerId";

    $result = mysqli_query($conn, $query);

    return $result;
}

// Main code
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['customer_id'])) {
        // Fetch customer details
        $customerId = $_GET['customer_id'];
        $customerDetails = fetchCustomerDetails($customerId);

        // Return customer details as JSON
        echo json_encode($customerDetails);
    } else {
        // Fetch all customers with booking numbers
        $query = "SELECT c.customer_id, c.first_name, c.last_name, c.email, c.cellphone_number, b.booking_id
                  FROM tbl_customers c
                  LEFT JOIN tbl_bookings b ON c.customer_id = b.customer_id";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            echo json_encode([]); // Return an empty array in case of an error
        } else {
            $data = [];

            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }

            echo json_encode($data);
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_customer_id'])) {
    // Update customer information
    $customerId = $_POST['edit_customer_id'];
    $firstName = $_POST['edit_first_name'];
    $lastName = $_POST['edit_last_name'];
    $email = $_POST['edit_email'];
    $cellphoneNumber = $_POST['edit_cellphone_number'];

    $success = updateCustomer($customerId, $firstName, $lastName, $email, $cellphoneNumber);

    // Return success status as JSON
    echo json_encode(['success' => $success]);
} else {
    // Invalid request
    echo json_encode(['error' => 'Invalid request']);
}

// No need to explicitly close the connection; PHP will automatically close it when the script ends
?>
