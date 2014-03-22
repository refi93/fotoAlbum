<?php

include 'config.php';
include 'functions.php';

foreach ($_GET as $value) {
    $path = $images_path.$value.'.jpg';
    $link = spoj_s_db();
    $result = mysql_query("DELETE FROM `Photo` WHERE `id`=".$value, $link);
    if (file_exists($path)) {
        unlink($path);
    }
}

?>

<script>
location.replace(document.referrer);
</script>