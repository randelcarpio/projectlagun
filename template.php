<!DOCTYPE html>
<html lang="en">

<head>
    
    <?php require 'scripts/php/header.php'; ?>

    <!--CUSTOME FOR THIS PAGE-->
    <link rel="stylesheet" href="css/admin.css" />
</head>

<body>
    <?php 
        require('scripts/php/upload.php');
        upload_file('images/id/','jpeg, png', true);
    ?>
</body>

</html>