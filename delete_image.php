<?php

include 'config.php';
include 'functions.php';

foreach ($_GET as $value) {
    echo $value;
    delete_image($value);
}

?>

<script>
location.replace(document.referrer);
</script>