<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Table</title>
    
    <!-- Include Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" integrity="sha512-oAvZuuYVzkcTc2dH5z1ZJup5OmSQ000qlfRvuoTTiyTBjwX1faoyearj8KdMq0LgsBTHMrRuMek7s+CxF8yE+w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Include Bootstrap Table CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.22.1/bootstrap-table.min.css">
    
    <!-- Include Modal CSS -->
    <link rel="stylesheet" href="css/modal.css">
    
</head>
<body>
    <?php
    require 'scripts/php/check_access.php'; // Path to your check_access.php file
    checkAccessLevel(['super_admin']);
?>
<div class="container mt-5">
    <h2>User Table</h2>
    <!-- Table element for user data -->
    <div id="toolbar">
        <button id="add_new" class="btn btn-success">Add New User</button>
    </div>
    <table id="userTable" class="table table-bordered"
           data-toggle="table"
           data-toolbar="#toolbar"
           data-search="true"
           data-show-export="true"
           data-pagination="true"
           data-sortable="true"
           data-filter-control="true"
           data-show-toggle="true"
           data-show-columns="true"
           data-export-types="['json', 'xml', 'csv', 'txt', 'sql', 'excel', 'pdf']">
        <thead>
            <tr>
                <th data-field="user_id" data-sortable="true">User ID</th>
                <th data-field="first_name" data-sortable="true">First Name</th>
                <th data-field="last_name" data-sortable="true">Last Name</th>
                <th data-field="email" data-sortable="true">Email</th>
                <th data-field="access_level" data-filter-control="select">Access Level</th>
                <th data-field="created_at" data-sortable="true">Created At</th>
                <th data-field="updated_at" data-sortable="true">Updated At</th>
                <th data-field="account_status" data-filter-control="select">Account Status</th>
                <th data-field="actions">Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be loaded here using Ajax -->
        </tbody>
    </table>

    <!-- Modal to add a new user -->
    <!-- Modal to add a new user -->
    <div id="addUserModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add a New User</h2>
            <form id="addUserForm">
                <!-- Form fields go here -->
                <div class="form-group">
                    <label for="first_name">First Name:</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                </div>

                <div class="form-group">
                    <label for="last_name">Last Name:</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="access_level">Access Level:</label>
                    <select class="form-control" id="access_level" name="access_level" required>
                        <option value="admin">Admin</option>
                        <option value="front_desk">Front Desk</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Add User</button>
            </form>
        </div>
    </div>


    <!-- Modal to edit user -->
    <!-- Modal to edit a user -->
    <div id="editUserModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit User</h2>
            <form id="editUserForm">
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
                    <label for="edit_password">Change Password:</label>
                    <input type="password" class="form-control" id="edit_password" name="edit_password">
                </div>

                <div class="form-group">
                    <label for="edit_access_level">Access Level:</label>
                    <select class="form-control" id="edit_access_level" name="edit_access_level" required>
                        <option value="admin">Admin</option>
                        <option value="front_desk">Front Desk</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="edit_account_status">Account Status:</label>
                    <select class="form-control" id="edit_account_status" name="edit_account_status" required>
                        <option value="enabled">Enabled</option>
                        <option value="disabled">Disabled</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>


    <!-- Modal to delete user -->
    <!-- Modal to delete a user -->
    <div id="confirmDeleteUserModal" class="modal" style="display: none; height:auto;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 class="modal-title" id="confirmDeleteUserModalLabel">Confirm Delete User</h2>
            <div class="modal-body">
                <p>Are you sure you want to delete this user? This action cannot be undone.</p>
            </div>
            <div class="modal-footer text-center">
                <button id="confirmDeleteUserButton" class="btn btn-danger">Delete</button>
                <button id="cancelDeleteUserButton" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>

</div>

<!-- Include JavaScript libraries and custom scripts -->
<!-- ... (Include your JavaScript libraries and scripts) ... -->

<!-- Scripts For All JS Scripts -->   
<?php require('scripts/php/lib_link_bt.php') ?>
<script>
    // Modify the JavaScript code to work with the new user table
    $(document).ready(function() {
        // Open the add user modal when the "Add New User" button is clicked
        $("#add_new").click(function() {
            $("#addUserModal").css("display", "block");
        });

        // Close the add user modal when the close button is clicked
        $(".close").click(function() {
            $("#addUserModal").css("display", "none");
        });

        // Submit the add user form via AJAX
        $("#addUserForm").submit(function(event) {
            event.preventDefault();

            // Gather form data
            var formData = $(this).serialize();

            $.ajax({
                url: "scripts/php/add_users.php", // Replace with your PHP script URL
                type: "POST",
                data: formData,
                success: function(response) {
                    // Handle the response (e.g., display a success message)
                    alert("User added successfully!");
                    // Close the add user modal
                    clearForm('addUserForm');
                    $("#addUserModal").css("display", "none");
                    // Optionally, refresh the user list on your page
                    refreshUserTable();
                },
                error: function(xhr, status, error) {
                    // Handle errors (e.g., display an error message)
                    alert("Error adding user: " + error);
                    console.log(error);
                }
            });
        });
    });
    
    
    // PART TWO
    $(document).ready(function() {
        // Open the edit user modal when an "Edit" button is clicked
        $(document).on("click", ".edit-button", function() {
            userId = $(this).data("user-id");

            // Send an AJAX request to fetch user details by user_id
            $.ajax({
            url: 'scripts/php/fetch_users.php', // Replace with the path to your PHP script
            type: 'GET',
            data: { user_id: userId }, // Pass the user_id parameter
            dataType: 'json',
            success: function(data) {
                // Check if data is not empty
                if (data) {
                    // Populate the edit modal form fields with user data
                    $("#editUserForm input[name='edit_first_name']").val(data.first_name);
                    $("#editUserForm input[name='edit_last_name']").val(data.last_name);
                    $("#editUserForm input[name='edit_email']").val(data.email);
                    $("#editUserForm select[name='edit_access_level']").val(data.access_level);
                    $("#editUserForm select[name='edit_account_status']").val(data.account_status);

                    // If the access_level is 'super_admin', disable the fields
                    if (data.access_level === 'super_admin') {
                        $("#editUserForm select[name='edit_access_level']").prop('disabled', true);
                        $("#editUserForm select[name='edit_account_status']").prop('disabled', true);
                    } else {
                        $("#editUserForm select[name='edit_access_level']").prop('disabled', false);
                        $("#editUserForm select[name='edit_account_status']").prop('disabled', false);
                    }

                    // Display the edit user modal
                    $("#editUserModal").css("display", "block");
                } else {
                    // Handle errors or display an error message
                    alert("Error fetching user details: User not found.");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Ajax Error: ' + textStatus + ' - ' + errorThrown);
            }
        });

        });

        // Close the edit user modal when the close button is clicked
        $(".close").click(function() {
            $("#editUserModal").css("display", "none");
        });

        // Submit the edit user form via AJAX
        $("#editUserForm").submit(function(event) {
            event.preventDefault();

            // Gather form data
            var formData = $(this).serialize();
            // Add the user ID to the form data
            formData += '&user_id=' + userId;

            $.ajax({
                url: "scripts/php/edit_users.php", // Replace with your PHP script URL for editing
                type: "POST",
                data: formData,
                success: function(response) {
                    // Handle the response (e.g., display a success message)
                    alert("User edited successfully!");
                    // Close the edit user modal
                    clearForm('editUserForm');
                    $("#editUserModal").css("display", "none");
                    // Optionally, refresh the user list on your page
                    refreshUserTable();
                },
                error: function(xhr, status, error) {
                    // Handle errors (e.g., display an error message)
                    alert("Error editing user: " + error);
                    console.log(error);
                }
            });
        });
    });
    
    //FOR DELETE
    $(document).ready(function() {
        var userId;

        // Open the confirmation modal when a "Delete" button is clicked
        $(document).on("click", ".delete-button", function() {
            // Store the user ID in a data attribute of the confirm button
            $("#confirmDeleteUserButton").data("user-id", $(this).data("user-id"));
            // Show the confirmation modal
            $("#confirmDeleteUserModal").css("display", "block");
        });

        $("#confirmDeleteUserButton").click(function() {
            // Get the user ID from the confirm button's data attribute
            userId = $(this).data("user-id");
            // Delete the user
            deleteUser(userId);
            // Hide the confirmation modal
            $("#confirmDeleteUserModal").css("display", "none");
            // Refresh the user table
            refreshUserTable();
        });

        $("#cancelDeleteUserButton").click(function() {
            // Hide the confirmation modal without deleting anything
            $("#confirmDeleteUserModal").css("display", "none");
        });

        function deleteUser(userId) {
            $.ajax({
                url: "scripts/php/delete_users.php", // Replace with your PHP script URL for deletion
                type: "POST",
                data: { user_id: userId },
                success: function(response) {
                    // Handle the response (e.g., display a success message)
                    alert("User deleted successfully!");
                    // Optionally, refresh the user list on your page
                    refreshUserTable();
                },
                error: function(xhr, status, error) {
                    // Handle errors (e.g., display an error message)
                    alert("Error deleting user: " + error);
                    console.log(error);
                }
            });
        }
    });

    
    // FILL UP THE TABLE
    function refreshUserTable() {
        // Clear the contents of the user table
        $('#userTable').bootstrapTable('removeAll');

        // Fetch user data using Ajax
        $.ajax({
            url: 'scripts/php/fetch_users.php', // Replace with the path to your PHP script
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Create an empty array to store table rows
                var tableRows = [];

                // Iterate through the data and prepare rows
                $.each(data, function(index, user) {
                    var row = {
                        user_id: user.user_id,
                        first_name: user.first_name,
                        last_name: user.last_name,
                        email: user.email,
                        access_level: user.access_level,
                        account_status: user.account_status
                    };

                    // Get the 'access_level' cookie
                    var access_level_cookie = document.cookie.split('; ').find(row => row.startsWith('access_level')).split('=')[1];

                    // Only add action buttons if the user is not a super_admin or if there's a cookie for access_level and the value is 'super_admin'
                    if (user.access_level !== 'super_admin' || access_level_cookie === 'super_admin') {
                        row.actions = '<div style="text-align: center">' +
                            '<button class="btn btn-primary edit-button" data-user-id="' + user.user_id + '"><i class="fas fa-edit"></i></button> ' +
                            '<button class="btn btn-danger delete-button" data-user-id="' + user.user_id + '"><i class="fas fa-trash-alt"></i></button> ' +
                        '</div>';
                    }


                    tableRows.push(row);
                });

                // Update the bootstrap table with the new data
                $('#userTable').bootstrapTable('load', tableRows);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Ajax Error: ' + textStatus + ' - ' + errorThrown);
            }
        });
    }
    
    // CLEAR THE FORM
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


    $(document).ready(function() {
        // Call the function to load user data when the page is ready
        // Initialize the bootstrap table first
        $('#userTable').bootstrapTable();
        refreshUserTable();
        
        clearForm('addUserForm');
        clearForm('editUserForm');

        
        // Other initialization code can go here if needed
    });


</script>
</body>
</html>
