<!DOCTYPE html>
<html>
<head>
    <title>Side Navigation Bar</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    <style>
        #sidebar {
            width: 60px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: #086873; /* Updated color */
            transition: width 0.3s;
        }

        #sidebar:hover {
            width: 200px;
        }

        #sidebar a {
            color: #fff;
            display: block;
            width: 100%;
            line-height: 60px;
            text-decoration: none;
            box-sizing: border-box;
            transition: background 0.3s;
        }

        #sidebar a:hover {
            background: #495057;
        }

        #sidebar i {
            margin: 0 10px;
            width: 30px;
            text-align: center;
        }

        #sidebar span {
            display: none;
        }

        #sidebar:hover span {
            display: inline;
        }
    </style>
</head>
<body>
    <?php
    $navItems = array(
        array('icon' => 'fa-tachometer-alt', 'text' => 'Dashboard', 'link' => 'dashboard.php'),
        array('icon' => 'fa-book', 'text' => 'Bookings', 'link' => 'bookings.php')
        /* Add more items here in the format:
        array('icon' => 'icon-class', 'text' => 'Link Text', 'link' => 'page.php')
        */
    );

    echo '<div id="sidebar">';
    foreach ($navItems as $item) {
        echo '<a href="' . $item['link'] . '"><i class="fas ' . $item['icon'] . '"></i><span>' . $item['text'] . '</span></a>';
    }
    echo '</div>';
    ?>
    <script>
        // Highlight the current page in the navigation bar
        var links = document.querySelectorAll('#sidebar a');
        for (var i = 0; i < links.length; i++) {
            if (links[i].href == window.location.href) {
                links[i].style.backgroundColor = '#495057'; // Change this to any color you want
            }
        }
    </script>
</body>
</html>
