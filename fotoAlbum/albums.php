<?php
    include 'functions.php';
    include 'config.php';    
    
    image_page_header();    
?>

<?php 
$path =  'http://' . $_SERVER['SERVER_NAME'] . '/fotoAlbum/images/'; 
$path_to_album = 'http://' . $_SERVER['SERVER_NAME'] . '/fotoAlbum/images.php'
?>
<h1> Albums </h1>

<ul>


<?php
    $link = spoj_s_db();
	$result = mysql_query("SELECT * FROM  `Album`", $link);	
	
    while ($row = mysql_fetch_assoc($result)) {
        $link_photo = spoj_s_db();
	    $result_photo = mysql_query("SELECT * FROM  `Photo` WHERE album_id = ".$row['id']." LIMIT 1", $link);
        
        $file = mysql_fetch_assoc($result_photo)['id'].'.jpg';
?>  
        <li >         
            <a href="<?php echo $path_to_album .'?album_id='.$row['id']; ?>" rel='lightbox' >
                <span style="position: absolute; left: 30px; top: 220px;"><?php echo $row['name']; ?></span> 
                <img src="scripts/timthumb.php?src=<?php echo $path . $file; ?>&h=194&w=224&zc=1&q=100" /> 
            </a>   
        </li>
<?php
    }
?>
</ul>