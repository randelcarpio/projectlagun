function displayImages(elementId, phpScriptUrl, deleteScriptUrl) {
    var container = document.getElementById(elementId);
    if (!container) {
        console.error('Element with id ' + elementId + ' not found');
        return;
    }
    var select = document.getElementById('roomTypeSelect');
    var folderPath = select.value;
    console.log(folderPath);
    fetch(phpScriptUrl + '?folderPath=' + encodeURIComponent(folderPath))
        .then(response => response.json())
        .then(data => {
            // Clear the container
            while (container.firstChild) {
                container.removeChild(container.firstChild);
            }
            if (typeof data === 'string' && data.startsWith('Error:')) {
                console.error(data);
            } else {
                var row = document.createElement('div');
                row.className = 'row row-cols-1 row-cols-md-4 g-4';  // Updated classes for Bootstrap 5
                data.forEach(function(url) {
                    var col = document.createElement('div');
                    col.className = 'col';
                    var card = document.createElement('div');
                    card.className = 'card h-100 position-relative';  // Updated classes for Bootstrap 5
                    var img = document.createElement('img');
                    img.className = 'card-img-top';
                    img.style.objectFit = 'cover';  // Resize the image to fill the div, cropping it if necessary
                    img.style.aspectRatio = '1';  // Make the image square
                    img.src = url;
                    var btn = document.createElement('button');
                    btn.className = 'btn btn-danger position-absolute top-0 end-0';  // Position the button at the top right corner of the image
                    btn.style.zIndex = '1';  // Make sure the button is above the image
                    btn.innerHTML = '<i class="bi bi-trash"></i>';  // Use Bootstrap Icons for the trash icon
                    btn.onclick = function() {
                        fetch(deleteScriptUrl + '?imagePath=' + encodeURIComponent(url))
                            .then(response => response.json())
                            .then(data => {
                                if (typeof data === 'string' && data.startsWith('Error:')) {
                                    console.error(data);
                                } else {
                                    col.remove();
                                }
                            })
                            .catch(error => console.error('Error:', error));
                    };
                    card.appendChild(img);
                    card.appendChild(btn);
                    col.appendChild(card);
                    row.appendChild(col);
                });
                container.appendChild(row);
            }
        })
        .catch(error => console.error('Error:', error));
}




document.getElementById('fileInput').addEventListener('change', function() {
    var fileInput = document.getElementById('fileInput');
    var select = document.getElementById('roomTypeSelect');
    var folderPath = select.value;
    if (fileInput.files.length > 0) {
        var formData = new FormData();
        formData.append('image', fileInput.files[0]);
        formData.append('folderPath', folderPath);
        fetch('scripts/php/upload_image.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (typeof data === 'string' && data.startsWith('Error:')) {
                console.error(data);
            } else {
                // Refresh the image list
                displayImages('myDivId', 'scripts/php/folder_path.php', 'scripts/php/delete_image.php');
            }
        })
        .catch(error => console.error('Error:', error));
    }
});


document.getElementById('roomTypeSelect').addEventListener('change', function() {
    displayImages('myDivId', 'scripts/php/folder_path.php', 'scripts/php/delete_image.php');
});



fetch('scripts/php/fetch_room_types.php')
    .then(response => response.json())
    .then(data => {
        var select = document.getElementById('roomTypeSelect');
        // Clear the select
        while (select.firstChild) {
            select.removeChild(select.firstChild);
        }
        // Add an option for each room type
        data.forEach(function(roomType, index) {
            var option = document.createElement('option');
            option.value = roomType.room_type_picture_folder_path;
            option.textContent = roomType.room_type_name;
            select.appendChild(option);
            // Select the first option
            if (index === 0) {
                option.selected = true;
            }
        });
        // Load images for the selected room type
        displayImages('myDivId', 'scripts/php/folder_path.php', 'scripts/php/delete_image.php');
    })
    .catch(error => console.error('Error:', error));


