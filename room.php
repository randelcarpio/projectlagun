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
    <h2>Room Table</h2>

    <!-- Table element with the "table" and "table-bordered" classes -->
    <div id="toolbar">
      <button id="add_new" class="btn btn-success">Add New</button>
    </div>
    <table id="roomTable" class="table table-bordered"
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
                <th data-field="room_id" data-sortable="true">Room ID</th>
                <th data-field="room_number" data-sortable="true">Room Number</th>
                <th data-field="room_status" data-filter-control="select">Availability Status</th>
                <th data-field="room_type" data-filter-control="select">Room Type Name</th>
                <th data-field="actions">Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be loaded here using Ajax -->
        </tbody>
    </table>
    
    
    
    <!-- Modal to add a new room -->
     <div id="addRoomModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add a New Room</h2>
            <form id="addRoomForm">
                <!-- Form fields go here -->
                <div class="form-group">
                    <label for="room_no">Room Number:</label>
                    <input type="text" class="form-control" id="room_no" name="room_no" required>
                </div>

                <div class="form-group">
                    <label for="availability_status">Availability Status:</label>
                    <select class="form-control" id="availability_status" name="availability_status" required>
                        <option value="available">Available</option>
                        <option value="booked">Booked</option>
                        <option value="under maintenance">Under Maintenance</option>
                    </select>
                </div>

                <div class="form-group">
                    <select class="form-control" id="edit_room_type" name="edit_room_type" required>
                        <!-- Options will be dynamically populated here using JavaScript -->
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Add Room</button>
            </form>
        </div>
    </div>
    
    
    <!-- Modal to add a edit room -->
     <div id="editRoomModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Room</h2>
            <form id="editRoomForm">
                <!-- Form fields go here -->
                <div class="form-group">
                    <label for="edit_room_no">Room Number:</label>
                    <input type="text" class="form-control" id="edit_room_no" name="edit_room_no" required>
                </div>

                <div class="form-group">
                    <label for="edit_availability_status">Availability Status:</label>
                    <select class="form-control" id="edit_availability_status" name="edit_availability_status" required>
                        <option value="available">Available</option>
                        <option value="booked">Booked</option>
                        <option value="under maintenance">Under Maintenance</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="room_type">Room Type:</label>
                    <select class="form-control" id="room_type" name="room_type" required>
                        <!-- Options will be dynamically populated here using JavaScript -->
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
    
    <!-- Modal to Delete Room -->
    <div id="confirmDeleteModal" class="modal" style="display: none; height:auto;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete</h2>
            <div class="modal-body">
                <p>Are you sure you want to delete this room? This action cannot be undone.</p>
            </div>
            <div class="modal-footer text-center">
                <button id="confirmDeleteButton" class="btn btn-danger">Delete</button>
                <button id="cancelDeleteButton" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>


    
</div>

<!-- Scripts For All JS Scripts -->   
<?php require('scripts/php/lib_link_bt.php') ?>

<!-- Your custom JavaScript for loading data -->
<script>
    $(document).ready(function() {
        // Using Ajax to fetch data from PHP script
        $.ajax({
            url: 'scripts/php/fetch_rooms.php', // Replace with the path to your PHP script
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                
                // Create an empty array to store table rows
                var tableRows = [];

                // Iterate through the data and prepare rows
                $.each(data, function(index, room) {
                    var row = {
                        room_id: room.room_id,
                        room_number: room.room_no, // Updated key to match data-field
                        room_status: room.availability_status, // Updated key to match data-field
                        room_type: room.room_type_name, // Updated key to match data-field
                        actions: '<div style="text-align: center">' +
                            '<button class="btn btn-primary edit-button" data-room-id="' + room.room_id + '"><i class="fas fa-edit"></i></button> ' +
                            '<button class="btn btn-danger delete-button" data-room-id="' + room.room_id + '"><i class="fas fa-trash-alt"></i></button> ' +
                        '</div>'


                    };
                    tableRows.push(row);
                });

                // Append the rows to the table using bootstrapTable('append', data)
                $('#roomTable').bootstrapTable('append', tableRows);


            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Ajax Error: ' + textStatus + ' - ' + errorThrown);
            }

        });
        
        // Initialize Table as Bootstrap-Table
        $('#sampleTable').bootstrapTable();
        
    });
    
    
</script>

<script>
    // After making changes to the database, call this function to refresh the table.
    function refreshTable() {
        // Destroy the existing bootstrap table
        $('#roomTable').bootstrapTable('destroy');

        // Load the data from your PHP script again and update the table.
        $.ajax({
            url: 'scripts/php/fetch_rooms.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Create an empty array to store table rows
                var tableRows = [];

                // Iterate through the data and prepare rows
                $.each(data, function(index, room) {
                    var row = {
                        room_id: room.room_id,
                        room_number: room.room_no, // Updated key to match data-field
                        room_status: room.availability_status, // Updated key to match data-field
                        room_type: room.room_type_name, // Updated key to match data-field
                        actions: '<div style="text-align: center">' +
                            '<button class="btn btn-primary edit-button" data-room-id="' + room.room_id + '"><i class="fas fa-edit"></i></button> ' +
                            '<button class="btn btn-danger delete-button" data-room-id="' + room.room_id + '"><i class="fas fa-trash-alt"></i></button> ' +
                        '</div>'
                    };
                    tableRows.push(row);
                });

                // Initialize the bootstrap table with the new data
                $('#roomTable').bootstrapTable({data: tableRows});
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Ajax Error: ' + textStatus + ' - ' + errorThrown);
            }
        });
    }
</script>

<!--Script to Insert New Data using the Modal-->
<script>
$(document).ready(function() {
    // Open the modal when the "Add New" button is clicked
    $("#add_new").click(function() {
        $("#addRoomModal").css("display", "block");
    });

    // Close the modal when the close button is clicked
    $(".close").click(function() {
        $("#addRoomModal").css("display", "none");
    });

    // Submit the form via AJAX
    $("#addRoomForm").submit(function(event) {
        event.preventDefault();

        // Gather form data
        var formData = $(this).serialize();

        $.ajax({
            url: "scripts/php/add_rooms.php", // Replace with your PHP script URL
            type: "POST",
            data: formData,
            success: function(response) {
                // Handle the response (e.g., display a success message)
                alert("Room added successfully!");
                // Close the modal
                $("#addRoomModal").css("display", "none");
                // Optionally, refresh the room list on your page
                refreshTable();
            },
            error: function(xhr, status, error) {
                // Handle errors (e.g., display an error message)
                alert("Error adding room: " + error);
                console.log(error);
            }
        });
    });
});
</script>
    
    
<script>
$(document).ready(function() {
    
    //Global Variable
    var roomId;
    
    // Open the modal when the "Edit" button is clicked
    $(document).on("click", ".edit-button", function() {
        roomId = $(this).data("room-id");

        // Send an AJAX request to fetch room details by room_id
        $.ajax({
            url: 'scripts/php/fetch_rooms.php', // Replace with the path to your PHP script
            type: 'GET',
            data: { room_id: roomId }, // Pass the room_id parameter
            dataType: 'json',
            success: function(data) {
                // Check if data is not empty and does not contain a "No Records Found" message
                if (data && data.room_id !== "No Records Found") {
                    // Populate the edit modal form fields with room data
                    //$("#editRoomForm input[name='room_id']").val(data.room_id);
                    $("#editRoomForm input[name='edit_room_no']").val(data.room_no);
                    $("#editRoomForm select[name='edit_availability_status']").val(data.availability_status);
                    // Get the original room type ID
                    var originalRoomTypeID = data.room_type_id;

                    // Select the option that matches the original room type ID
                    $("#edit_room_type").val(originalRoomTypeID);

                    // Display the edit modal
                    $("#editRoomModal").css("display", "block");
                    
                    refreshTable();
                    
                } else {
                    // Handle errors or display an error message
                    alert("Error fetching room details: Room not found.");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Ajax Error: ' + textStatus + ' - ' + errorThrown);
            }
        });
    });

    // Close the modal when the close button is clicked
    $(".close").click(function() {
        $("#editRoomModal").css("display", "none");
    });

    // Submit the edit form via AJAX
    $("#editRoomForm").submit(function(event) {
        event.preventDefault();

        // Gather form data
        var formData = $(this).serialize();
        // Add the room ID to the form data
        formData += '&room_id=' + roomId;

        $.ajax({
            url: "scripts/php/edit_rooms.php", // Replace with your PHP script URL for editing
            type: "POST",
            data: formData,
            success: function(response) {
                // Handle the response (e.g., display a success message)
                alert("Room edited successfully!");
                // Close the edit modal
                $("#editRoomModal").css("display", "none");
                // Optionally, refresh the room list on your page
            },
            error: function(xhr, status, error) {
                // Handle errors (e.g., display an error message)
                alert("Error editing room: " + error);
                console.log(error);
            } 
        });
    });
});
</script>

<script>   
$(document).ready(function() {
    
    $(document).on("click", ".delete-button", function() {
        // Store the room ID in a data attribute of the confirm button
        $("#confirmDeleteButton").data("room-id", $(this).data("room-id"));
        // Show the confirmation modal
        $("#confirmDeleteModal").css("display", "block");
    });
    
    $("#confirmDeleteButton").click(function() {
        // Get the room ID from the confirm button's data attribute
        var roomId = $(this).data("room-id");
        // Delete the room
        deleteRoom(roomId);
        // Hide the confirmation modal
        $("#confirmDeleteModal").css("display", "none");
        // Refresh the table
        refreshTable();
        $('#roomTable').bootstrapTable('refresh');
    });

    $("#cancelDeleteButton").click(function() {
        // Hide the confirmation modal without deleting anything
        $("#confirmDeleteModal").css("display", "none");
    });


    function deleteRoom(roomId) {
        $.ajax({
            url: "scripts/php/delete_rooms.php", // Replace with your PHP script URL for deletion
            type: "POST",
            data: { room_id: roomId },
            success: function(response) {
                // Handle the response (e.g., display a success message)
                alert("Room deleted successfully!");
                // Optionally, refresh the room list on your page
            },
            error: function(xhr, status, error) {
                // Handle errors (e.g., display an error message)
                alert("Error deleting room: " + error);
                console.log(error);
            } 
        });
    }
    
});    
    
    
    
// Function to populate room type dropdown options
function populateRoomTypeOptions() {
    // Send an AJAX request to fetch room types
    $.ajax({
        url: 'scripts/php/fetch_room_types.php', // Create a new PHP script to fetch room types
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            // Populate the add and edit room type dropdowns with options
            $.each(data, function(index, roomType) {
                // Use room type ID as the option value
                $('#room_type').append('<option value="' + roomType.room_type_id + '">' + roomType.room_type_name + '</option>');
                $('#edit_room_type').append('<option value="' + roomType.room_type_id + '">' + roomType.room_type_name + '</option>');
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('Ajax Error: ' + textStatus + ' - ' + errorThrown);
        }
    });
}

// Call the function to populate room type options when the page is ready
$(document).ready(function() {
    populateRoomTypeOptions();
});


</script>


</body>
</html>
