<?php
$dir = '../../' . $_GET['path'];
$defaultDir = '../../images/Defaults';

if (!is_dir($dir) || !(new FilesystemIterator($dir))->valid()) {
    $dir = $defaultDir;
}

$files = array_diff(scandir($dir), array('.', '..'));
$files = array_filter($files, function ($file) {
    return preg_match('/\.(jpeg|png)$/i', $file);
});

// Prepend $dir to each filename and remove '../../'
$files = array_map(function ($file) use ($dir) {
    return str_replace('../../', '', $dir) . '/' . $file;
}, $files);

if (empty($files)) {
    $files = array_diff(scandir($defaultDir), array('.', '..'));
    $files = array_filter($files, function ($file) {
        return preg_match('/\.(jpeg|png)$/i', $file);
    });

    // Prepend $dir to each filename and remove '../../'
    $files = array_map(function ($file) use ($defaultDir) {
        return str_replace('../../', '/', $defaultDir) . '/' . $file;
    }, $files);
}

echo json_encode($files);
?>
