<?php
    include 'functions.php';
    $link = spoj_s_db();
	mysql_query('UPDATE `Album` SET `name`="'.$_POST['name'].'" WHERE `id`='.$_GET['album_id'], $link);    
?>

<script>
location.replace(document.referrer);
</script>