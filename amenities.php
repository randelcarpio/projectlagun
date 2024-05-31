<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amenities Table</title>
    
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
    <h2>Amenities Table</h2>

    <!-- Table element with the necessary attributes -->
    <div id="toolbar">
      <button id="add_new" class="btn btn-success">Add New</button>
    </div>
    <table id="amenitiesTable" class="table table-bordered"
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
                <th data-field="amenity_id" data-sortable="true">Amenity ID</th>
                <th data-field="room_number" data-sortable="true">Room Number</th>
                <th data-field="amenity_name" data-sortable="true">Amenity Name</th>
                <th data-field="description" data-sortable="true">Description</th>
                <th data-field="category" data-filter-control="select">Category</th>
                <th data-field="icon" data-sortable="true">Icon</th>
                <th data-field="is_available" data-filter-control="select">Is Available</th>
                <th data-field="actions">Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be loaded here using Ajax -->
        </tbody>
    </table>
    
    <!-- Modal to add a new amenity -->
    <!-- Modal to add a new amenity -->
    <div id="addAmenityModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add a New Amenity</h2>
            <form id="addAmenityForm">
                <!-- Form fields go here -->
                <div class="form-group">
                    <label for="room_number">Room Number:</label>
                    <input type="text" class="form-control" id="room_number" name="room_number">
                </div>

                <div class="form-group">
                    <label for="amenity_name">Amenity Name:</label>
                    <input type="text" class="form-control" id="amenity_name" name="amenity_name" required>
                </div>

                <div class="form-group">
                    <label for="description">Description:</label>
                    <input type="text" class="form-control" id="description" name="description" required>
                </div>

                <div class="form-group">
                    <label for="category">Category:</label>
                    <select class="form-control" id="category" name="category" required>
                        <option value="Property/Hotel Amenities">Property/Hotel Amenities</option>
                        <option value="Room Amenities">Room Amenities</option>
                        <option value="Room Features">Room Features</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="icon">Icon:</label>
                    <input type="text" class="form-control" id="icon" name="icon">
                </div>

                <div class="form-group">
                    <label for="is_available">Is Available:</label>
                    <select class="form-control" id="is_available" name="is_available" required>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Add Amenity</button>
            </form>
        </div>
    </div>


    <!-- Modal to edit an amenity -->
    <!-- Modal to edit an amenity -->
    <div id="editAmenityModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Amenity</h2>
            <form id="editAmenityForm">
                <!-- Form fields go here -->
                <input type="hidden" id="edit_amenity_id" name="edit_amenity_id" value="">
                <div class="form-group">
                    <label for="edit_room_number">Room Number:</label>
                    <input type="text" class="form-control" id="edit_room_number" name="edit_room_number">
                </div>

                <div class="form-group">
                    <label for="edit_amenity_name">Amenity Name:</label>
                    <input type="text" class="form-control" id="edit_amenity_name" name="edit_amenity_name" required>
                </div>

                <div class="form-group">
                    <label for="edit_description">Description:</label>
                    <input type="text" class="form-control" id="edit_description" name="edit_description" required>
                </div>

                <div class="form-group">
                    <label for="edit_category">Category:</label>
                    <select class="form-control" id="edit_category" name="edit_category" required>
                        <option value="Property/Hotel Amenities">Property/Hotel Amenities</option>
                        <option value="Room Amenities">Room Amenities</option>
                        <option value="Room Features">Room Features</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="edit_icon">Icon:</label>
                    <input type="text" class="form-control" id="edit_icon" name="edit_icon">
                </div>

                <div class="form-group">
                    <label for="edit_is_available">Is Available:</label>
                    <select class="form-control" id="edit_is_available" name="edit_is_available" required>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>

    
    <!-- Modal to delete an amenity -->
    <!-- Modal to delete an amenity -->
    <div id="confirmDeleteAmenityModal" class="modal" style="display: none; height:auto;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 class="modal-title" id="confirmDeleteAmenityModalLabel">Confirm Delete</h2>
            <div class="modal-body">
                <p>Are you sure you want to delete this amenity? This action cannot be undone.</p>
            </div>
            <div class="modal-footer text-center">
                <button id="confirmDeleteAmenityButton" class="btn btn-danger">Delete</button>
                <button id="cancelDeleteAmenityButton" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>


</div>

<!-- Include JS scripts here -->
<!-- Scripts For All JS Scripts -->   
<?php require('scripts/php/lib_link_bt.php') ?>
<script>
    var amenityId; // Define a variable to store the selected amenity ID

    // Open the add amenity modal when the "Add New Amenity" button is clicked
    $("#add_new").click(function() {
        $("#addAmenityModal").css("display", "block");
    });

    // Close the add amenity modal when the close button is clicked
    $(".close").click(function() {
        $("#addAmenityModal").css("display", "none");
    });

    // Submit the add amenity form via AJAX
    $("#addAmenityForm").submit(function(event) {
        event.preventDefault();

        // Gather form data
        var formData = $(this).serialize();

        $.ajax({
            url: "scripts/php/add_amenities.php", // Replace with your PHP script URL for adding amenities
            type: "POST",
            data: formData,
            success: function(response) {
                // Handle the response (e.g., display a success message)
                alert("Amenity added successfully!");
                // Close the add amenity modal
                clearForm('addAmenityForm');
                $("#addAmenityModal").css("display", "none");
                // Optionally, refresh the amenity table on your page
                refreshAmenityTable();
            },
            error: function(xhr, status, error) {
                // Handle errors (e.g., display an error message)
                alert("Error adding amenity: " + error);
                console.log(error);
            }
        });
    });

    // Open the edit amenity modal when an "Edit" button is clicked
    $(document).on("click", ".edit-button", function() {
        amenityId = $(this).data("amenity-id");

        // Send an AJAX request to fetch amenity details by amenity_id
        $.ajax({
            url: 'scripts/php/fetch_amenities.php', // Replace with the path to your PHP script
            type: 'GET',
            data: { amenity_id: amenityId }, // Pass the amenity_id parameter
            dataType: 'json',
            success: function(data) {
                // Check if data is not empty
                if (data) {
                    // Populate the edit modal form fields with amenity data
                    $("#editAmenityForm input[name='edit_amenity_name']").val(data.amenity_name);
                    $("#editAmenityForm input[name='edit_description']").val(data.description);
                    $("#editAmenityForm select[name='edit_category']").val(data.category);
                    $("#editAmenityForm input[name='edit_icon']").val(data.icon);
                    $("#editAmenityForm select[name='edit_is_available']").val(data.is_available);

                    // Display the edit amenity modal
                    $("#editAmenityModal").css("display", "block");
                } else {
                    // Handle errors or display an error message
                    alert("Error fetching amenity details: Amenity not found.");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Ajax Error: ' + textStatus + ' - ' + errorThrown);
            }
        });
    });

    // Close the edit amenity modal when the close button is clicked
    $(".close").click(function() {
        $("#editAmenityModal").css("display", "none");
    });

    // Submit the edit amenity form via AJAX
    $("#editAmenityForm").submit(function(event) {
        event.preventDefault();

        // Gather form data
        var formData = $(this).serialize();
        // Add the amenity ID to the form data
        formData += '&amenity_id=' + amenityId;

        $.ajax({
            url: "scripts/php/edit_amenities.php", // Replace with your PHP script URL for editing amenities
            type: "POST",
            data: formData,
            success: function(response) {
                // Handle the response (e.g., display a success message)
                alert("Amenity edited successfully!");
                // Close the edit amenity modal
                clearForm('editAmenityForm');
                $("#editAmenityModal").css("display", "none");
                // Optionally, refresh the amenity table on your page
                refreshAmenityTable();
            },
            error: function(xhr, status, error) {
                // Handle errors (e.g., display an error message)
                alert("Error editing amenity: " + error);
                console.log(error);
            }
        });
    });

    // Open the confirmation modal when a "Delete" button is clicked
    $(document).on("click", ".delete-button", function() {
        // Store the amenity ID in a data attribute of the confirm button
        $("#confirmDeleteAmenityButton").data("amenity-id", $(this).data("amenity-id"));
        // Show the confirmation modal
        $("#confirmDeleteAmenityModal").css("display", "block");
    });

    $("#confirmDeleteAmenityButton").click(function() {
        // Get the amenity ID from the confirm button's data attribute
        amenityId = $(this).data("amenity-id");
        // Delete the amenity
        deleteAmenity(amenityId);
        // Hide the confirmation modal
        $("#confirmDeleteAmenityModal").css("display", "none");
        // Refresh the amenity table
        refreshAmenityTable();
    });

    $("#cancelDeleteAmenityButton").click(function() {
        // Hide the confirmation modal without deleting anything
        $("#confirmDeleteAmenityModal").css("display", "none");
    });

    function deleteAmenity(amenityId) {
        $.ajax({
            url: "scripts/php/delete_amenities.php", // Replace with your PHP script URL for deleting amenities
            type: "POST",
            data: { amenity_id: amenityId },
            success: function(response) {
                // Handle the response (e.g., display a success message)
                alert("Amenity deleted successfully!");
                // Optionally, refresh the amenity list on your page
                refreshAmenityTable();
            },
            error: function(xhr, status, error) {
                // Handle errors (e.g., display an error message)
                alert("Error deleting amenity: " + error);
                console.log(error);
            }
        });
    }

    // FILL UP THE TABLE
    function refreshAmenityTable() {
        // Clear the contents of the amenity table
        $('#amenitiesTable').bootstrapTable('removeAll');

        // Fetch amenity data using Ajax
        $.ajax({
            url: 'scripts/php/fetch_amenities.php', // Replace with the path to your PHP script
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Create an empty array to store table rows
                var tableRows = [];

                // Iterate through the data and prepare rows
                $.each(data, function(index, amenity) {
                    var row = {
                        amenity_id: amenity.amenity_id,
                        room_number: amenity.room_number,
                        amenity_name: amenity.amenity_name,
                        description: amenity.description,
                        category: amenity.category,
                        icon: amenity.icon,
                        is_available: amenity.is_available
                    };

                    // Add action buttons
                    row.actions = '<div style="text-align: center">' +
                        '<button class="btn btn-primary edit-button" data-amenity-id="' + amenity.amenity_id + '"><i class="fas fa-edit"></i></button> ' +
                        '<button class="btn btn-danger delete-button" data-amenity-id="' + amenity.amenity_id + '"><i class="fas fa-trash-alt"></i></button> ' +
                        '</div>';

                    tableRows.push(row);
                });

                // Update the bootstrap table with the new data
                $('#amenitiesTable').bootstrapTable('load', tableRows);
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
        // Initialize the bootstrap table and call the function to load amenity data when the page is ready
        $('#amenitiesTable').bootstrapTable();
        refreshAmenityTable();

        clearForm('addAmenityForm');
        clearForm('editAmenityForm');

        // Other initialization code can go here if needed
    });
</script>



</body>
</html>
