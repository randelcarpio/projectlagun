<?php
$base_dir = '../../booking/';
$folder_name = $_GET['folder_name'];

$dir = $base_dir . $folder_name . '/';

$files = scandir($dir);
$files = array_diff($files, array('.', '..')); // remove '.' and '..'

echo json_encode($files);
?>
