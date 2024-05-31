<!DOCTYPE html>
<html>
<head>
    <title>Edit Room Photos</title>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        .custom-file-label::after {
            content: none;
        }
    </style>
</head>
<body>
    <?php
    require 'scripts/php/check_access.php'; // Path to your check_access.php file
    checkAccessLevel(['super_admin', 'admin']);
?>
    <div class="m-3">
    <select id="roomTypeSelect" class="form-select mb-3">
    </select>
    </div>
    <div class="card bg-dark text-white rounded p-3 m-3">
    <form id="uploadForm" enctype="multipart/form-data" class="mb-3">
        <input type="file" class="form-control-file" id="fileInput" name="image" accept="image/*" style="display: none;">
        <label class="btn btn-primary" for="fileInput">Upload Image</label>
    </form>
    <div id="myDivId" class=""></div>
    </div>

    <script src="scripts/js/images.js"></script>
    <script>
    displayImages('myDivId', 'scripts/php/folder_path.php', 'scripts/php/delete_image.php');
    </script>
</body>
</html>
z