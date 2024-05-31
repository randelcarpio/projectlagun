<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Extras Table</title>
    
    <!-- Include CSS and JS libraries here -->
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
    checkAccessLevel(['super_admin', 'admin']);
?>
<div class="container mt-5">
    <h2>Room Extras Table</h2>

    <!-- Table element with the necessary attributes -->
    <div id="toolbar">
      <button id="add_new" class="btn btn-success">Add New</button>
    </div>
    <table id="roomExtrasTable" class="table table-bordered"
           data-toggle="table"
           data-toolbar="#toolbar"
           data-search="true"
           data-show-export="true"
           data-pagination="true"
           data-sortable="true"
           data-filter-control="true"
           data-export-types="['csv', 'excel', 'pdf']">
        <thead>
            <tr>
                <th data-field="id" data-sortable="true">ID</th>
                <th data-field="identification" data-sortable="true">Identification</th>
                <th data-field="room_no" data-sortable="true">Room Number</th>
                <th data-field="item_name" data-sortable="true">Item Name</th>
                <th data-field="description" data-sortable="true">Description</th>
                <th data-field="price" data-sortable="true">Price</th>
                <th data-field="actions">Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be loaded here using Ajax -->
        </tbody>
    </table>
    
    <!-- Modal to add a new room extra -->
    <div id="addRoomExtraModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add a New Room Extra</h2>
            <form id="addRoomExtraForm">
                <div class="form-group">
                    <label for="identification">Identification:</label>
                    <input type="text" class="form-control" id="identification" name="identification">
                </div>

                <div class="form-group">
                    <label for="item_name">Item Name:</label>
                    <input type="text" class="form-control" id="item_name" name="item_name" required>
                </div>

                <div class="form-group">
                    <label for="description">Description:</label>
                    <input type="text" class="form-control" id="description" name="description" required>
                </div>

                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="text" class="form-control" id="price" name="price">
                </div>

                <button type="submit" class="btn btn-primary">Add Room Extra</button>
            </form>
        </div>
    </div>

    <!-- Modal to edit a room extra -->
    <div id="editRoomExtraModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Room Extra</h2>
            <form id="editRoomExtraForm">
                <input type="hidden" id="edit_id" name="edit_id" value="">
                <div class="form-group">
                    <label for="edit_identification">Identification:</label>
                    <input type="text" class="form-control" id="edit_identification" name="edit_identification">
                </div>

                <div class="form-group">
                    <label for="edit_item_name">Item Name:</label>
                    <input type="text" class="form-control" id="edit_item_name" name="edit_item_name" required>
                </div>

                <div class="form-group">
                    <label for="edit_description">Description:</label>
                    <input type="text" class="form-control" id="edit_description" name="edit_description" required>
                </div>

                <div class="form-group">
                    <label for="edit_price">Price:</label>
                    <input type="text" class="form-control" id="edit_price" name="edit_price">
                </div>
                
                <div class="form-group">
                    <label for="edit_room">Room Number:</label>
                    <input type="text" class="form-control" id="edit_room" name="edit_room">
                </div>

                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>

    <!-- Modal to delete a room extra -->
    <div id="confirmDeleteRoomExtraModal" class="modal" style="display: none; height:auto;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 class="modal-title" id="confirmDeleteRoomExtraModalLabel">Confirm Delete</h2>
            <div class="modal-body">
                <p>Are you sure you want to delete this room extra? This action cannot be undone.</p>
            </div>
            <div class="modal-footer text-center">
                <button id="confirmDeleteRoomExtraButton" class="btn btn-danger">Delete</button>
                <button id="cancelDeleteRoomExtraButton" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Include JS scripts here -->
<!-- Scripts For All JS Scripts -->   
<?php require('scripts/php/lib_link_bt.php') ?>
<!-- Scripts For All JS Scripts -->
<script>
    var roomExtraId; // Define a variable to store the selected room extra ID

    // Open the add room extra modal when the "Add New Room Extra" button is clicked
    $("#add_new").click(function() {
        $("#addRoomExtraModal").css("display", "block");
    });

    // Close the add room extra modal when the close button is clicked
    $(".close").click(function() {
        $("#addRoomExtraModal").css("display", "none");
    });

    // Submit the add room extra form via AJAX
    $("#addRoomExtraForm").submit(function(event) {
        event.preventDefault();

        // Gather form data
        var formData = $(this).serialize();

        $.ajax({
            url: "scripts/php/add_room_extras.php", // Replace with your PHP script URL for adding room extras
            type: "POST",
            data: formData,
            success: function(response) {
                // Handle the response (e.g., display a success message)
                alert("Room extra added successfully!");
                // Close the add room extra modal
                clearForm('addRoomExtraForm');
                $("#addRoomExtraModal").css("display", "none");
                // Optionally, refresh the room extra table on your page
                refreshRoomExtraTable();
            },
            error: function(xhr, status, error) {
                // Handle errors (e.g., display an error message)
                alert("Error adding room extra: " + error);
                console.log(error);
            }
        });
    });

    // Open the edit room extra modal when an "Edit" button is clicked
    $(document).on("click", ".edit-button", function() {
        roomExtraId = $(this).data("room-extra-id");

        // Send an AJAX request to fetch room extra details by id
        $.ajax({
            url: 'scripts/php/fetch_room_extras.php', // Replace with the path to your PHP script
            type: 'GET',
            data: { id: roomExtraId }, // Pass the id parameter
            dataType: 'json',
            success: function(data) {
                // Check if data is not empty
                if (data) {
                    // Populate the edit modal form fields with room extra data
                    $("#editRoomExtraForm input[name='edit_identification']").val(data.identification);
                    $("#editRoomExtraForm input[name='edit_room_no']").val(data.room_no);
                    $("#editRoomExtraForm input[name='edit_item_name']").val(data.item_name);
                    $("#editRoomExtraForm input[name='edit_description']").val(data.description);
                    $("#editRoomExtraForm input[name='edit_price']").val(data.price);
                    $("#editRoomExtraForm input[name='edit_room']").val(data.room_no);

                    // Display the edit room extra modal
                    $("#editRoomExtraModal").css("display", "block");
                } else {
                    // Handle errors or display an error message
                    alert("Error fetching room extra details: Room extra not found.");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Ajax Error: ' + textStatus + ' - ' + errorThrown);
            }
        });
    });

    // Close the edit room extra modal when the close button is clicked
    $(".close").click(function() {
        $("#editRoomExtraModal").css("display", "none");
    });

    // Submit the edit room extra form via AJAX
    $("#editRoomExtraForm").submit(function(event) {
        event.preventDefault();

        // Gather form data
        var formData = $(this).serialize();
        // Add the room extra ID to the form data
        formData += '&id=' + roomExtraId;

        $.ajax({
            url: "scripts/php/edit_room_extras.php", // Replace with your PHP script URL for editing room extras
            type: "POST",
            data: formData,
            success: function(response) {
                // Handle the response (e.g., display a success message)
                alert("Room extra edited successfully!");
                // Close the edit room extra modal
                clearForm('editRoomExtraForm');
                $("#editRoomExtraModal").css("display", "none");
                // Optionally, refresh the room extra table on your page
                refreshRoomExtraTable();
            },
            error: function(xhr, status, error) {
                // Handle errors (e.g., display an error message)
                alert("Error editing room extra: " + error);
                console.log(error);
            }
        });
    });

    // Open the confirmation modal when a "Delete" button is clicked
    $(document).on("click", ".delete-button", function() {
        // Store the room extra ID in a data attribute of the confirm button
        $("#confirmDeleteRoomExtraButton").data("room-extra-id", $(this).data("room-extra-id"));
        // Show the confirmation modal
        $("#confirmDeleteRoomExtraModal").css("display", "block");
    });

    $("#confirmDeleteRoomExtraButton").click(function() {
        // Get the room extra ID from the confirm button's data attribute
        roomExtraId = $(this).data("room-extra-id");
        // Delete the room extra
        deleteRoomExtra(roomExtraId);
        // Hide the confirmation modal
        $("#confirmDeleteRoomExtraModal").css("display", "none");
        // Refresh the room extra table
        refreshRoomExtraTable();
    });

    $("#cancelDeleteRoomExtraButton").click(function() {
        // Hide the confirmation modal without deleting anything
        $("#confirmDeleteRoomExtraModal").css("display", "none");
    });

    function deleteRoomExtra(roomExtraId) {
        $.ajax({
            url: "scripts/php/delete_room_extras.php", // Replace with your PHP script URL for deleting room extras
            type: "POST",
            data: { id: roomExtraId },
            success: function(response) {
                // Handle the response (e.g., display a success message)
                alert("Room extra deleted successfully!");
                // Optionally, refresh the room extra list on your page
                refreshRoomExtraTable();
            },
            error: function(xhr, status, error) {
                // Handle errors (e.g., display an error message)
                alert("Error deleting room extra: " + error);
                console.log(error);
            }
        });
    }

    // FILL UP THE TABLE
    function refreshRoomExtraTable() {
        // Clear the contents of the room extra table
        $('#roomExtrasTable').bootstrapTable('removeAll');

        // Fetch room extra data using Ajax
        $.ajax({
            url: 'scripts/php/fetch_room_extras.php', // Replace with the path to your PHP script
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Create an empty array to store table rows
                var tableRows = [];

                // Iterate through the data and prepare rows
                $.each(data, function(index, roomExtra) {
                    var row = {
                        id: roomExtra.id,
                        identification: roomExtra.identification,
                        room_no: roomExtra.room_no,
                        item_name: roomExtra.item_name,
                        description: roomExtra.description,
                        price: roomExtra.price
                    };

                    // Add action buttons
                    row.actions = '<div style="text-align: center">' +
                        '<button class="btn btn-primary edit-button" data-room-extra-id="' + roomExtra.id + '"><i class="fas fa-edit"></i></button> ' +
                        '<button class="btn btn-danger delete-button" data-room-extra-id="' + roomExtra.id + '"><i class="fas fa-trash-alt"></i></button> ' +
                        '</div>';

                    tableRows.push(row);
                });

                // Update the bootstrap table with the new data
                $('#roomExtrasTable').bootstrapTable('load', tableRows);
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
        // Initialize the bootstrap table and call the function to load room extra data when the page is ready
        $('#roomExtrasTable').bootstrapTable();
        refreshRoomExtraTable();

        clearForm('addRoomExtraForm');
        clearForm('editRoomExtraForm');

        // Other initialization code can go here if needed
    });
</script>

</body>
</html>
