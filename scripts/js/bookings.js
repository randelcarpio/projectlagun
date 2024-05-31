//GLOBAL VARIAVLES
var checkInDate = '';
var checkOutDate = '';
var vat = 12;

$(document).ready(function() {
    
    
    // LOAD TABLE
    refreshTable();
    
    // Function to display booking details in the "View" modal
    /*
    function displayBookingDetails(booking) {
        $("#viewBookingModal").css("display", "block");
        $("#viewBookingModal .modal-content").html(`
            <span class="close">&times;</span>
            <h2>View Booking</h2>
            <p><strong>Booking ID:</strong> ${booking.booking_id}</p>
            <p><strong>Customer Name:</strong> ${booking.customer_name}</p>
            <p><strong>Check-In Date:</strong> ${booking.check_in_date}</p>
            <p><strong>Check-Out Date:</strong> ${booking.check_out_date}</p>
            <p><strong>Status:</strong> ${booking.status}</p>
        `);
    }
    */
    

    // Function to close the "View" modal
    function closeViewModal() {
        $("#viewBookingModal").css("display", "none");
    }

    // Attach a click event to the rows of the table to open the "View" modal
    $(document).on("click", ".view-button", function() {
        var booking_id = $(this).data('booking-id');
        fillModal(booking_id);
        
    });


    // Close the "View" modal when the close button is clicked
    $(document).on("click", ".close", function() {
        closeViewModal();
    });
    
    

    
    
    // LISTENING TO FORM FOR ADDING ROOMS
    // Assuming availableRooms is defined

    // Get the form and its elements
    var roomSelectionForm = document.getElementById('editBookingDetailsForm');
    var editRoomType = document.getElementById('editRoomType');
    var editNumberOfAdults = document.getElementById('editNumberOfAdults');
    var editNumberOfChildren = document.getElementById('editNumberOfChildren');
    var newRoomPrice = document.getElementById('newRoomPrice');
    var newRoomItem = document.getElementById('newRoomItem');

    // Add event listener to the form
    roomSelectionForm.addEventListener('change', function(event) {
        // Check if the changed element is one of the select elements
        if (event.target === editRoomType || event.target === editNumberOfAdults || event.target === editNumberOfChildren) {
            calculateTotalPrice();
        }
    });

    function calculateTotalPrice() {
        // Get the selected room type
        var selectedRoomType = availableRooms.find(room => room.room_type_id == editRoomType.value);
        if (!selectedRoomType) return;  // If no room type is selected, do nothing

        // Calculate the base price
        var totalPrice = parseFloat(selectedRoomType.room_type_price);
        var roomPrice = parseFloat(selectedRoomType.room_type_price);

        // Calculate the additional price for adults
        var numberOfAdults = parseInt(editNumberOfAdults.value);
        var additionalAdults = 0;
        if (numberOfAdults > 2) {
            totalPrice += (numberOfAdults - 2) * 1500;
            additionalAdults += (numberOfAdults - 2);
        }

        // Calculate the additional price for children
        var numberOfChildren = parseInt(editNumberOfChildren.value);
        totalPrice += numberOfChildren * 750;
        
        //console.log("Check In Date: ", checkInDate);
        // Calculate the number of days between check-in and check-out
        // Calculate the number of nights
        const checkIn = new Date(checkInDate.split('/').reverse().join('-'));
        const checkOut = new Date(checkOutDate.split('/').reverse().join('-'));
        const oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
        const totalNights = Math.round(Math.abs((checkOut - checkIn) / oneDay));
        console.log("Check Out Date", checkOut);
        
        totalPrice *= totalNights;
        
        
        
        

        // Update the total price
        newRoomPrice.textContent = totalPrice.toFixed(2);
        // Update the total price
        newRoomItem.textContent = "(₱"+roomPrice.toFixed(2)+" per Day for "+totalNights+" Day(s) stay with "+additionalAdults+" Additional Adults and "+numberOfChildren+" Additional Children)";
    }

// END OF DOCUMENT READY
});


// FUNCTION TO FILL UP BOOKING TABLE
function refreshTable() {
    // Clear the table
    $('#bookingTable').bootstrapTable('removeAll');

    // Using Ajax to fetch data from PHP script
    $.ajax({
        url: 'scripts/php/fetch_bookings.php', // Replace with the path to your PHP script for fetching bookings
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            // Create an empty array to store table rows
            var tableRows = [];

            // Iterate through the data and prepare rows
            $.each(data, function(index, booking) {
                var row = {
                    booking_id: booking.booking_id,
                    customer_name: booking.customer_name,
                    check_in_date: booking.check_in_date,
                    check_out_date: booking.check_out_date,
                    status: booking.status,
                    actions: '<div style="text-align: center">' +
                        '<button class="btn btn-primary view-button" data-booking-id="' + booking.booking_id + '" data-toggle="modal" data-target="#viewBookingModal"><i class="fas fa-eye"></i></button> ' +
                    '</div>'
                };
                tableRows.push(row);
            });

            // Append the rows to the table using bootstrapTable('append', data)
            $('#bookingTable').bootstrapTable('append', tableRows);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('Ajax Error: ' + textStatus + ' - ' + errorThrown);
        }
    });
}


// FUNCTION TO FILL UP BOOKING TABLE
var selectedBooking = '';
var selectedBilling = '';
function fillModal(booking_id) {
    selectedBooking = booking_id;
    console.log("Selected Booking: ", selectedBooking);
    // Using Ajax to fetch data from PHP script
    $.ajax({
        url: 'scripts/php/fetch_booking_details.php', // Replace with the path to your PHP script for fetching booking details
        type: 'GET',
        data: { booking_id: booking_id },
        dataType: 'json',
        success: function(data) {
            // Clear the tables
            $('#bookingDetailsTable').bootstrapTable('removeAll');
            $('#billingItemsTable').bootstrapTable('removeAll');
            $('#paymentsTable').bootstrapTable('removeAll');
            
            // ASSIGN SELECTED BILL
            selectedBilling = data.billing_items[0].bill_id;
            console.log("Selected Billing: ", selectedBilling);

            // Prepare booking details rows
            var bookingDetailsRows = data.booking_details.map(function(detail) {
                return {
                    room_id: detail.room_id,
                    number_of_adults: detail.number_of_adults,
                    number_of_children: detail.number_of_children,
                    room_type_name: detail.room_type_name,
                    actions: '<div style="text-align: center">' +
                        '<button class="btn btn-danger delete-button" data-room-id="' + detail.room_id + '"><i class="fas fa-trash"></i></button> ' +
                    '</div>'
                };
            });

            // Prepare billing items rows
            var billingItemsRows = data.billing_items.map(function(item) {
                return {
                    billing_item_id: item.billing_item_id,
                    description: item.description,
                    price: item.price,
                    room_id: item.room_id
                };
            });
            
            var total = 0;

            // Loop through the billing items and add the prices
            for (var i = 0; i < data.billing_items.length; i++) {
                total += parseFloat(data.billing_items[i].price);
            }
            
            totalTaxed = total + (total * (vat/100));

            // Format the total with commas
            // Format the total with commas
            var formattedTotal = total.toFixed(2);
            var totalTaxed = totalTaxed.toFixed(2);

            // Convert to a string with commas
            formattedTotal = Number(formattedTotal).toLocaleString('en');
            totalTaxed = Number(totalTaxed).toLocaleString('en');

            // Update the balance in the HTML
            $('#balance').text(totalTaxed + " (" + formattedTotal + " + " + vat + "% tax)");



            // Prepare payments rows
            var paymentsRows = data.payments.map(function(payment) {
                return {
                    payment_id: payment.payment_id,
                    amount: payment.amount,
                    payment_method: payment.payment_method,
                    status: payment.status
                };
            });
            
            total = 0;

            // Loop through the payments and add the amounts
            for (var i = 0; i < data.payments.length; i++) {
                total += parseFloat(data.payments[i].amount);
            }
            

            // Format the total with commas
            formattedTotal = Number(total.toFixed(2)).toLocaleString('en');

            // Update the balance in the HTML
            $('#paid').text(formattedTotal);

            // Append the rows to the tables using bootstrapTable('append', data)
            $('#bookingDetailsTable').bootstrapTable('append', bookingDetailsRows);
            $('#billingItemsTable').bootstrapTable('append', billingItemsRows);
            $('#paymentsTable').bootstrapTable('append', paymentsRows);

            // Set the booking status dropdown
            $('#bookingStatus').val(data.booking.status);
            
            // ASSIGN DATES
            checkInDate = data.booking.check_in_date;
            checkOutDate = data.booking.check_out_date;
            console.log("Check In: ", checkInDate, " Check Out: ", checkOutDate)
            getRooms();

            $.ajax({
                url: 'scripts/php/get_files.php',
                type: 'GET',
                data: { folder_name: selectedBooking },
                dataType: 'json', // Add this line
                success: function(files) {
                    var html = '<div class="row">';
                    $.each(files, function(i, file) {
                        var extension = file.split('.').pop().toLowerCase();
                        var isImage = ['jpg', 'jpeg', 'png', 'gif'].indexOf(extension) > -1;
                        var isPdf = extension === 'pdf';
                        html += '<div class="col-sm-3">'; // Adjust the column size as needed
                        if (isImage) {
                            html += '<div class="thumbnail">';
                            html += '<a href="booking/' + selectedBooking + '/' + file + '" download>';
                            html += '<i class="fas fa-file-image fa-3x"></i>'; // Font Awesome image icon
                            html += '</a>';
                            html += '<div class="caption">' + file + '</div>';
                            html += '</div>';
                        } else if (isPdf) {
                            html += '<div class="thumbnail">';
                            html += '<a href="booking/' + selectedBooking + '/' + file + '" download>';
                            html += '<i class="fas fa-file-pdf fa-3x"></i>'; // Font Awesome PDF icon
                            html += '</a>';
                            html += '<div class="caption">' + file + '</div>';
                            html += '</div>';
                        }
                        html += '</div>'; // End of column
                    });
                    html += '</div>'; // End of row
                    $('#booking_files').html(html);
                }


            });



        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('Ajax Error: ' + textStatus + ' - ' + errorThrown);
        }
    });
    $("#viewBookingModal").css("display", "block");
    
    $(document).on('click', '.delete-button', function() {
        var roomId = $(this).data('room-id');
        var bookingId = selectedBooking; // Assuming you have a data-booking-id attribute

        $.ajax({
            url: 'scripts/php/delete_booking.php', // Replace with the path to your PHP script
            type: 'POST',
            data: {
                room_id: roomId,
                booking_id: bookingId
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    fillModal(selectedBooking);
                    refreshTable();
                    // You can add code here to remove the deleted item from the DOM
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('AJAX error: ' + textStatus + ' ' + errorThrown);
            }
        });
    });
    

}



// FUNCTION FOR ADDING ROOMS
let availableRooms = [];
function getRooms() {
    // Fetch the data from the PHP script
    $.ajax({
        url: 'scripts/php/fetch_available_rooms.php', // Replace with the actual PHP script URL
        method: 'GET',
        data: {
            check_in_date: checkInDate,
            check_out_date: checkOutDate
        },
        dataType: 'json',
        success: function (data) {
            availableRooms = data;
            // Populate the room type dropdown
            var roomTypeDropdown = $('#editRoomType');
            roomTypeDropdown.empty();
            roomTypeDropdown.append('<option selected="true" disabled>Choose Room Type</option>');
            roomTypeDropdown.prop('selectedIndex', 0);
            $.each(data, function(key, entry) {
                roomTypeDropdown.append($('<option></option>').attr('value', entry.room_type_id).text(entry.room_type_name));
            });

            // Update the other dropdowns when a room type is selected
            roomTypeDropdown.change(function() {
                var selectedRoomType = $(this).val();

                // Find the selected room type in the data
                var roomType = data.find(function(roomType) {
                    return roomType.room_type_id == selectedRoomType;
                });

                // Populate the room number dropdown
                var roomNumberDropdown = $('#editRoomId');
                roomNumberDropdown.empty();
                var roomNumbers = roomType.room_numbers.split(',');
                $.each(roomNumbers, function(key, roomNumber) {
                    roomNumberDropdown.append($('<option></option>').attr('value', roomNumber).text(roomNumber));
                });

                // Populate the adults dropdown
                var adultsDropdown = $('#editNumberOfAdults');
                adultsDropdown.empty();
                for (var i = 1; i <= roomType.room_type_max_capacity_adults; i++) {
                    adultsDropdown.append($('<option></option>').attr('value', i).text(i));
                }

                // Populate the kids dropdown
                var kidsDropdown = $('#editNumberOfChildren');
                kidsDropdown.empty();
                for (var i = 0; i <= roomType.room_type_max_capacity_kids; i++) {
                    kidsDropdown.append($('<option></option>').attr('value', i).text(i));
                }
            });
        }
    });
}


$(document).ready(function() {
    $('#editBookingDetailsForm').on('submit', function(e) {
        e.preventDefault();

        var roomType = $('#editRoomType').val();
        var roomId = $('#editRoomId').val();
        var numberOfAdults = $('#editNumberOfAdults').val();
        var numberOfChildren = $('#editNumberOfChildren').val();
        var newRoomPrice = $('#newRoomPrice').text();
        var newRoomItem = $('#newRoomItem').text();

        // Add newRoomItem text to the description
        var description = roomType + ' ' + newRoomItem;

        $.ajax({
            url: 'scripts/php/additional_booking.php', // replace with your PHP file
            type: 'POST',
            data: {
                booking_id: selectedBooking,
                room_id: roomId,
                number_of_adults: numberOfAdults,
                number_of_children: numberOfChildren,
                note: newRoomItem,
                bill_id: selectedBilling,
                description: newRoomItem,
                price: newRoomPrice
            },
            success: function(response) {
                // handle success
                alert('Room successfully added to the booking.');
                console.log(response);
                
                // Clear form selections
                $('#editRoomType').val('');
                $('#editRoomId').val('');
                $('#editNumberOfAdults').val('');
                $('#editNumberOfChildren').val('');
                $('#newRoomPrice').text('0');
                $('#newRoomItem').text('');

                // Run fillModal
                fillModal(selectedBooking);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // handle error
                alert('An error occurred while adding the room to the booking.');
                console.log(textStatus, errorThrown);
            }
        });
    });
});


$(document).ready(function() {
    // Form for adding new billing items
    $('#addBillingItemForm').on('submit', function(e) {
        e.preventDefault();

        var description = $('#description').val();
        var price = $('#price').val();
        var room_number = $('#room_number').val();

        $.ajax({
            url: 'scripts/php/add_bills_and_payment.php', // replace with your PHP file
            type: 'POST',
            data: {
                bill_id: selectedBilling,
                description: description,
                price: price,
                room_number: room_number
            },
            success: function(response) {
                // handle success
                alert('Billing item successfully added.');
                console.log(response);
                
                // Run fillModal
                fillModal(selectedBooking);
                refreshTable();
                
                // Clear form inputs
                $('#description').val('');
                $('#price').val('');
                $('#room_number').val('');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // handle error
                alert('An error occurred while adding the billing item.');
                console.log(textStatus, errorThrown);
            }
        });
    });

    // Form for adding new payments
    $('#addPaymentForm').on('submit', function(e) {
        e.preventDefault();

        var amount = $('#amount').val();
        var paymentMethod = $('#paymentMethod').val();
        var status = $('#status').val();

        $.ajax({
            url: 'scripts/php/add_bills_and_payment.php', // replace with your PHP file
            type: 'POST',
            data: {
                booking_id: selectedBooking,
                amount: amount,
                payment_method: paymentMethod,
                status: status
            },
            success: function(response) {
                // handle success
                alert('Payment successfully added.');
                console.log(response);
                
                // Run fillModal
                fillModal(selectedBooking);
                refreshTable();
            
                
                // Clear form inputs
                $('#amount').val('');
                $('#paymentMethod').val('');
                $('#status').val('');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // handle error
                alert('An error occurred while adding the payment.');
                console.log(textStatus, errorThrown);
            }
        });
    });
});


// UPDATE BOOKING STATUS
$(document).ready(function() {
    $('#updateBookingStatusForm').on('submit', function(e) {
        e.preventDefault();

        var bookingStatus = $('#bookingStatus').val();

        $.ajax({
            url: 'scripts/php/update_booking_status.php', // replace with your PHP file
            type: 'POST',
            data: {
                booking_id: selectedBooking,
                booking_status: bookingStatus
            },
            success: function(response) {
                // handle success
                alert('Booking status successfully updated.');
                // Run fillModal
                fillModal(selectedBooking);
                refreshTable();
                console.log(response);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // handle error
                alert('An error occurred while updating the booking status.');
                console.log(textStatus, errorThrown);
            }
        });
    });
});





$(document).ready(function() {
    $('#add_new').click(function() {
        checkInDate = new Date($('#check_in_date').val());
        checkOutDate = new Date($('#check_out_date').val());
        var formattedCheckInDate = checkInDate.toISOString().slice(0, 19).replace('T', ' ');
        var formattedCheckOutDate = checkOutDate.toISOString().slice(0, 19).replace('T', ' ');

        // Fetch available rooms
        fetchAvailableRooms(formattedCheckInDate, formattedCheckOutDate);
        // Show the modal
        $('#addBookingModal').css('display', 'block');
    });

    // Close the modal when the close button is clicked
    $('.close').click(function() {
        $('#addBookingModal').css('display', 'none');
    });
    var availableRooms1 = [];
    function fetchAvailableRooms(checkInDate, checkOutDate) {
        // Fetch the data from the PHP script
        $.ajax({
            url: 'scripts/php/fetch_available_rooms.php', // Replace with the actual PHP script URL
            method: 'GET',
            data: {
                check_in_date: checkInDate,
                check_out_date: checkOutDate
            },
            dataType: 'json',
            success: function (data) {
                availableRooms1 = data;
                // Populate the room type dropdown
                var roomTypeDropdown = $('#room_type_dropdown');
                roomTypeDropdown.empty();
                roomTypeDropdown.append('<option selected="true" disabled>Choose Room Type</option>');
                roomTypeDropdown.prop('selectedIndex', 0);
                $.each(data, function(key, entry) {
                    roomTypeDropdown.append($('<option></option>').attr('value', entry.room_type_id).text(entry.room_type_name));
                });

                // Update the other dropdowns when a room type is selected
                roomTypeDropdown.change(function() {
                    var selectedRoomType = $(this).val();

                    // Find the selected room type in the data
                    var roomType = data.find(function(roomType) {
                        return roomType.room_type_id == selectedRoomType;
                    });

                    // Populate the room number dropdown
                    var roomNumberDropdown = $('#room_number_dropdown');
                    roomNumberDropdown.empty();
                    var roomNumbers = roomType.room_numbers.split(',');
                    $.each(roomNumbers, function(key, roomNumber) {
                        roomNumberDropdown.append($('<option></option>').attr('value', roomNumber).text(roomNumber));
                    });

                    // Populate the adults dropdown
                    var adultsDropdown = $('#adults_dropdown');
                    adultsDropdown.empty();
                    for (var i = 1; i <= roomType.room_type_max_capacity_adults; i++) {
                        adultsDropdown.append($('<option></option>').attr('value', i).text(i));
                    }

                    // Populate the kids dropdown
                    var kidsDropdown = $('#kids_dropdown');
                    kidsDropdown.empty();
                    for (var i = 0; i <= roomType.room_type_max_capacity_kids; i++) {
                        kidsDropdown.append($('<option></option>').attr('value', i).text(i));
                    }
                });
            }
        });
    }
    
    // Assuming availableRooms is defined

    // Get the form and its elements
    var roomSelectionForm = document.getElementById('newBookingForm');
    var selectRoomType = document.getElementById('room_type_dropdown');
    var selectNumberOfAdults = document.getElementById('adults_dropdown');
    var selectNumberOfChildren = document.getElementById('kids_dropdown');
    var newRoomPrice = document.getElementById('newBookingPrice');
    var newRoomItem = document.getElementById('newBookingItem');
    var checkInDate = document.getElementById('check_in_date');
    var checkOutDate = document.getElementById('check_out_date');
    
    // Add event listener to the form
    roomSelectionForm.addEventListener('change', function(event) {
        // Check if the changed element is one of the select elements
        if (event.target === selectRoomType || event.target === selectNumberOfAdults || event.target === selectNumberOfChildren) {
            calculateTotalPrice1();
        }
    });

    function calculateTotalPrice1() {
        // Get the selected room type
        var selectedRoomType = availableRooms1.find(room => room.room_type_id == selectRoomType.value);
        if (!selectedRoomType) return;  // If no room type is selected, do nothing

        // Calculate the base price
        var totalPrice = parseFloat(selectedRoomType.room_type_price);
        var roomPrice = parseFloat(selectedRoomType.room_type_price);

        // Calculate the additional price for adults
        var numberOfAdults = parseInt(selectNumberOfAdults.value);
        var additionalAdults = 0;
        if (numberOfAdults > 2) {
            totalPrice += (numberOfAdults - 2) * 1500;
            additionalAdults += (numberOfAdults - 2);
        }

        // Calculate the additional price for children
        var numberOfChildren = parseInt(selectNumberOfChildren.value);
        totalPrice += numberOfChildren * 750;

        //console.log("Check In Date: ", checkInDate);
        // Calculate the number of days between check-in and check-out
        // Calculate the number of nights
        const checkInDate = new Date(document.getElementById("check_in_date").value);
        const checkOutDate = new Date(document.getElementById("check_out_date").value);

        var totalNights = 1;
        if (!isNaN(checkInDate) && !isNaN(checkOutDate)) {
          const checkIn = checkInDate.toISOString().slice(0, 19).replace('T', ' ');
          const checkOut = checkOutDate.toISOString().slice(0, 19).replace('T', ' ');

          const oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
          totalNights = Math.round(Math.abs((checkOutDate - checkInDate) / oneDay));

          console.log("Check In Date: ", checkIn);
          console.log("Check Out Date: ", checkOut);
          console.log("Total Nights: ", totalNights);
        } else {
          console.log("Invalid date input");
        }

        totalPrice *= totalNights;



        // Update the total price
        newRoomPrice.textContent = totalPrice.toFixed(2);
        // Update the total price
        newRoomItem.textContent = "(₱"+roomPrice.toFixed(2)+" per Day for "+totalNights+" Night(s) stay with "+additionalAdults+" Additional Adults and "+numberOfChildren+" Additional Children)";
    }
    
    // Set the minimum date for check-in to today
    var today = new Date();
    var tomorrow = new Date(today.getFullYear(), today.getMonth(), today.getDate() + 2);
    var minCheckInDate = today.toISOString().split('T')[0];
    var minCheckOutDate = tomorrow.toISOString().split('T')[0];

    // Create variables for default values
    var checkInDate = minCheckInDate;
    var checkOutDate = minCheckOutDate;

    // Set the default values for check-in and check-out
    $('#check_in_date').val(checkInDate);
    $('#check_out_date').val(checkOutDate);

    // Set the minimum date for check-in and check-out
    $('#check_in_date').attr('min', minCheckInDate);
    $('#check_out_date').attr('min', minCheckOutDate);

    // When a check-in date is selected
    $('#check_in_date').change(function() {
        // Set the check-out date to the day after the selected check-in date
        checkInDate = new Date($(this).val());
        checkOutDate = new Date(checkInDate.getFullYear(), checkInDate.getMonth(), checkInDate.getDate() + 2);

        // Set the minimum date for check-out to the day after the selected check-in date
        $('#check_out_date').val(checkOutDate.toISOString().split('T')[0]);
        $('#check_out_date').attr('min', checkOutDate.toISOString().split('T')[0]);

        var formattedCheckInDate = checkInDate.toISOString().slice(0, 19).replace('T', ' ');
        var formattedCheckOutDate = checkOutDate.toISOString().slice(0, 19).replace('T', ' ');

        // Fetch available rooms
        fetchAvailableRooms(formattedCheckInDate, formattedCheckOutDate);
    });

    // When a check-out date is selected
    $('#check_out_date').change(function() {
        checkInDate = new Date($('#check_in_date').val());
        checkOutDate = new Date($(this).val());
        var formattedCheckInDate = checkInDate.toISOString().slice(0, 19).replace('T', ' ');
        var formattedCheckOutDate = checkOutDate.toISOString().slice(0, 19).replace('T', ' ');

        // Fetch available rooms
        fetchAvailableRooms(formattedCheckInDate, formattedCheckOutDate);
    });

});


// Function to validate and sanitize form inputs
function validateAndSanitizeInputs() {
  const checkInDate = document.getElementById("check_in_date").value;
  const checkOutDate = document.getElementById("check_out_date").value;
  const firstName = document.getElementById("first_name").value;
  const lastName = document.getElementById("last_name").value;
  const email = document.getElementById("email").value;
  const cellphoneNumber = document.getElementById("cellphone_number").value;

  // Regular expression for a valid email format
  const emailRegex = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/i;


  // Regular expression for a valid Philippine mobile number
  const philippineMobileNumberRegex = /^(09\d{9})$/;
    
  // Validate check-in and check-out dates
  if (!checkInDate || !checkOutDate) {
    return false;
  }

  // Validate and sanitize other inputs
  if (!firstName || !lastName || !email || !cellphoneNumber) {
    return false;
  }

  if (!email.match(emailRegex)) {
    return false;
  }
    
  if (!cellphoneNumber.match(philippineMobileNumberRegex)) {
    return false;
  }

  // You can add more validation and sanitation here if needed

  return true;
}

// Function to enable or disable the submit button based on form validity
function updateSubmitButtonState() {
  const isValid = validateAndSanitizeInputs();
  document.getElementById("create_booking").disabled = !isValid;
}

// Attach input event listeners to form fields
document.getElementById("check_in_date").addEventListener("input", updateSubmitButtonState);
document.getElementById("check_out_date").addEventListener("input", updateSubmitButtonState);
document.getElementById("first_name").addEventListener("input", updateSubmitButtonState);
document.getElementById("last_name").addEventListener("input", updateSubmitButtonState);
document.getElementById("email").addEventListener("input", updateSubmitButtonState);
document.getElementById("cellphone_number").addEventListener("input", updateSubmitButtonState);

// Disable the submit button by default
document.getElementById("create_booking").disabled = true;

// Add an event listener for form submission
document.getElementById("newBookingForm").addEventListener("submit", function (event) {
  if (!validateAndSanitizeInputs()) {
    event.preventDefault(); // Prevent form submission if validation fails
  }
});



function clearFormFields() {
  document.getElementById("check_in_date").value = "";
  document.getElementById("check_out_date").value = "";
  document.getElementById("first_name").value = "";
  document.getElementById("last_name").value = "";
  document.getElementById("email").value = "";
  document.getElementById("cellphone_number").value = "";

  // You can add more fields to clear here if needed
}

document.getElementById("create_booking").addEventListener("click", function () {
  const form = document.getElementById("newBookingForm");

  var formData = {
        firstName: $("#first_name").val(),
        lastName: $("#last_name").val(),
        email: $("#email").val(),
        phoneNumber: $("#cellphone_number").val(),
        bookingData: [
            {
                roomNumber: $("#room_number_dropdown").val(),
                roomTypeName: $("#room_type_dropdown").val(),
                roomPrice: parseFloat($("#newBookingPrice").text()),
                adults: parseInt($("#adults_dropdown").val()),
                kids: parseInt($("#kids_dropdown").val()),
                checkInDate: $("#check_in_date").val(),
                checkOutDate: $("#check_out_date").val(),
                itemTotal: parseFloat($("#newBookingPrice").text()),
                notes: $("#newBookingItem").text()
            }
            // Add more booking data objects as needed
        ]
    };



    $.ajax({
        type: "POST",
        url: "scripts/php/create_reservation.php", // Replace with the actual path to your PHP script
        data: formData,
        success: function(response) {
            console.log(response);
            if (response.includes("Failed: ")) {
                alert("An error occurred while creating the reservation. Please try again.");
                //window.location.href = "select_room.php"; // Replace URL
            } else {
                alert("Reservation created successfully!");
                //window.location.href = "select_room.php"; // Replace URL
                clearFormFields();
                fillModal(selectedBooking);
                refreshTable();
                $('#addBookingModal').css('display', 'none');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
            alert("An error occurred while sending the request. Please try again.");
        }
    });
    
    
    
    
    

});

