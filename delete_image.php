<?php

include 'config.php';
include 'functions.php';

$link = spoj_s_db();

foreach ($_GET as $value) {
    
    $result = mysql_query("SELECT * FROM `Album` JOIN `User` ON (`Album`.owner_id = `User`.id AND `Album`.owner_id=".$_SESSION['user_id'].") JOIN `Photo` ON (`Album`.id = `Photo`.album_id AND `Photo`.id=".mysql_escape_string($value).")",$link);       
    if (mysql_num_rows($result) == 1){
        delete_image($value);
    }    
    else{
        echo "ACCESS DENIED";
        return;    
    }
}

?>

<script>
location.replace(document.referrer);
</script>