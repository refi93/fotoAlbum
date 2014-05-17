<?php

/* zmananie vybranych obrazkov */

include 'config.php';
include 'functions.php';

$link = spoj_s_db();

/* tato funkcia da potrebne prava na mazanie aj adminovi, inak by ich nemal */
grant_privileges_to_admin(get_owner_id($_GET['album_id']));

foreach ($_POST as $value) {

    grant_privileges_to_admin(get_image_owner($value));
    /* overime, ci ma dany uzivatel pravo mazat dane fotky */
    $result = mysql_query("SELECT * FROM `Album` JOIN `User` ON (`Album`.owner_id = `User`.id AND `Album`.owner_id=".$_SESSION['user_id'].") JOIN `Photo` ON (`Album`.id = `Photo`.album_id AND `Photo`.id=".mysql_escape_string($value).")",$link);       
    if (mysql_num_rows($result) == 1){
        delete_image($value);
    }    
    else{
        echo "ACCESS DENIED";
        return;    
    }
}
/* vratenie prav do stavu pred zavolanim tohto skriptu */
restore_admin_privileges();

?>

<script>
location.replace(document.referrer);
</script>