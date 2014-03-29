<?php

include 'config.php';
include 'functions.php';


$link = spoj_s_db();
mysql_query("INSERT INTO `Album` (`owner_id`,`name`) VALUES (".$_SESSION['user_id'].",'".$_POST['name']."')", $link);

?>

<script>
location.replace(document.referrer);
</script>