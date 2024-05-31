<?php
$originalFolderPath = $_GET['folderPath'];
$folderPath = $originalFolderPath;
if (!file_exists($folderPath)) {
    $folderPath = '../../' . $folderPath;
    if (!file_exists($folderPath)) {
        echo json_encode('Error: Folder does not exist');
        exit;
    }
}
if (!is_readable($folderPath)) {
    echo json_encode('Error: Folder is not readable');
    exit;
}
$files = scandir($folderPath);
$images = array_filter($files, function($file) {
    return preg_match('/\.(jpg|jpeg|png|gif)$/i', $file);
});
$images = array_map(function($image) use ($originalFolderPath) {
    return $originalFolderPath . '/' . $image;
}, $images);
echo json_encode(array_values($images));
?>
