<?php
	include('functions.php');
	
	$link = spoj_s_db();
	
	/* overime, ci je nastaveny album, ktory sa komentuje a ci je prihlaseny pouzivatel ma pravo komentovat dany album */	
	if(isset($_GET['album_id']) && (check_login_album($_GET['album_id']))){
		echo "INSERT INTO `Comment`(`user_id`, `album_id`, `text`) VALUES (".$_SESSION['user_id'].",".$_GET['album_id'].",'".$_POST['comment_text']."')";
		mysql_query("INSERT INTO `Comment`(`user_id`, `album_id`, `text`) VALUES (".$_SESSION['user_id'].",".$_GET['album_id'].",'".$_POST['comment_text']."')", $link);
	}
?>

<script>
location.replace(document.referrer);
</script>