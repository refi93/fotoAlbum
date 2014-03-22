<?php

include 'config.php';
include 'functions.php';


$link = spoj_s_db();
mysql_query("INSERT INTO `Album` (`owner_id`,`name`) VALUES (1,'".$_POST['name']."')", $link);

?>

<script>
location.replace(document.referrer);
</script>