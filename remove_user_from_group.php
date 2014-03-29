<?php
	include 'functions.php';
	
	$link = spoj_s_db();
	
	/* presvedcime sa, ze ideme odstranovat zo skupiny, ktora patri prihlasenemu pouzivatelovi */
	$result = mysql_query("SELECT * FROM `Group` WHERE `id`=".$_GET['group_id']." AND `owner_id`=".$_SESSION['user_id'], $link);
	if (mysql_num_rows($result) != 1){
		echo "ACCESS DENIED";
		return;		
	}	
	
	mysql_query("DELETE FROM `GroupMembers` WHERE `member_id`=".$_GET['user_id']." AND `group_id`=".$_GET['group_id'], $link);
?>

<script>
location.replace(document.referrer);
</script>