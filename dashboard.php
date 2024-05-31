<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>
    <?php
        require 'scripts/php/check_access.php'; // Path to your check_access.php file
        checkAccessLevel(['super_admin', 'admin', 'front_desk']);
    ?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header">Number of Pending Bookings</div>
                    <div class="card-body">
                        <h5 class="card-title" id="pending">0</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Number of Checked In Bookings</div>
                    <div class="card-body">
                        <h5 class="card-title" id="checkedIn">0</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-header">Number of Cancelled Bookings</div>
                    <div class="card-body">
                        <h5 class="card-title" id="cancelled">0</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-white bg-info mb-3">
                    <div class="card-header">Number of Completed Bookings</div>
                    <div class="card-body">
                        <h5 class="card-title" id="completed">0</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: 'scripts/php/fetch_bookings.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var pending = 0, checkedIn = 0, cancelled = 0, completed = 0;
                    var currentMonth = new Date().getMonth() + 1;
                    data.forEach(function(booking) {
                        var checkInMonth = new Date(booking.check_in_date).getMonth() + 1;
                        if (checkInMonth === currentMonth) {
                            switch (booking.status) {
                                case 'pending':
                                    pending++;
                                    break;
                                case 'checked in':
                                    checkedIn++;
                                    break;
                                case 'cancelled':
                                    cancelled++;
                                    break;
                                case 'completed':
                                    completed++;
                                    break;
                            }
                        }
                    });
                    $('#pending').text(pending);
                    $('#checkedIn').text(checkedIn);
                    $('#cancelled').text(cancelled);
                    $('#completed').text(completed);
                }
            });
        });
    </script>
</body>
</html>
