<?php
include 'functions.php';
$link = spoj_s_db();   

mysql_query("DELETE FROM `Share` WHERE album_id=".mysql_escape_string($_GET['album_id']), $link);

foreach($_POST as $value){
    /* pridame tam zdielania, ktore prave zadal pouzivatel */
    mysql_query("INSERT INTO `Share` (`album_id`, `group_id`) VALUES ('".mysql_escape_string($_GET['album_id'])."', '".mysql_escape_string($value)."')", $link);
}
?>

<script>
location.replace(document.referrer);
</script>
