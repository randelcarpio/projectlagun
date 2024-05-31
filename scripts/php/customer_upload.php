<?php
$target_dir = "../../booking/" . $_POST['booking_id'] . "/";
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}
$target_file = $target_dir . "/" . basename($_FILES["file"]["name"]);
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if the file is a valid format
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "pdf" ) {
    echo "Sorry, only JPG, JPEG, PNG & PDF files are allowed.";
    exit;
}

// Check if the file size is within the limit (5MB)
if ($_FILES["file"]["size"] > 5000000) {
    echo "Sorry, your file is too large. The maximum file size is 5MB.";
    exit;
}

// Attempt to move the uploaded file to the target directory
if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
    echo "The file ". htmlspecialchars( basename( $_FILES["file"]["name"])). " has been uploaded.";
} else {
    echo "Sorry, there was an error uploading your file.";
}
?>
