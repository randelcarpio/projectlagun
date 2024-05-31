<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Include necessary CSS and JavaScript libraries -->
    
    <!-- Include Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" integrity="sha512-oAvZuuYVzkcTc2dH5z1ZJup5OmSQ000qlfRvuoTTiyTBjwX1faoyearj8KdMq0LgsBTHMrRuMek7s+CxF8yE+w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Include Bootstrap Table CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.22.1/bootstrap-table.min.css">
    
    <!-- Include Modal CSS -->
    <link rel="stylesheet" href="css/modal_full.css">
    
</head>
<body>
<?php
    require 'scripts/php/check_access.php'; // Path to your check_access.php file
    checkAccessLevel(['super_admin', 'admin', 'front_desk']);
?>
<div class="container mt-5">
    <h2>Bookings Table</h2>

    <!-- Table element with the "table" and "table-bordered" classes -->
    <div id="toolbar">
        <button id="add_new" class="btn btn-success">Create Booking</button>
    </div>
    <table id="bookingTable" class="table table-bordered"
           data-toggle="table"
           data-toolbar="#toolbar"
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
                <th data-field="booking_id" data-sortable="true">Booking ID</th>
                <th data-field="customer_name" data-sortable="true">Customer Name</th>
                <th data-field="check_in_date" data-sortable="true">Check-In Date</th>
                <th data-field="check_out_date" data-sortable="true">Check-Out Date</th>
                <th data-field="status" data-filter-control="select">Status</th>
                <th data-field="actions">Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be loaded here using Ajax -->
        </tbody>
    </table>
    
    
    
    
    
    <!-- START OF MODAL -->
    
    <!-- Modal to view a booking -->
    <div id="viewBookingModal" class="modal" style="display: none;">
        <div class="modal-header">
            <span class="close">×</span>
            <h2>View Booking</h2>
        </div>
        <div class="modal-content">
            <!-- Dropdown for booking status -->
            <form id="updateBookingStatusForm">
                <div class="form-group row">
                    <label for="bookingStatus" class="col-sm-2 col-form-label">Booking Status</label>
                    <div class="col-sm-8">
                        <select class="form-control" id="bookingStatus">
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="cheked in">Checked In</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="with balance">With Balance</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>


            <!-- Booking details table -->
            <div class="row" style="margin-top: 20px; margin-bottom: 20px;">
                <div class="col-md-12">
                    <h3>Booking Details</h3>
                    <table id="bookingDetailsTable" class="table table-bordered"
                        data-toggle="table"
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
                                <th data-field="room_id" data-sortable="true">Room Number</th>
                                <th data-field="room_type_name" data-filter-control="select">Room Type</th>
                                <th data-field="number_of_adults" data-sortable="true">Number of Adults</th>
                                <th data-field="number_of_children" data-sortable="true">Number of Children</th>
                                <th data-field="actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded here using Ajax -->
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Form to Add Rooms -->
            <h4>Add Rooms</h4>
            <form id="editBookingDetailsForm" class="form-row">
                <div class="form-group col-md-3">
                    <label for="editRoomType">Room Type</label>
                    <select class="form-control" id="editRoomType" required>
                        <!-- Add room types here -->
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="editRoomId">Room ID</label>
                    <select class="form-control" id="editRoomId" required>
                        <!-- Add room IDs here -->
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="editNumberOfAdults">Number of Adults</label>
                    <select class="form-control" id="editNumberOfAdults" required>
                        <!-- Add number of adults options here -->
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="editNumberOfChildren">Number of Children</label>
                    <select class="form-control" id="editNumberOfChildren" required>
                        <!-- Add number of children options here -->
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <span>Total Room Price: ₱<span id="newRoomPrice">0</span> <span id="newRoomItem"></span></span>
                </div>
                <div class="form-group col-md-3">
                    <button type="submit" class="btn btn-primary">Add Room</button>
                </div>
            </form>



            <!-- Billing items and Payments tables -->
            <div class="row" style="margin-top: 20px; margin-bottom: 20px;">
                <!-- Billing items table -->
                <div class="col-md-6">
                    <h3>Billing Items</h3>
                    <table id="billingItemsTable" class="table table-bordered"
                        data-toggle="table"
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
                                <th data-field="billing_item_id" data-sortable="true">Billing Item ID</th>
                                <th data-field="description" data-sortable="true">Description</th>
                                <th data-field="price" data-sortable="true">Price</th>
                                <th data-field="room_id" data-sortable="true">Room Number</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded here using Ajax -->
                        </tbody>
                    </table>
                    <!-- Balance card -->
                    <div class="card mb-3" style="padding: 2px; margin-top: 2px; margin-bottom: 20px;">
                        <div class="card-body" style="padding: 5px;">
                            <h5 class="card-title">Balance: ₱<span id="balance">0</span></h5>
                        </div>
                    </div>
                    <!-- Form for adding new billing items -->
                    <form id="addBillingItemForm" class="form-row" style="margin-top: 5px">
                        <div class="form-group col-md-6">
                            <label for="description">Description</label>
                            <input type="text" class="form-control" id="description" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="price">Price</label>
                            <input type="text" class="form-control" id="price" required pattern="^-?\d*(\.\d{0,2})?$">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="description">Room Number</label>
                            <input type="text" class="form-control" id="room_number" required>
                        </div>
                        <div class="form-group col-md-2">
                            <button type="submit" class="btn btn-primary" style="margin-top: 30px;">Add</button>
                        </div>
                    </form>
                </div>

                <!-- Payments table -->
                <div class="col-md-6">
                    <h3>Payments</h3>
                    <table id="paymentsTable" class="table table-bordered"
                        data-toggle="table"
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
                                <th data-field="payment_id" data-sortable="true">Payment ID</th>
                                <th data-field="amount" data-sortable="true">Amount</th>
                                <th data-field="payment_method" data-filter-control="select">Payment Method</th>
                                <th data-field="status">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded here using Ajax -->
                        </tbody>
                    </table>
                    <!-- Balance card -->
                    <div class="card mb-3" style="padding: 2px; margin-top: 2px; margin-bottom: 20px;">
                        <div class="card-body" style="padding: 5px;">
                            <h5 class="card-title">Total Paid: ₱<span id="paid">0</span></h5>
                        </div>
                    </div>
                    <!-- Form for adding new payments -->
                    <form id="addPaymentForm" class="form-row" style="margin-top: 15px">
                        <div class="form-group col-md-6">
                            <label for="amount">Amount</label>
                            <input type="text" class="form-control" id="amount" required pattern="^-?\d*(\.\d{0,2})?$">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="paymentMethod">Payment Method</label>
                            <input type="text" class="form-control" id="paymentMethod" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="status">Description</label>
                            <input type="text" class="form-control" id="status">
                        </div>
                        <div class="form-group col-md-2">
                            <button type="submit" class="btn btn-primary" style="margin-top: 30px;">Add</button>
                        </div>
                    </form>
                </div>
                <!-- Add Extras -->
                <!-- 
                // REMOVED 
                <div class="col-md-12" style="margin-top: 20px">
                    <h3>Add Extra Items</h3>
                    <!-- Form for adding new payments --
                    <form id="addExtrasForm" class="form-row" style="margin-top: 15px">
                        <div class="form-group col-md-6">
                            <label for="extras">Extra Items</label>
                            <select class="form-control" id="extras">
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="roomNumber">Room Number</label>
                            <input type="text" class="form-control" id="roomNumber" required>
                        </div>
                        <div class="form-group col-md-3">
                            <button type="submit" class="btn btn-primary" style="margin-top: 30px;">Add</button>
                        </div>
                    </form>
                </div>
                -->
                <div class="col-md-12" style="margin-top: 20px">
                    <h4>Uploaded Files By Customer</h4>
                    <div id="booking_files"></div>
                </div>
            </div>
        </div>
    </div>


    
    <!-- END OF MODAL -->
    
    
    
    <!-- START OF MODAL -->
    
    <!-- Modal to create a booking -->
    <!-- Modified Modal -->
    <div id="addBookingModal" class="modal" style="display: none;">
        <div class="modal-header">
            <span class="close">×</span>
            <h2>Create Booking</h2>
        </div>
        <div class="modal-content">
            <form id="newBookingForm">
                <!-- Date selectors -->
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="check_in_date">Check In Date</label>
                        <input type="date" class="form-control" id="check_in_date" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="check_out_date">Check Out Date</label>
                        <input type="date" class="form-control" id="check_out_date" value="<?php echo date('Y-m-d', strtotime('+2 day')); ?>">
                    </div>
                </div>


                <!-- Room details -->
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="room_type_dropdown">Room Type</label>
                        <select id="room_type_dropdown" class="form-control"></select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="room_number_dropdown">Room Number</label>
                        <select id="room_number_dropdown" class="form-control"></select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="adults_dropdown">Number of Adults</label>
                        <select id="adults_dropdown" class="form-control"></select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="kids_dropdown">Number of Kids</label>
                        <select id="kids_dropdown" class="form-control"></select>
                    </div>
                    <div class="form-group col-md-6">
                        <span>Total Room Price: ₱<span id="newBookingPrice">0</span> <span id="newBookingItem"></span></span>
                    </div>
                </div>

                <!-- Personal details -->
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="first_name">First Name</label>
                        <input type="text" class="form-control" id="first_name">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="last_name">Last Name</label>
                        <input type="text" class="form-control" id="last_name">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="cellphone_number">Cellphone Number</label>
                        <input type="tel" class="form-control" id="cellphone_number">
                    </div>
                </div>
                <!-- Create booking button -->
                <button type="button" id="create_booking" class="btn btn-primary">Create Booking</button>
            </form>
        </div>
    </div>



    
    <!-- END OF MODAL -->


    
</div>

<!-- Include your custom JavaScript file -->
<!-- Scripts For All JS Scripts -->   
<?php require('scripts/php/lib_link_bt.php') ?>
<script src="scripts/js/bookings.js"></script>

</body>
</html>
