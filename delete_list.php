<?php 
include 'functions.php';
include 'config.php';
$path =  'http://' . $_SERVER['SERVER_NAME'] . '/fotoAlbum/images/'; 
$files = scandir('images/'); 
?>

<h1> Delete photographs</h1>
<?php echo "<a href = ".$base_path."images.php?album_id=".$_GET['album_id'].">back to album</a>"; ?>

<form action="delete.php" method="get">
<?php $link = spoj_s_db();
	$result = mysql_query("SELECT * FROM  `Photo` WHERE album_id = ".$_GET['album_id'], $link);
    while ($row = mysql_fetch_assoc($result)) {
        $file = $row['id'].'.jpg';
?>
    <img src="scripts/timthumb.php?src=<?php echo $path . $file; ?>&h=194&w=224&zc=1&q=100" />
    <input type="checkbox" name="<?php echo $row['id']; ?>" value= "<?php echo $row['id']; ?>" >
<?php
    }
?>
<br/>
<input type="submit" value="Delete selected photgraphs">
</form>
