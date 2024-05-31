<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking</title>
    
    <!-- Include Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

   <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">


    <!-- Include Bootstrap Table CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.22.1/bootstrap-table.min.css">
    
    <!-- Include Modal CSS -->
    <link rel="stylesheet" href="css/modal_wide.css">
    
    <style>
        .overflow-auto {
            overflow-x: auto;
        }
    </style>
    
    
</head>
    
<body>
    
    <div class="container mt-5">
        <div class="row" id="roomCardsContainer">
            <!-- Room cards will be added here via JavaScript -->
        </div>
    </div>
    
    <!-- Modal for Room Details -->
    <div id="roomDetailsModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">×</span>
            <h4>Room Details</h4>
            <div class="row">
                <div class="col-md-4" id="third">
                    <!-- Room Images Container -->
                    <div id="roomImagesContainer">
                        <!-- Images will be added dynamically here -->
                    </div>
                </div>
                <div class="col-md-8">
                    <!-- Room Details -->
                    <h4 id="roomTypeName"></h4>
                    <p id="roomDescription"></p>
                    <p>Price: ₱<span id="roomPrice"></span></p>
                    <p>Max Adults: <span id="maxAdults"></span></p>
                    <p>Max Kids: <span id="maxKids"></span></p>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Backdrop -->
    <div class="modal-backdrop" style="display: none;"></div>



    
    
    
    
    <!--
    
    Sample of Uploads
    <?php
        //require('scripts/php/upload.php');
        //upload_file("images/folder/", "jpeg, png");
    ?>
    
    -->
    
    


    <!-- Scripts For All JS Scripts -->   
    <?php require('scripts/php/lib_link_bt.php') ?>
    
    <!-- Include Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+JwBzcy7uf3VG7pHsoXz8jOG2WBf/xUj6Ag5Uks5k9OXSnx0" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+JwBzcy7uf3VG7pHsoXz8jOG2WBf/xUj6Ag5Uks5k9OXSnx0" crossorigin="anonymous"></script>

    <script>
        
        // RESIZE DETECTOR
        window.addEventListener('resize', function() {
            var windowWidth = window.innerWidth;
            var container = document.getElementById('roomImagesContainer');

            if (windowWidth < 768) {
                container.style.display = 'flex';
                //container.style.flexWrap = 'nowrap';
                container.style.overflowX = 'auto';
            } else {
                container.style.display = '';
                container.style.flexWrap = '';
                container.style.overflowX = '';
            }
        });






        
        // Function to create a room card
        // Function to create a room card
        function createRoomCard(room) {
            const card = document.createElement('div');
            card.classList.add('col-md-4', 'mb-4');

            // Check if room.room_type_price is a valid number
            const price = typeof Number(room.room_type_price) === 'number' ? Number(room.room_type_price).toFixed(2) : 'N/A';

            // Function to get the first image in the folder or use default
            function getFirstImageOrDefault(folderPath, defaultImage) {
                return fetch(`${folderPath}`)
                    .then(response => response.text())
                    .then(data => {
                        const parser = new DOMParser();
                        const xmlDoc = parser.parseFromString(data, 'text/xml');
                        const imageElements = Array.from(xmlDoc.querySelectorAll('a[href$=".jpg"], a[href$=".png"]'));

                        if (imageElements.length > 0) {
                            return `${folderPath}/${imageElements[0].getAttribute('href')}`;
                        } else {
                            return defaultImage;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching image:', error);
                        return defaultImage;
                    });
            }

            // Get the first image in the folder or use default
            getFirstImageOrDefault(room.room_type_picture_folder_path, 'images/defaults/1.png')
                .then(imageSrc => {
                    card.innerHTML = `
                        <div class="card">
                            <img class="card-img-top" src="${imageSrc}" alt="${room.room_type_name}">
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



        // Function to load room cards via AJAX
        function loadRoomCards() {
            $.ajax({
                url: 'scripts/php/fetch_room_types.php', // Replace with the actual PHP script URL
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    const roomCardsContainer = document.getElementById('roomCardsContainer');
                    if (data && data.length > 0) {
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

       // Load room cards when the page loads
        $(document).ready(function () {
        loadRoomCards();

        $('#roomCardsContainer').on('click', '.view-details-button', function () {
            var roomTypeId = $(this).data('room-type-id');

            $.ajax({
                url: 'scripts/php/fetch_room_types.php',
                method: 'GET',
                data: { room_type_id: roomTypeId },
                dataType: 'json',
                success: function (room) {
                    $('#roomTypeName').text(room.room_type_name);
                    $('#roomDescription').text(room.room_type_description);
                    $('#roomPrice').text(room.room_type_price);
                    $('#maxAdults').text(room.room_type_max_capacity_adults);
                    $('#maxKids').text(room.room_type_max_capacity_kids);

                    var imageFolderPath = room.room_type_picture_folder_path;
                    var $imagesContainer = $('#roomImagesContainer');
                    $imagesContainer.empty();

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

                    $('#roomDetailsModal').css('display', 'block');
                    $('.modal-backdrop').css('display', 'block');
                },
                error: function () {
                    console.error('Error loading room details.');
                }
            });
        });

        $('.close').click(function () {
            $('#roomDetailsModal').css('display', 'none');
            $('.modal-backdrop').css('display', 'none');
        });
    });




        
    </script>

</body>
</html>
