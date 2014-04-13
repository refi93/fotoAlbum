<?php
include 'functions.php';
session_destroy();
?>

<script>
location.replace(document.referrer);
</script>