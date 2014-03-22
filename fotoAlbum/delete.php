<?php

include 'config.php';

foreach ($_GET as $value) {
    $path = $images_path.$value;
    if (file_exists($path)) {
        unlink($path);
    }
}

?>

<script>
location.replace(document.referrer);
</script>