<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking</title>
    
    <!-- Include Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../../libs/fontawesome-free-6.4.2-web/css/all.css">

    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>


    <!-- Include Bootstrap Table CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.22.1/bootstrap-table.min.css">
    
    <!-- Include Modal CSS -->
    <link rel="stylesheet" href="css/modal_wide.css">
    
    <!-- Google reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js?render=6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></script>

    
    <style>
        .modal-content {
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
        }

        #roomImagesContainer {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        #roomTypeName {
            color: #007bff;
        }

        #book-room-button {
            margin-top: 20px;
        }


    </style>
    
    <style>
    .rounded-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-start; /* Align items to the start of the container */
    }

    .rounded-item {
        border: 1px solid #ccc;
        border-radius: 50px; /* This will make the items rounded like pills */
        padding: 10px;
        margin: 5px;
        display: inline-flex; /* This will make the items take up only as much space as they need */
    }


    </style>
    
    <style>
        .img-thumbnail {
        object-fit: cover;
        width: 100px;
        height: 100px;
    }

    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.css" integrity="sha512-Woz+DqWYJ51bpVk5Fv0yES/edIMXjj3Ynda+KWTIkGoynAMHrqTcDUQltbipuiaD5ymEo9520lyoVOo9jCQOCA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js" integrity="sha512-Ixzuzfxv1EqafeQlTCufWfaC6ful6WFqIz4G+dWvK0beHw0NVJwvCKSgafpy5gwNqKmgUfIBraVwkKI+Cz0SEQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox-plus-jquery.min.js" integrity="sha512-U9dKDqsXAE11UA9kZ0XKFyZ2gQCj+3AwZdBMni7yXSvWqLFEj8C1s7wRmWl9iyij8d5zb4wm56j4z/JVEwS77g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</head>
    
<body>
    <div id="loadingSpinner" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);">
      <div class="spinner-border text-primary" role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </div>

    <div class="container mt-3 card">
        <form class="row">
            <!-- Check In Date Field -->
            <div class="form-group col-sm-4">
                <label for="check-in-date" class="d-block">Check In:</label>
                <input type="date" class="form-control" id="check-in-date" value="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
            </div>

            <!-- Check Out Date Field -->
            <div class="form-group col-sm-4">
                <label for="check-out-date" class="d-block">Check Out:</label>
                <input type="date" class="form-control" id="check-out-date" value="<?php echo date('Y-m-d', strtotime('+2 days')); ?>" min="">
            </div>

            <div class="col-sm-4 d-flex align-items-center">
                <button type="button" class="btn btn-primary w-100" id="find-rooms-button">Find Rooms</button>
            </div>
        </form>
    </div>






    
    <div class="container mt-5">
        <div class="row" id="roomCardsContainer">
            <!-- Room cards will be added here via JavaScript -->
        </div>
    </div>
    
    <!-- Modal for Room Details -->
    <!-- Modal for Room Details -->
    <div id="roomDetailsModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">×</span>
            <h4>Room Details</h4>
            <div class="row">
                <!-- Room Images Container -->
                <div class="col-12" id="third" style="overflow: auto; max-height: 70vh;">
                    <div id="roomImagesContainer">
                        <!-- Images will be added dynamically here -->
                    </div>
                </div>
                <!-- Room Details -->
                <div class="col-12" style="overflow: auto; max-height: 70vh;">
                    <h4 id="roomTypeName"></h4>
                    <p id="roomDescription"></p>
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Price</h5>
                            <p class="card-text">₱<span id="roomPrice"></span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <p>Max Adults: <span id="maxAdults"></span></p>
                        </div>
                        <div class="col-sm-6">
                            <p>Max Kids: <span id="maxKids"></span></p>
                        </div>
                    </div>
                    <div class="card text-center" id="amenities">
                        <div class="card-body"></div>
                    </div>
                </div>
            </div>
            <button id="book-room-button" class="btn btn-primary">Book Room</button>
        </div>
    </div>


    <!-- Modal Backdrop -->
    <div class="modal-backdrop" style="display: none;"></div>
    
    <div id="selected-rooms-container" class="container mt-5 card p-3 bg-dark text-light" style="display: none;">
        <h3 class="card-title">Selected Rooms</h3>
        <!-- Selected rooms will appear here -->
        <div id="selected-rooms-container-row" class="row"></div>
    </div>
    
    <!-- Guest Information Form -->
    <!-- Guest Information Form -->
    <div id="guest-information-container" class="container mt-5 card p-3 bg-dark text-white" style="display: none;">
        <div class="row">
            <div class="col-md-6">
                <h3 class="card-title">Guest Information</h3>
                <form id="guest-information">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="firstName" class="text-white">First Name</label>
                            <input type="text" class="form-control" id="firstName" placeholder="First Name">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="lastName" class="text-white">Last Name</label>
                            <input type="text" class="form-control" id="lastName" placeholder="Last Name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="phoneNumber" class="text-white">Phone Number</label>
                        <input type="tel" class="form-control" id="phoneNumber" placeholder="Phone Number">
                    </div>
                    <div class="form-group">
                        <label for="email" class="text-white">Email Address</label>
                        <input type="email" class="form-control" id="email" placeholder="Email Address">
                    </div>
                    <div class="form-group">
                        <input type="checkbox" id="terms" name="terms">
                        <span for="terms" style="text-align: justify">By proceeding with this booking, I acknowledge that I have read, understood, and agree to the Terms and Conditions and Data Privacy Policy of this hotel. I consent to the collection, use, and disclosure of my personal information in accordance with these policies.</span>
                    </div>
                    <br>
                    <input type="hidden" id="recaptchaResponse" name="recaptchaResponse">
                    <button type="submit" class="btn btn-block btn-primary" disabled>Proceed to Booking</button>
                </form>
            </div>
            <div class="col-md-6">
                <!-- Receipt/Invoice content goes here -->
                <h3 class="card-title">Summary</h3>
                <div id="receipt" style="background-color: white; color: black; font-family: 'Monospace'; padding: 10px;">
                    <!-- You can dynamically add content here -->
                </div>
            </div>
        </div>
    </div>
    
    

    

    
    <!-- Scripts For All JS Scripts -->
    <?php require('scripts/php/lib_link_bt.php') ?>
    
    <!-- Include Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+JwBzcy7uf3VG7pHsoXz8jOG2WBf/xUj6Ag5Uks5k9OXSnx0" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
    
    <!-- Custom Scripts for this Page -->
    <script src="scripts/js/select_room.js"></script>
    <script src="scripts/js/input_validation.js"></script>
</body>
</html>
