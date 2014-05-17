<?php
/* odporucanie mien - pouziva sa pri zdielani albumu skupinam */
include 'functions.php';
$link = spoj_s_db();

/* retrieve the search term that autocomplete sends */
$term = trim(strip_tags($_GET['term'])); 
$a_json = array();
$a_json_row = array();
$result = mysql_query("SELECT * FROM User WHERE username LIKE '".mysql_escape_string($term)."%' AND username !='".mysql_escape_string($_SESSION['username'])."'",$link);
	while($row = mysql_fetch_assoc($result)) {
		$username = htmlentities(stripslashes($row['username']));
		$id = htmlentities(stripslashes($row['id']));
		$a_json_row["id"] = $id;
		$a_json_row["value"] = $username;
		$a_json_row["label"] = $username;
		array_push($a_json, $a_json_row);
	}

// jQuery wants JSON data

echo json_encode($a_json);
flush();
?>