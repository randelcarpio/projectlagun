<?php
function generatePhotoSwipeGallery($folderPath) {
    // Get the list of image files with multiple extensions in the specified folder
    $imageFiles = glob($folderPath . '/*.{jpg,jpeg,png}', GLOB_BRACE);

    if (count($imageFiles) === 0) {
        // If no images were found in the specified folder, try loading from the default folder
        $defaultFolderPath = 'images/Defaults';
        $imageFiles = glob($defaultFolderPath . '/*.{jpg,jpeg,png}', GLOB_BRACE);

        if (count($imageFiles) === 0) {
            echo "No images found in both the specified folder and the default folder.";
            return;
        }
    }
    
    // Include the required Bootstrap and PhotoSwipe dependencies
    $header = '
    <head>
        <title>PhotoSwipe Gallery</title>
        <!-- Bootstrap CSS -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
        <!-- PhotoSwipe CSS and ESM JS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/5.4.2/photoswipe.css">
        <script type="module">
            import PhotoSwipe from "https://cdnjs.cloudflare.com/ajax/libs/photoswipe/5.4.2/photoswipe.esm.min.js";
            import PhotoSwipeLightbox from "https://cdnjs.cloudflare.com/ajax/libs/photoswipe/5.4.2/photoswipe-lightbox.esm.min.js";

            const lightbox = new PhotoSwipeLightbox({
                // may select multiple "galleries"
                gallery: "#gallery--getting-started",

                // Elements within gallery (slides)
                children: "a",

                // setup PhotoSwipe Core dynamic import
                pswpModule: () => Promise.resolve(PhotoSwipe),
            });
            lightbox.init();
        </script>
    </head>';

    echo $header;
    echo '<body>';
    
    // Create the PhotoSwipe gallery HTML content based on image files
    echo '<div class="pswp-gallery pswp-gallery--single-column" id="gallery--getting-started">';
    foreach ($imageFiles as $imageFile) {
        // Get the image dimensions
        list($width, $height) = getimagesize($imageFile);
        echo '<a href="' . $imageFile . '" data-pswp-width="' . $width . '" data-pswp-height="' . $height . '" data-cropped="true" target="_blank">';
        echo '<img src="' . $imageFile . '" alt="" class="img-fluid"/>';
        echo '</a>';
    }
    echo '</div>';
    
    echo '<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>';
    echo '</body>';
    echo '</html>';
}


?>