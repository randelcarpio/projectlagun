//GLOBAL VARIABLES FROM THE SYSTEM

// FOR LOADING
$(document).ready(function() {
  $('#loadingSpinner').fadeOut('slow');
});

// RESIZE DETECTOR
window.addEventListener('resize', function() {
    var windowWidth = window.innerWidth;
    var container = document.getElementById('roomImagesContainer');

    if (windowWidth < 768) {
        container.style.display = 'flex';
        container.style.overflowX = 'auto';
    } else {
        container.style.display = '';
        container.style.flexWrap = '';
        container.style.overflowX = '';
    }
});


// RECEIPT
let bookingData = [];
function updateReceipt() {
    const receipt = $('#receipt');
    receipt.empty();

    let totalAmount = 0;
    bookingData = []; // Store booking data for each room

    $('.selectedRoomCard').each(function () {
        // Get the room type name, room number, and room price from the container
        const roomTypeName = $(this).find('.card-title').text().split(' - ')[0];
        const roomNumber = $(this).find('.card-title').text().split(' - ')[1].split(': ')[1];
        const roomPriceText = $(this).find('span').text().split(' ')[0];
        const roomPrice = parseFloat(roomPriceText.replace('₱', '').trim());

        // Get the check-in and check-out dates from the container
        const checkInDate = $('#check-in-date').val();
        const checkOutDate = $('#check-out-date').val();
        console.log("Check Out Date: ", checkOutDate);
        console.log("Check In Date: ", checkInDate);

        // Calculate the number of nights
        const checkIn = new Date(checkInDate);
        const checkOut = new Date(checkOutDate);
        const oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
        const totalNights = Math.round(Math.abs((checkOut - checkIn) / oneDay));

        console.log("Check Out Date", checkOut);

        // Get the number of adults and kids for this room
        const numAdults = $(`#selected-adults-${roomNumber}-${checkInDate}`).val();
        const numKids = $(`#selected-kids-${roomNumber}-${checkInDate}`).val();
        const notes = $(`#booking-requests-${roomTypeName}-${checkInDate}`).val();

        // Calculate additional fees
        const additionalAdults = numAdults > 2 ? numAdults - 2 : 0;
        const additionalKids = numKids > 0 ? numKids : 0;

        const totalRoomPrice = roomPrice * totalNights;
        const totalAdultsPrice = additionalAdults * 1500 * totalNights;
        const totalKidsPrice = additionalKids * 750 * totalNights;

        const itemTotal = totalRoomPrice + totalAdultsPrice + totalKidsPrice;

        totalAmount += itemTotal; // Add the itemTotal to the totalAmount

        // Store the booking data for each room, including check-in and check-out dates
        bookingData.push({
            roomTypeName: roomTypeName,
            roomNumber: roomNumber,
            adults: numAdults,
            kids: numKids,
            totalNights: totalNights,
            roomPrice: roomPrice,
            additionalAdults: additionalAdults,
            additionalKids: additionalKids,
            itemTotal: itemTotal,
            checkInDate: checkInDate,
            checkOutDate: checkOutDate,
            notes: notes,
        });

        // Format the itemTotal to include commas and two decimal places
        const formattedItemTotal = itemTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        const receiptItem = `
            <div class="receipt-item">
                <div class="item-name">Room: ${roomTypeName} - Room Number: ${roomNumber}</div>
                <div class="item-details" style="display: none">Check-in: ${checkInDate}, Check-out: ${checkOutDate}</div>
                <div class="item-details">Adults: ${numAdults} (${additionalAdults > 0 ? `+ ₱${additionalAdults * 1500 * totalNights}` : 'No additional fee'}), Kids: ${numKids} (${additionalKids > 0 ? `+ ₱${additionalKids * 750 * totalNights}` : 'No additional fee'})</div>
                <div class="item-price">₱${formattedItemTotal} for ${totalNights} nights (${roomPriceText.trim()} per night + additional person fees)</div>
            </div>
            <hr style="border-top: 1px dashed #000;">
        `;
        receipt.append(receiptItem);
    });

    // Format the totalAmount to include commas and two decimal places
    var vat = 12;
    const totalTax = (totalAmount*(vat/100)).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    const formattedTotalAmount = totalAmount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    const taxedTotal = (totalAmount + (totalAmount*(vat/100))).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    
    // Get the check-in and check-out dates from the container
    const checkInDate = $('#check-in-date').val();
    const checkOutDate = $('#check-out-date').val();
    console.log("Check Out Date: ", checkOutDate);
    console.log("Check In Date: ", checkInDate);

    // Calculate the number of nights
    const checkIn = new Date(checkInDate);
    const checkOut = new Date(checkOutDate);
    receipt.append(`
        <div class="item-details">Check-in: ${checkInDate}, Check-out: ${checkOutDate}</div>
        <hr style="border-top: 1px dashed #000;">
        <div class="total-amount" style="font-size: larger; font-weight: bold;">
            <div class="total-name">Total Amount:</div>
            <div class="total-price">Vatable Price: ₱${formattedTotalAmount}</div>
            <div class="total-price">${vat}% Vat: ₱${totalTax}</div>
            <div class="total-price">Total Price: ₱${taxedTotal}</div>
        </div>
    `);
    
    console.log(bookingData);
}









// Function to create a room card
function createRoomCard(room) {
    const card = document.createElement('div');
    card.classList.add('col-md-4', 'mb-4');

    // Check if room.room_type_price is a valid number
    const price = typeof Number(room.room_type_price) === 'number' ? Number(room.room_type_price).toFixed(2) : 'N/A';

    // Function to get the first image in the folder or use default
    function getFirstImage(folderPath) {
        return fetch(`scripts/php/fetch_images.php?path=${encodeURIComponent(folderPath)}`)
            .then(response => response.json())
            .then(files => files[2])
            .catch(error => {
                console.error('Error fetching image:', error);
            });
    }

    // Get the first image in the folder
    getFirstImage(room.room_type_picture_folder_path)
        .then(imageSrc => {
            card.innerHTML = `
                <div class="card mx-auto">
                    <img class="card-img-top" src="${imageSrc}" alt="${room.room_type_name}" style="object-fit: cover; height: calc((100vw / 3) * 2 / 3); width: 100%;">
                    <div class="card-body">
                        <h5 class="card-title">${room.room_type_name}</h5>
                        <p class="card-text">Price: ₱${price}</p>
                        <a href="#" class="btn btn-primary view-details-button" data-room-type-id="${room.room_type_id}">View Details</a>
                    </div>
                </div>
            `;
        });

    return card;
}


// GLOBAL VARIABLE: ROOM DATA
var roomData;

// Function to load room cards via AJAX
function loadRoomCards() {
    const checkInDate = document.getElementById('check-in-date').value;
    const checkOutDate = document.getElementById('check-out-date').value;
    console.log('Check-in Date:', checkInDate);
    console.log('Check-out Date:', checkOutDate);

    // Clear the existing cards in the container
    roomCardsContainer.innerHTML = '';

    $.ajax({
        url: 'scripts/php/fetch_available_rooms.php', // Replace with the actual PHP script URL
        method: 'GET',
        data: {
            check_in_date: checkInDate,
            check_out_date: checkOutDate
        },
        dataType: 'json',
        success: function (data) {
            const roomCardsContainer = document.getElementById('roomCardsContainer');
            if (data && data.length > 0) {
                roomData = data; // Store the data in the global variable
                data.forEach(function (room) {
                    const roomCard = createRoomCard(room);
                    roomCardsContainer.appendChild(roomCard);
                });
            } else {
                roomCardsContainer.innerHTML = '<p>No room types available.</p>';
            }
        },
        error: function () {
            console.error('Error loading room types.');
        }
    });
}


// Define a variable to store the current room type ID
var currentRoomTypeId;

// Load room cards when the page loads
$(document).ready(function () {
    //loadRoomCards();

    $('#roomCardsContainer').on('click', '.view-details-button', function () {
        var roomTypeId = $(this).data('room-type-id');
        currentRoomTypeId = $(this).data('room-type-id'); //FOR GLOBAL USE
        var room = roomData.find(room => room.room_type_id === roomTypeId);
        loadAmenities(roomTypeId);
        if (room) {
            // Use the room data to display room details, without making another AJAX request
            $('#roomTypeName').text(room.room_type_name);
            $('#roomDescription').text(room.room_type_description);
            $('#roomPrice').text(room.room_type_price);
            $('#maxAdults').text(room.room_type_max_capacity_adults);
            $('#maxKids').text(room.room_type_max_capacity_kids);
            $('#selected-adults').attr('max', room.room_type_max_capacity_adults);
            $('#selected-kids').attr('max', room.room_type_max_capacity_kids);

            var imageFolderPath = room.room_type_picture_folder_path;
            var $imagesContainer = $('#roomImagesContainer');
            $imagesContainer.empty();
            
            /*
            $.ajax({
                url: 'scripts/php/fetch_images.php',
                dataType: 'json',
                data: { path: imageFolderPath },
                success: function (images) {
                    console.log(Array.isArray(images));
                    Object.keys(images).forEach(function (key) {
                        var imagePath = images[key];
                        console.log('Image Path:', imagePath); // Log the image path

                        // Create a card for each image
                        var card = $('<div class="card mb-3"></div>');
                        var imgElement = $('<img src="' + imagePath + '" class="card-img-top" alt="Room Image">');
                        card.append(imgElement);

                        // Append the card to the images container
                        $imagesContainer.append(card);
                    });

                    // Set the first image as active
                    $imagesContainer.find('.card:first').addClass('active');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error('Error loading images:', textStatus, errorThrown);
                }
            });
            */
            
            $.ajax({
                url: 'scripts/php/folder_path.php',
                type: 'GET',
                data: {
                    folderPath: imageFolderPath
                },
                dataType: 'json',
                success: function(images) {
                    if (typeof images === 'string') {
                        // Handle error messages
                        console.error(images);
                    } else {
                        var html = '';
                        for (var i = 0; i < images.length; i++) {
                            html += '<a href="' + images[i] + '" data-lightbox="image-set"><img src="' + images[i] + '" class="img-thumbnail"></a>';
                        }
                        $imagesContainer.append(html);
                    }
                }
            });


            $('#roomDetailsModal').css('display', 'block');
            $('.modal-backdrop').css('display', 'block');
        } else {
            console.error('Room not found in roomData.');
        }
    });


    // BOOK ROOM WITH ADD TO CART LOGIC
    const selectedRoomsData = [];
    $('#book-room-button').click(function () {
    const roomTypeName = $('#roomTypeName').text();
    const roomPrice = $('#roomPrice').text();
    const maxAdults = $('#maxAdults').text();
    const maxKids = parseInt($('#maxKids').text(), 10);
    const adjustedMaxKids = maxKids + 1;
    const selectedAdults = $('#selected-adults').val();
    const selectedKids = $('#selected-kids').val();
    const checkInDate = $('#check-in-date').val(); // Get check-in date
    const checkOutDate = $('#check-out-date').val(); // Get check-out date

    if (currentRoomTypeId) {
        const roomIndex = roomData.findIndex(room => room.room_type_id === currentRoomTypeId);

        if (roomIndex !== -1) {
            const roomDataForType = roomData[roomIndex];

            if (roomDataForType && roomDataForType.room_numbers) {
                const roomNumbersArray = roomDataForType.room_numbers.split(',');

                // Check for room number availability, date conflicts, and add to the selectedRoomsData
                let roomNumber = null;
                for (const num of roomNumbersArray) {
                    // Get all bookings for this room number
                    const bookingsForRoomNumber = selectedRoomsData.filter(room => room.roomNumber === num);

                    if (bookingsForRoomNumber.length > 0) {
                        // Check for date conflicts with existing selected rooms
                        const isDateConflict = bookingsForRoomNumber.some(room => {
                            return (checkInDate >= room.checkInDate && checkInDate < room.checkOutDate) ||
                                (checkOutDate > room.checkInDate && checkOutDate <= room.checkOutDate);
                        });

                        if (!isDateConflict) {
                            roomNumber = num;
                            break;
                        }
                    } else {
                        roomNumber = num;
                        break;
                    }
                }

                if (roomNumber) {
                    const selectedRoomInfo = {
                        roomNumber: roomNumber,
                        checkInDate: checkInDate,
                        checkOutDate: checkOutDate
                    };
                    selectedRoomsData.push(selectedRoomInfo);

                    // Display the selected room card
                    const selectedRoomsContainer = $('#selected-rooms-container');
                    const selectedRoomsContainerRow = $('#selected-rooms-container-row');
                    const guestInformationContainer = $('#guest-information-container');
                    const additionalPersonFee = 1500;
                    const additionalKidFee = 750;
                    
                    // Calculate the number of nights
                    const checkIn = new Date(checkInDate);
                    const checkOut = new Date(checkOutDate);
                    const oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
                    const diffDays = Math.round(Math.abs((checkOut - checkIn) / oneDay));

                    // Calculate the initial total price
                    const initialTotalPrice = roomPrice * diffDays;

                    const selectedRoomCard = `
                        <div class="col-lg-6 mx-auto">
                            <div class="card mb-3 bg-secondary text-light selectedRoomCard">
                                <div class="card-body">
                                    <h5 class="card-title">${roomTypeName} - Room Number: ${roomNumber}</h5>
                                    <hr style="border-top: 2px solid white;">
                                    <span style="background-color: #007bff; color: white; padding: 10px; border-radius: .25rem;">₱${roomPrice.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} PER NIGHT</span>
                                    <br><br>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="selected-adults-${roomNumber}-${checkInDate}">Adults</label>
                                            <select class="form-control" id="selected-adults-${roomNumber}-${checkInDate}">
                                                ${Array.from({ length: maxAdults }, (_, i) => {
                                                    const fee = i >= 2 ? ` (+ ₱${(i - 1) * 1500})` : '';
                                                    return `<option value="${i + 1}">${i + 1}${fee}</option>`;
                                                }).join('')}
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="selected-kids-${roomNumber}-${checkInDate}">Kids</label>
                                            <select class="form-control" id="selected-kids-${roomNumber}-${checkInDate}">
                                                ${Array.from({ length: adjustedMaxKids }, (_, i) => {
                                                    const fee = i > 0 ? ` (+ ₱${i * 750})` : '';
                                                    return `<option value="${i}">${i}${fee}</option>`;
                                                }).join('')}
                                            </select>
                                        </div>
                                    </div>
                                    <p id="total-price-${roomNumber}-${checkInDate}" style="background-color: #28a745; color: white; padding: 10px; border-radius: .25rem; font-weight: bold; text-align: center;">Total Room Price: ₱${initialTotalPrice.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</p>
                                    <button class="btn btn-danger remove-room">Remove</button>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    /*
                    Removed
                    <div class="form-group col-md-6">
                        <label for="discount-upload-${roomTypeName}">Request Discount</label>
                        <small>If you want to request a discount, please upload your ID here and specify in the note for this room what the discount is for.</small>
                        <input type="file" class="form-control" id="discount-upload-${roomTypeName}" accept=".jpeg, .jpg, .png, .pdf">
                    </div>
                    
                    */

                    selectedRoomsContainerRow.append(selectedRoomCard);

                    // Rest of the code to update the receipt, scroll to the last element, and show containers
                    updateReceipt();

                    // Wait for the DOM to update by using a setTimeout
                    setTimeout(function () {
                        const newlyCreatedRoomElements = document.querySelectorAll('.selectedRoomCard');

                        // Scroll to the last element with the class if it exists
                        if (newlyCreatedRoomElements.length > 0) {
                            const lastNewlyCreatedRoomElement = newlyCreatedRoomElements[newlyCreatedRoomElements.length - 1];
                            const topPosition = lastNewlyCreatedRoomElement.getBoundingClientRect().top + window.scrollY;

                            window.scrollTo({
                                top: topPosition,
                                behavior: 'smooth',
                            });
                        }
                    }, 0);

                    // Show the "Selected Rooms" container
                    selectedRoomsContainer.show();
                    guestInformationContainer.show();

                    // Close the modal
                    $('#roomDetailsModal').css('display', 'none');
                    $('.modal-backdrop').css('display', 'none');
                } else {
                    // Display a message for no available rooms and suggest trying other dates
                    alert('No available rooms for this room type on the selected dates. Please try other dates or room types.');
                }
            } else {
                // Display an alert for "No More Rooms Available"
                alert('No more rooms available for this room type.');
            }
        } else {
            console.error('Room not found in roomData.');
        }
    } else {
        console.error('Room type ID not available.');
    }
});







    $('#selected-rooms-container').on('click', '.remove-room', function () {
        const roomTitle = $(this).closest('.card').find('.card-title').text();
        const checkInDate = $(this).closest('.selectedRoomCard').find('p').text().split(' || ')[0].split(': ')[1];
        const roomNumber = roomTitle.split(' - Room Number: ')[1];

        const roomIndex = selectedRoomsData.findIndex(data => data.roomNumber === roomNumber && data.checkInDate === checkInDate);
        if (roomIndex !== -1) {
            selectedRoomsData.splice(roomIndex, 1);
        }

        $(this).closest('.col-lg-6').remove();

        if ($('#selected-rooms-container .card').length === 0) {
            $('#selected-rooms-container').hide();
            $('#guest-information-container').hide();
        }
        
        var $container = $('#selected-rooms-container-row');
        $container.hide().show(0);

        updateReceipt();
    });






    $('.close').click(function () {
        $('#roomDetailsModal').css('display', 'none');
        $('.modal-backdrop').css('display', 'none');
        
    });
    
    
    

    
    
    
    // Script for Date Selector Fields
    // Get references to the check-in and check-out date fields
    const checkInDateField = document.getElementById('check-in-date');
    const checkOutDateField = document.getElementById('check-out-date');

    // Function to update the check-out date
    function updateCheckOutDate() {
        // Get the selected check-in date
        const selectedDate = new Date(checkInDateField.value);

        // Calculate the day after the selected check-in date
        const dayAfter = new Date(selectedDate);
        dayAfter.setDate(dayAfter.getDate() + 1);

        // Convert the dayAfter date to a string in the 'YYYY-MM-DD' format
        const dayAfterStr = dayAfter.toISOString().split('T')[0];

        // Update the check-out date field
        checkOutDateField.valueAsDate = dayAfter;
        checkOutDateField.min = dayAfterStr;
        loadRoomCards();
    }

    // Listen for changes to the check-in date field
    checkInDateField.addEventListener('change', updateCheckOutDate);

    // Initial update when the page loads
    updateCheckOutDate();

    // Add a click event listener to the button
    document.getElementById("find-rooms-button").addEventListener("click", function () {
        loadRoomCards();
    });
    
    $(document).on('change', 'select[id^="selected-adults-"], select[id^="selected-kids-"]', function() {
        // Call the updateReceipt function to update the total amount
        updateReceipt();

        /// Get the room number from the id of the select element
        const idParts = this.id.split('-');
        const roomNumber = idParts[idParts.length - 4];


        // Get the check-in date from the selected room card
        const checkInDate = $(this).closest('.selectedRoomCard').find('p').text().split(' || ')[0].split(': ')[1];

        console.log("Data: ", roomNumber, checkInDate);
        // Find the corresponding booking data for this room
        const roomBookingData = bookingData.find(data => data.roomNumber === roomNumber && data.checkInDate === checkInDate);

        if (roomBookingData) {
            // Calculate the new total price for this room
            const totalRoomPrice = roomBookingData.roomPrice * roomBookingData.totalNights;
            const totalAdultsPrice = roomBookingData.additionalAdults * 1500 * roomBookingData.totalNights;
            const totalKidsPrice = roomBookingData.additionalKids * 750 * roomBookingData.totalNights;
            const itemTotal = totalRoomPrice + totalAdultsPrice + totalKidsPrice;

            // Format the itemTotal to include commas and two decimal places
            const formattedItemTotal = itemTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

            // Update the total price in the div
$           (`#total-price-${roomNumber}-${checkInDate}`).text(`Total Room Price: ₱${formattedItemTotal}`);
        } else {
            console.log("DATA NOT FOUND");
        }
    });




    
    // Listen for changes in the 'check-out-date' input field
    $(document).on('change', '#check-out-date', function() {
        loadRoomCards();
        
        // Clear the content of '#selected-rooms-container'
        //$('#selected-rooms-container').empty();

        // Hide the forms
        //$('#selected-rooms-container').hide();
        //$('#guest-information-container').hide();

        // Call the 'updateReceipt()' function
        updateReceipt();
    });
    
    
    // Book Room
    $("#guest-information").submit(function(e) {
        e.preventDefault();

        var formData = {
            firstName: $("#firstName").val(),
            lastName: $("#lastName").val(),
            phoneNumber: $("#phoneNumber").val(),
            email: $("#email").val(),
            bookingData: bookingData
        };

        $.ajax({
            type: "POST",
            url: "scripts/php/create_reservation.php",
            data: formData,
            success: function(response) {
                console.log(response);
                if (response.includes("Failed: ")) {
                    alert("An error occurred while creating the reservation. Please try again.");
                    window.location.href = "select_room.php"; // Replace URL
                } else {
                    alert("Reservation created successfully!");
                    window.location.href = "select_room.php"; // Replace URL
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
                alert("An error occurred while sending the request. Please try again.");
            }
        });
    });

    // Call this function whenever you want to load the amenities
    // Call this function whenever you want to load the amenities
    // scripts/php/fetch_amenities_all.php
    function loadAmenities(room_number) {
        $.ajax({
            url: 'scripts/php/fetch_amenities_all.php',
            type: 'POST',
            data: {room_number: room_number},
            success: function(response) {
                var amenities = JSON.parse(response);

                var tabs = '';
                var tabContent = '';
                var firstCategory = true;

                for (var category in amenities) {
                    var id = category.replace(/[^A-Za-z0-9\-]/g, '_'); // Sanitize the category name

                    // Create the tabs
                    var activeClass = firstCategory ? 'active' : '';
                    tabs += '<li class="nav-item">';
                    tabs += '<a class="nav-link ' + activeClass + '" id="' + id + '-tab" data-toggle="tab" href="#' + id + '" role="tab" aria-controls="' + id + '" aria-selected="true">' + category + '</a>';
                    tabs += '</li>';

                    // Create the tab content
                    activeClass = firstCategory ? 'show active' : '';
                    tabContent += '<div class="tab-pane fade ' + activeClass + '" id="' + id + '" role="tabpanel" aria-labelledby="' + id + '-tab">';

                    for (var i = 0; i < amenities[category].length; i++) {
                        var amenity = amenities[category][i];
                        var icon = 'fas ' + amenity.icon; // Add 'fas' before the icon name

                        tabContent += '<div class="rounded-item">';
                        tabContent += '<i class="' + icon + '"></i>';
                        tabContent += '<span class="amenity-name" data-toggle="tooltip" title="' + amenity.description + '">' + amenity.amenity_name + '</span>';
                        tabContent += '</div>';
                    }

                    tabContent += '</div>';

                    firstCategory = false;
                }

                // Update the #amenities div
                $('#amenities').html('<ul class="nav nav-tabs" id="amenityTabs">' + tabs + '</ul>' + '<div class="tab-content" id="amenityTabContent">' + tabContent + '</div>');

                // Initialize Bootstrap tabs and tooltips
                $('#amenityTabs a').on('click', function (e) {
                    e.preventDefault();
                    $(this).tab('show');
                });
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    }




    
    
    
    



    
    
    
    
    
});




