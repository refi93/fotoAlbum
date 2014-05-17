<?php
/* zmazanie commentu */
include 'config.php';
include 'functions.php';

$link = spoj_s_db();

$result = mysql_query("SELECT * FROM `Comment` WHERE `id`=".mysql_escape_string($_GET['comment_id']),$link);
grant_privileges_to_admin(mysql_fetch_assoc($result)['user_id']);


$result = mysql_query("SELECT * FROM `Comment` WHERE `id`=".mysql_escape_string($_GET['comment_id'])." AND user_id=".$_SESSION['user_id'],$link);
      
if ((mysql_num_rows($result) == 1)||(get_album_owner_of_comment($_GET['comment_id']) == $_SESSION['user_id'])){
    mysql_query("DELETE FROM `Comment` WHERE `id`=".mysql_escape_string($_GET['comment_id']), $link);
}
else{
    echo "ACCESS DENIED";
    return;    
}

restore_admin_privileges();
?>