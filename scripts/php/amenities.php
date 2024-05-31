<?php
// Define the clean_id function
function clean_id($string) {
    return preg_replace('/[^A-Za-z0-9\-]/', '_', $string); // Replaces all special characters with underscores
}

require_once('db_connect.php'); // Include the database connection file

// Fetch unique categories from the database
$category_query = "SELECT DISTINCT category FROM tbl_amenities";
$category_result = $conn->query($category_query);

// Create the tabs
echo '<ul class="nav nav-tabs" id="amenityTabs">';
$firstCategory = true;
while ($category_row = $category_result->fetch_assoc()) {
    $category = $category_row['category'];
    $id = clean_id($category); // Sanitize the category name
    $activeClass = $firstCategory ? 'active' : '';
    
    echo '<li class="nav-item">';
    echo '<a class="nav-link ' . $activeClass . '" id="' . $id . '-tab" data-toggle="tab" href="#' . $id . '" role="tab" aria-controls="' . $id . '" aria-selected="true">' . $category . '</a>';
    echo '</li>';
    
    $firstCategory = false;
}
echo '</ul>';

// Create the tab content
echo '<div class="tab-content" id="amenityTabContent">';
$firstCategory = true;
$category_result->data_seek(0);
while ($category_row = $category_result->fetch_assoc()) {
    $category = $category_row['category'];
    $id = clean_id($category); // Sanitize the category name
    $activeClass = $firstCategory ? 'show active' : '';
    
    echo '<div class="tab-pane fade ' . $activeClass . '" id="' . $id . '" role="tabpanel" aria-labelledby="' . $id . '-tab">';
    
    // Fetch amenities for the current category
    $amenity_query = "SELECT * FROM tbl_amenities WHERE category = '$category'";
    $amenity_result = $conn->query($amenity_query);
    
    echo '<div class="rounded-container">';
    while ($amenity_row = $amenity_result->fetch_assoc()) {
        $amenity_name = $amenity_row['amenity_name'];
        $description = $amenity_row['description'];
        $icon = 'fa-solid ' . $amenity_row['icon']; // Add 'fa-solid' before the icon name

        echo '<div class="rounded-item">';
        echo '<i class="' . $icon . '"></i>';
        echo '<span class="amenity-name" data-toggle="tooltip" title="' . $description . '">' . $amenity_name . '</span>';
        echo '</div>';
    }
    echo '</div>';
    
    echo '</div>';
    
    $firstCategory = false;
}

// Close the database connection
$conn->close();
?>
<!-- Include Bootstrap and Font Awesome -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="../../libs/fontawesome-free-6.4.2-web/css/all.css">

<!-- Include Bootstrap and jQuery JS (if not already included) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom CSS for rounded borders -->
<style>
.rounded-container {
    display: flex;
    flex-wrap: wrap;
}

.rounded-item {
    border: 1px solid #ccc;
    border-radius: 10px;
    padding: 10px;
    margin: 5px;
    display: flex; /* Add this line to make the icon and text inline */
    align-items: center; /* Vertically align icon and text */
}

.rounded-item i {
    margin-right: 10px; /* Add margin to the right of the icon */
}
</style>


<!-- Initialize Bootstrap tooltips -->
<script>
$('#amenityTabs a').on('click', function (e) {
  e.preventDefault();
  
  // Show the clicked tab's content
  var category = $(this).attr('id').replace('-tab', '');
  category = category.replace(/\//g, '\\/'); // Escape forward slashes
  
  // Use Bootstrap's 'tab' method to switch tabs
  $(this).tab('show');
});
</script>
