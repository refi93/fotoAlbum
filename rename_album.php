<?php
    include 'functions.php';
    $link = spoj_s_db();
    
    /* presvedcime sa, ze mazany albym patri prihlasenemu pouzivatelovi */
    $result = mysql_query("SELECT * FROM `Album` WHERE `id`=".mysql_escape_string($_GET['album_id'])." AND `owner_id`=".mysql_escape_string($_SESSION['user_id']), $link); 
    if (mysql_num_rows($result) != 1){
        echo "ACCESS DENIED";
        return;    
    } 
    mysql_query('UPDATE `Album` SET `name`="'.mysql_escape_string($_POST['name']).'" WHERE `id`='.mysql_escape_string($_GET['album_id']), $link);    
?>

<script>
location.replace(document.referrer);
</script>