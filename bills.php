<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bills Table</title>

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
        <h2>Bills Table</h2>
        <!-- Table element for bills data -->
        <table id="billTable" class="table table-bordered" 
               data-toggle="table" data-search="true" 
               data-show-export="true" 
               data-pagination="true" 
               data-sortable="true" 
               data-filter-control="true" 
               data-show-toggle="true" 
               data-show-columns="true" 
               data-export-types="['json', 'xml', 'csv', 'txt', 'sql', 'excel', 'pdf']">
            <thead>
                <tr>
                    <th data-field="bill_id" data-sortable="true">Bill ID</th>
                    <th data-field="booking_id" data-sortable="true">Booking ID</th>
                    <th data-field="total_amount" data-sortable="true">Total Amount</th>
                    <th data-field="total_paid" data-sortable="true">Total Paid</th>
                    <th data-field="status" data-filter-control="select">Status</th>
                    <th data-field="guest_name" data-sortable="true">Guest Name</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data will be loaded here using Ajax -->
            </tbody>
        </table>
    </div>

    <!-- Include JavaScript libraries and custom scripts -->
    <?php require('scripts/php/lib_link_bt.php') ?>
    <script>
        // FILL UP THE TABLE
        function refreshBillTable() {
            // Clear the contents of the bill table
            $('#billTable').bootstrapTable('removeAll');

            // Fetch bill data using Ajax
            $.ajax({
                url: 'scripts/php/fetch_bills.php', // Replace with the path to your PHP script
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    // Create an empty array to store table rows
                    var tableRows = [];

                    // Iterate through the data and prepare rows
                    $.each(data, function (index, bill) {
                        var row = {
                            bill_id: bill.bill_id,
                            booking_id: bill.booking_id,
                            total_amount: bill.total_amount,
                            total_paid: bill.total_paid,
                            status: bill.status,
                            guest_name: bill.guest_name,
                        };

                        tableRows.push(row);
                    });

                    // Update the bootstrap table with the new data
                    $('#billTable').bootstrapTable('load', tableRows);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('Ajax Error: ' + textStatus + ' - ' + errorThrown);
                }
            });
        }

        $(document).ready(function () {
            // Call the function to load bill data when the page is ready
            // Initialize the bootstrap table first
            $('#billTable').bootstrapTable();
            refreshBillTable();
        });

    </script>
</body>

</html>
