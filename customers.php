<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers Table</title>

    <!-- Include Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" integrity="sha512-oAvZuuYVzkcTc2dH5z1ZJup5OmSQ000qlfRvuoTTiyTBjwX1faoyearj8KdMq0LgsBTHMrRuMek7s+CxF8yE+w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Include Bootstrap Table CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.22.1/bootstrap-table.min.css">
    
    <!-- Include Modal CSS -->
    <link rel="stylesheet" href="css/modal_wide.css">

</head>

<body>
    <?php
        require 'scripts/php/check_access.php'; // Path to your check_access.php file
        checkAccessLevel(['super_admin', 'admin', 'front_desk']);
    ?>
    <div class="container mt-5">
        <h2>Customers Table</h2>
        <!-- Table element for customers data -->
        <table id="customerTable" class="table table-bordered" 
               data-toggle="table" 
               data-search="true" 
               data-show-export="true" 
               data-pagination="true" 
               data-sortable="true" 
               data-filter-control="true" 
               data-show-toggle="true" 
               data-show-columns="true" 
               data-advanced-search="true"
               data-id-table="advancedTable"
               data-export-types="['json', 'xml', 'csv', 'txt', 'sql', 'excel', 'pdf']">
            <thead>
                <tr>
                    <th data-field="customer_id" data-sortable="true">Customer ID</th>
                    <th data-field="first_name" data-sortable="true">First Name</th>
                    <th data-field="last_name" data-sortable="true">Last Name</th>
                    <th data-field="email" data-sortable="true">Email</th>
                    <th data-field="cellphone_number" data-sortable="true">Cellphone Number</th>
                    <th data-field="booking_number" data-sortable="true">Booking Number</th>
                    <th data-field="actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data will be loaded here using Ajax -->
            </tbody>
        </table>

        <!-- Modal to edit customer -->
        <div id="editCustomerModal" class="modal" style="display: none;">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Edit Customer</h2>
                <form id="editCustomerForm">
                    <!-- Form fields go here -->
                    <div class="form-group">
                        <label for="edit_first_name">First Name:</label>
                        <input type="text" class="form-control" id="edit_first_name" name="edit_first_name" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_last_name">Last Name:</label>
                        <input type="text" class="form-control" id="edit_last_name" name="edit_last_name" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_email">Email:</label>
                        <input type="email" class="form-control" id="edit_email" name="edit_email" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_cellphone_number">Cellphone Number:</label>
                        <input type="text" class="form-control" id="edit_cellphone_number" name="edit_cellphone_number" required>
                    </div>

                    <!-- Include a hidden input field for customer_id -->
                    <input type="hidden" id="edit_customer_id" name="edit_customer_id">

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>

    </div>

    <!-- Include JavaScript libraries and custom scripts -->
    <?php require('scripts/php/lib_link_bt.php') ?>
    <script>
        // Modify the JavaScript code to work with the new customer table
        $(document).ready(function () {
            // Open the edit customer modal when an "Edit" button is clicked
            $(document).on("click", ".edit-button", function () {
                customerId = $(this).data("customer-id");

                // Send an AJAX request to fetch customer details by customer_id
                $.ajax({
                    url: 'scripts/php/fetch_edit_customers.php', // Replace with the path to your PHP script
                    type: 'GET',
                    data: { customer_id: customerId }, // Pass the customer_id parameter
                    dataType: 'json',
                    success: function (data) {
                        // Check if data is not empty
                        if (data) {
                            // Populate the edit modal form fields with customer data
                            $("#editCustomerForm input[name='edit_first_name']").val(data.first_name);
                            $("#editCustomerForm input[name='edit_last_name']").val(data.last_name);
                            $("#editCustomerForm input[name='edit_email']").val(data.email);
                            $("#editCustomerForm input[name='edit_cellphone_number']").val(data.cellphone_number);

                            // Include the customer_id in the hidden input field
                            $("#edit_customer_id").val(customerId);

                            // Display the edit customer modal
                            $("#editCustomerModal").css("display", "block");
                        } else {
                            // Handle errors or display an error message
                            alert("Error fetching customer details: Customer not found.");
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log('Ajax Error: ' + textStatus + ' - ' + errorThrown);
                    }
                });
            });

            // Close the edit customer modal when the close button is clicked
            $(".close").click(function () {
                $("#editCustomerModal").css("display", "none");
            });

            // Submit the edit customer form via AJAX
            $("#editCustomerForm").submit(function (event) {
                event.preventDefault();

                // Gather form data
                var formData = $(this).serialize();

                $.ajax({
                    url: "scripts/php/fetch_edit_customers.php", // Replace with your PHP script URL for editing
                    type: "POST",
                    data: formData,
                    success: function (response) {
                        // Handle the response (e.g., display a success message)
                        alert("Customer edited successfully!");
                        // Close the edit customer modal
                        clearForm('editCustomerForm');
                        $("#editCustomerModal").css("display", "none");
                        // Optionally, refresh the customer list on your page
                        refreshCustomerTable();
                    },
                    error: function (xhr, status, error) {
                        // Handle errors (e.g., display an error message)
                        alert("Error editing customer: " + error);
                        console.log(error);
                    }
                });
            });
        });

        // Function to refresh the customer table
        function refreshCustomerTable() {
            // Clear the contents of the customer table
            $('#customerTable').bootstrapTable('removeAll');

            // Fetch customer data using Ajax
            $.ajax({
                url: 'scripts/php/fetch_edit_customers.php', // Replace with the path to your PHP script
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    // Create an empty array to store table rows
                    var tableRows = [];

                    // Iterate through the data and prepare rows
                    $.each(data, function (index, customer) {
                        var row = {
                            customer_id: customer.customer_id,
                            first_name: customer.first_name,
                            last_name: customer.last_name,
                            email: customer.email,
                            cellphone_number: customer.cellphone_number,
                            booking_number: customer.booking_id
                        };

                        // Add edit button to actions column
                        row.actions = '<button class="btn btn-primary edit-button" data-customer-id="' + customer.customer_id + '">Edit</button>';

                        tableRows.push(row);
                    });

                    // Update the bootstrap table with the new data
                    $('#customerTable').bootstrapTable('load', tableRows);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('Ajax Error: ' + textStatus + ' - ' + errorThrown);
                }
            });
        }

        // Function to clear the form
        function clearForm(formId) {
            // Get the form element by its ID
            var form = document.getElementById(formId);

            // Check if the form exists
            if (form) {
                // Loop through all form elements and clear their values
                for (var i = 0; i < form.elements.length; i++) {
                    var element = form.elements[i];

                    // Check if the element is an input, select, or textarea
                    if (element.tagName === 'INPUT' || element.tagName === 'SELECT' || element.tagName === 'TEXTAREA') {
                        // Clear the element's value
                        element.value = '';
                    }
                }
            }
        }

        // Call the function to load customer data when the page is ready
        $(document).ready(function () {
            // Initialize the bootstrap table first
            $('#customerTable').bootstrapTable();
            refreshCustomerTable();

            clearForm('editCustomerForm');

            // Other initialization code can go here if needed
        });
    </script>
</body>

</html>
