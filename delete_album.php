<?php

include 'config.php';
include 'functions.php';

foreach ($_GET as $value) {
    $path = $images_path.$value.'.jpg';
    $link = spoj_s_db();
    $result = mysql_query("DELETE FROM `Album` WHERE `id`=".$value, $link);
}

?>

<script>
location.replace(document.referrer);
</script>