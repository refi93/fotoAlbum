<?php 
include 'functions.php';
include 'config.php';
$path =  'http://' . $_SERVER['SERVER_NAME'] . '/fotoAlbum/images/'; 

?>

<h1> Delete Albums </h1>


<a href ='albums.php'>back to albums</a>
<form action="delete_album.php" method="get">
<?php
    $link = spoj_s_db();
	$result = mysql_query("SELECT * FROM  `Album`", $link);	
	
    while ($row = mysql_fetch_assoc($result)) {
        $link_photo = spoj_s_db();
	    $result_photo = mysql_query("SELECT * FROM  `Photo` WHERE album_id = ".$row['id']." LIMIT 1", $link);
        
        $file = mysql_fetch_assoc($result_photo)['id'].'.jpg';
?>          
            <img src="scripts/timthumb.php?src=<?php echo $path . $file; ?>&h=194&w=224&zc=1&q=100" />
             <input type="checkbox" name="<?php echo $row['id']; ?>" value= "<?php echo $row['id']; ?>" >
<?php
    }
?>
<br/>
<input type="submit" value="Delete selected albums">
</form>