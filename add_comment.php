<?php

    /* pridanie komentu, ktory dostaneme metodou POST, do databazy */
	include('functions.php');
	
	$link = spoj_s_db();
	
	/* overime, ci je nastaveny album, ktory sa komentuje a ci je prihlaseny pouzivatel ma pravo komentovat dany album */	
	if(isset($_POST['album_id']) && (check_login_album($_POST['album_id']))){
		mysql_query("INSERT INTO `Comment`(`user_id`, `album_id`, `text`) VALUES (".$_SESSION['user_id'].",".mysql_escape_string($_POST['album_id']).",'".mysql_escape_string($_POST['comment_text'])."')", $link);
	}
?>