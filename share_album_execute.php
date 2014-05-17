<?php
include 'functions.php';
$link = spoj_s_db();   

mysql_query("DELETE FROM `Share` WHERE album_id=".mysql_escape_string($_GET['album_id']), $link);

$set_public = 0;
echo "DDD";
foreach($_POST as $value){
    if ($value == "public"){
        $set_public = 1; /* ak bolo nastavene, ze album ma byt verejny */
    }
    /* pridame tam zdielania, ktore prave zadal pouzivatel */
    mysql_query("INSERT INTO `Share` (`album_id`, `group_id`) VALUES ('".mysql_escape_string($_GET['album_id'])."', '".mysql_escape_string($value)."')", $link);
}
/* nastavime verejnost albumu podla toho, co prislo od uzivatela */
mysql_query("UPDATE `Album` SET `public`=".$set_public." WHERE id=".mysql_escape_string($_GET['album_id']), $link);
?>
