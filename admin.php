<?php

/* rozhranie pre admina, vidi zoznam userov, ich maily a ma pristup k ich profilom */

include 'config.php';
include 'functions.php';

$link = spoj_s_db();
$result = mysql_query("SELECT * FROM `User`", $link);

if ($_SESSION['username'] != 'admin'){
    header( 'Location: login.php'); 
}
while($row = mysql_fetch_assoc($result)){
    echo "<a href='albums.php?user_id=".$row['id']."'>".$row['username']."</a> &nbsp; ".$row['email']."<br>";
}

?>