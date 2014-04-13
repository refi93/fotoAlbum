<?php

include 'config.php';
include 'functions.php';

foreach ($_GET as $value) {
    $path = $images_path.$value.'.jpg';
    $link = spoj_s_db();
    
    // presvedcime sa, ze mazany albym patri prihlasenemu pouzivatelovi
    $result = mysql_query("SELECT * FROM `Album` WHERE `album_id`=".$value."AND `owner_id`=".$_SESSION['username'], $link); 
    if (mysql_num_rows($result) != 1){
        echo "ACCESS DENIED";
        return;    
    }   
    
    /* zmazeme vsetky fotky aj fyzicky */
    $result = mysql_query("SELECT `id` FROM `Photo` WHERE `album_id`=".$value, $link);
    while ($row = mysql_fetch_assoc($result)) {
        delete_image($row['id']);    
    }
     
    
    mysql_query("DELETE FROM `Album` WHERE `id`=".$value, $link);
}

?>

<script>
location.replace(document.referrer);
</script>