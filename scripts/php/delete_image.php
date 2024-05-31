<?php
$imagePath = $_GET['imagePath'];
$imagePath = '../../' . $imagePath;
if (!file_exists($imagePath)) {
    echo json_encode('Error: Image does not exist');
    exit;
}
if (!is_writable($imagePath)) {
    echo json_encode('Error: Image is not writable');
    exit;
}
unlink($imagePath);
echo json_encode('Image deleted successfully');
?>
