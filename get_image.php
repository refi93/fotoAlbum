<?php
/* vrati obrazok s prislusnym id, ale len ak ma na to dany uzivatel pravo */

include 'config.php';
include 'functions.php';

$location = $IMAGES_LOCATION.$_GET['image_id'];
        if (file_exists($location) && check_login_image(trim($_GET['image_id'],".jpg"))) {

            header('Content-Type: image/jpeg');
            readfile($location);
                  
        } else {
            echo $location;
            die("Error: File not found.");
        } 
?>