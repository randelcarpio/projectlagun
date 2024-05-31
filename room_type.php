<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Table</title>
    
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
    <h2>Room Type Table</h2>

    <!-- Table element with the "table" and "table-bordered" classes -->
    <div id="toolbar">
      <button id="add_new" class="btn btn-success">Add New</button>
    </div>
    <table id="roomTypeTable" class="table table-bordered"
           data-toggle="table"
           data-toolbar="#toolbar"
           data-search="true"
           data-show-export="true"
           data-pagination="true"
           data-sortable="true"
           data-export-types="['csv', 'excel', 'pdf']">
        <thead>
            <tr>
                <th data-field="room_type_id" data-sortable="true">Room Type ID</th>
                <th data-field="room_type_name" data-sortable="true">Room Type Name</th>
                <th data-field="room_type_description" data-sortable="true">Description</th>
                <th data-field="room_type_price" data-sortable="true">Price (per night)</th>
                <th data-field="room_type_max_capacity_adults" data-sortable="true">Max Adults</th>
                <th data-field="room_type_max_capacity_kids" data-sortable="true">Max Kids</th>
                <th data-field="room_type_picture_folder_path">Pictures</th>
                <th data-field="actions">Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be loaded here using Ajax -->
        </tbody>
    </table>
    
    <!-- Modal to add a new room type -->
    <div id="addRoomTypeModal" class="modal" style="display: none; height:auto;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add a New Room Type</h2>
            <form id="addRoomTypeForm">
                <!-- Form fields for adding room type -->
                <div class="form-group">
                    <label for="room_type_name">Room Type Name:</label>
                    <input type="text" class="form-control" id="room_type_name" name="room_type_name" required>
                </div>

                <div class="form-group">
                    <label for="room_type_description">Description:</label>
                    <textarea class="form-control" id="room_type_description" name="room_type_description" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="room_type_price">Price (per night):</label>
                    <input type="number" step="0.01" class="form-control" id="room_type_price" name="room_type_price" required>
                </div>

                <div class="form-group">
                    <label for="room_type_max_capacity_adults">Max Adults:</label>
                    <input type="number" class="form-control" id="room_type_max_capacity_adults" name="room_type_max_capacity_adults" required>
                </div>

                <div class="form-group">
                    <label for="room_type_max_capacity_kids">Max Kids:</label>
                    <input type="number" class="form-control" id="room_type_max_capacity_kids" name="room_type_max_capacity_kids" required>
                </div>

                <button type="submit" class="btn btn-primary">Add Room Type</button>
            </form>
        </div>
    </div>
    
    <!-- Modal to edit a room type -->
    <div id="editRoomTypeModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Room Type</h2>
            <form id="editRoomTypeForm">
                <!-- Form fields for editing room type -->
                <div class="form-group">
                    <input type="hidden" class="form-control" id="edit_room_type_id" name="edit_room_type_id" required>
                </div>

                <div class="form-group">
                    <label for="edit_room_type_name">Room Type Name:</label>
                    <input type="text" class="form-control" id="edit_room_type_name" name="edit_room_type_name" required>
                </div>

                <div class="form-group">
                    <label for="edit_room_type_description">Description:</label>
                    <textarea rows="4" class="form-control" id="edit_room_type_description" name="edit_room_type_description" required></textarea>
                </div>

                <div class="form-group">
                    <label for="edit_room_type_price">Price (per night):</label>
                    <input type="number" step="0.01" class="form-control" id="edit_room_type_price" name="edit_room_type_price" required>
                </div>

                <div class="form-group">
                    <label for="edit_room_type_max_capacity_adults">Max Adults:</label>
                    <input type="number" class="form-control" id="edit_room_type_max_capacity_adults" name="edit_room_type_max_capacity_adults" required>
                </div>

                <div class="form-group">
                    <label for="edit_room_type_max_capacity_kids">Max Kids:</label>
                    <input type="number" class="form-control" id="edit_room_type_max_capacity_kids" name="edit_room_type_max_capacity_kids" required>
                </div>

                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
</div>

<!-- Modal to confirm deletion of a room type -->
<div id="confirmDeleteModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete</h2>
        <div class="modal-body">
            <p>Are you sure you want to delete this room type? This action cannot be undone.</p>
        </div>
        <div class="modal-footer text-center">
            <button id="confirmDeleteButton" class="btn btn-danger">Delete</button>
            <button id="cancelDeleteButton" class="btn btn-secondary">Cancel</button>
        </div>
    </div>
</div>

<!-- Scripts For All JS Scripts -->   
<?php require('scripts/php/lib_link_bt.php') ?>

<!-- JavaScript for loading and managing data -->
<script>
    $(document).ready(function() {
        // Using Ajax to fetch data from PHP script
        $.ajax({
            url: 'scripts/php/fetch_room_types.php', // Replace with the path to your PHP script for fetching room types
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Create an empty array to store table rows
                var tableRows = [];

                // Iterate through the data and prepare rows
                $.each(data, function(index, roomType) {
                    var row = {
                        room_type_id: roomType.room_type_id,
                        room_type_name: roomType.room_type_name,
                        room_type_description: roomType.room_type_description,
                        room_type_price: roomType.room_type_price,
                        room_type_max_capacity_adults: roomType.room_type_max_capacity_adults,
                        room_type_max_capacity_kids: roomType.room_type_max_capacity_kids,
                        room_type_picture_folder_path: roomType.room_type_picture_folder_path,
                        actions: '<div style="text-align: center">' +
                            '<button class="btn btn-primary edit-button" data-room-type-id="' + roomType.room_type_id + '"><i class="fas fa-edit"></i></button> ' +
                            '<button class="btn btn-danger delete-button" data-room-type-id="' + roomType.room_type_id + '"><i class="fas fa-trash-alt"></i></button> ' +
                        '</div>'
                    };
                    tableRows.push(row);
                });

                // Append the rows to the table using bootstrapTable('append', data)
                $('#roomTypeTable').bootstrapTable('append', tableRows);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Ajax Error: ' + textStatus + ' - ' + errorThrown);
            }
        });

        // Initialize Table as Bootstrap-Table
        $('#roomTypeTable').bootstrapTable();
    });
</script>
    

    <script>
    // After making changes to the database, call this function to refresh the table.
    function refreshTable() {
        // Destroy the existing bootstrap table
        $('#roomTypeTable').bootstrapTable('destroy');

        // Load the data from your PHP script again and update the table.
        $.ajax({
            url: 'scripts/php/fetch_room_types.php', // Update the URL to the room types script
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Create an empty array to store table rows
                var tableRows = [];

                // Iterate through the data and prepare rows
                $.each(data, function(index, roomType) {
                    var row = {
                        room_type_id: roomType.room_type_id, // Updated key to match data-field for room_type_id
                        room_type_name: roomType.room_type_name, // Updated key to match data-field
                        room_type_description: roomType.room_type_description, // Updated key to match data-field
                        room_type_price: roomType.room_type_price, // Updated key to match data-field
                        room_type_max_capacity_adults: roomType.room_type_max_capacity_adults, // Updated key to match data-field
                        room_type_max_capacity_kids: roomType.room_type_max_capacity_kids, // Updated key to match data-field
                        room_type_picture_folder_path: roomType.room_type_picture_folder_path,
                        actions: '<div style="text-align: center">' +
                            '<button class="btn btn-primary edit-button" data-room-type-id="' + roomType.room_type_id + '"><i class="fas fa-edit"></i></button> ' +
                            '<button class="btn btn-danger delete-button" data-room-type-id="' + roomType.room_type_id + '"><i class="fas fa-trash-alt"></i></button> ' +
                        '</div>'
                    };
                    tableRows.push(row);
                });

                // Initialize the bootstrap table with the new data
                $('#roomTypeTable').bootstrapTable({ data: tableRows });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Ajax Error: ' + textStatus + ' - ' + errorThrown);
            }
        });
    }
</script>



<!-- JavaScript for handling "Add Room Type" modal -->
<script>
    $(document).ready(function() {
        // Open the modal when the "Add New" button is clicked
        $("#add_new").click(function() {
            $("#addRoomTypeModal").css("display", "block");
        });

        // Close the modal when the close button is clicked
        $(".close").click(function() {
            $("#addRoomTypeModal").css("display", "none");
        });

        // Submit the form via AJAX for adding a room type
        $("#addRoomTypeForm").submit(function(event) {
            event.preventDefault();

            // Gather form data
            var formData = $(this).serialize();

            $.ajax({
                url: "scripts/php/add_room_type.php", // Replace with your PHP script URL for adding room type
                type: "POST",
                data: formData,
                success: function(response) {
                    // Handle the response (e.g., display a success message)
                    alert("Room type added successfully!");
                    // Close the modal
                    $("#addRoomTypeModal").css("display", "none");
                    // Optionally, refresh the room type list on your page
                    refreshTable();
                },
                error: function(xhr, status, error) {
                    // Handle errors (e.g., display an error message)
                    alert("Error adding room type: " + error);
                    console.log(error);
                }
            });
        });
    });
</script>


<!-- JavaScript for handling "Edit Room Type" modal -->
<script>
    $(document).ready(function() {
        // Open the modal when the "Edit" button is clicked
        $(document).on("click", ".edit-button", function() {
            var roomTypeId = $(this).data("room-type-id");

            // Send an AJAX request to fetch room type details by room_type_id
            $.ajax({
                url: 'scripts/php/fetch_room_types.php', // Replace with the path to your PHP script for fetching a specific room type
                type: 'GET',
                data: { room_type_id: roomTypeId }, // Pass the room_type_id parameter
                dataType: 'json',
                success: function(data) {
                    // Check if data is not empty and does not contain a "No Records Found" message
                    if (data && data.room_type_id !== "No Records Found") {
                        // Populate the edit modal form fields with room type data
                        $("#edit_room_type_id").val(data.room_type_id);
                        $("#edit_room_type_name").val(data.room_type_name);
                        $("#edit_room_type_description").val(data.room_type_description);
                        $("#edit_room_type_price").val(data.room_type_price);
                        $("#edit_room_type_max_capacity_adults").val(data.room_type_max_capacity_adults);
                        $("#edit_room_type_max_capacity_kids").val(data.room_type_max_capacity_kids);

                        // Display the edit modal
                        $("#editRoomTypeModal").css("display", "block");
                        // Optionally, refresh the room type list on your page
                        refreshTable();
                    } else {
                        // Handle errors or display an error message
                        alert("Error fetching room type details: Room type not found.");
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('Ajax Error: ' + textStatus + ' - ' + errorThrown);
                }
            });
        });

        // Close the modal when the close button is clicked
        $(".close").click(function() {
            $("#editRoomTypeModal").css("display", "none");
        });

        // Submit the edit form via AJAX for updating a room type
        $("#editRoomTypeForm").submit(function(event) {
            event.preventDefault();

            // Gather form data
            var formData = $(this).serialize();

            $.ajax({
                url: "scripts/php/edit_room_type.php", // Replace with your PHP script URL for editing room type
                type: "POST",
                data: formData,
                success: function(response) {
                    // Handle the response (e.g., display a success message)
                    alert("Room type edited successfully!");
                    // Close the edit modal
                    $("#editRoomTypeModal").css("display", "none");
                    // Optionally, refresh the room type list on your page
                    refreshTable();
                },
                error: function(xhr, status, error) {
                    // Handle errors (e.g., display an error message)
                    alert("Error editing room type: " + error);
                    console.log(error);
                }
            });
        });
    });
</script>
    
<!-- JavaScript for handling deletion -->
<script>
    $(document).ready(function() {
        // Open the confirmation modal when the "Delete" button is clicked
        $(document).on("click", ".delete-button", function() {
            var roomTypeId = $(this).data("room-type-id");

            // Store the room type ID in a data attribute of the confirm button
            $("#confirmDeleteButton").data("room-type-id", roomTypeId);
            
            // Show the confirmation modal
            $("#confirmDeleteModal").css("display", "block");
        });

        // Handle deletion when the confirmation button is clicked
        $("#confirmDeleteButton").click(function() {
            // Get the room type ID from the confirm button's data attribute
            var roomTypeId = $(this).data("room-type-id");

            // Delete the room type
            deleteRoomType(roomTypeId);

            // Hide the confirmation modal
            $("#confirmDeleteModal").css("display", "none");

            // Optionally, refresh the table
            refreshTable();
        });

        // Handle cancellation when the cancel button is clicked
        $("#cancelDeleteButton").click(function() {
            // Hide the confirmation modal without deleting anything
            $("#confirmDeleteModal").css("display", "none");
        });

        // Function to delete a room type
        function deleteRoomType(roomTypeId) {
            $.ajax({
                url: "scripts/php/delete_room_type.php", // Replace with your PHP script URL for deleting room types
                type: "POST",
                data: { room_type_id: roomTypeId },
                success: function(response) {
                    // Handle the response (e.g., display a success message)
                    alert("Room type deleted successfully!");
                    // Optionally, refresh the room type list on your page
                    refreshTable();
                },
                error: function(xhr, status, error) {
                    // Handle errors (e.g., display an error message)
                    alert("Error deleting room type: " + error);
                    console.log(error);
                }
            });
        }
    });
</script>



</body>
</html>
