<?php 
include 'functions.php';
include 'config.php';
$path =  'http://' . $_SERVER['SERVER_NAME'] . '/~korbas4/fotoAlbum/get_image.php?image_id='; 
image_page_header("Delete album");
?>
</head>
<body>
<?php echo_logout(); ?>
<h1> Delete Albums </h1>

<br/>
<ul class="nav nav-pills">
    <li><a href ='./albums.php'>back to albums</a></li>
</ul>
<br/>
<br/>

<form action="delete_album.php" method="get">
<?php
    $link = spoj_s_db();
	$result = mysql_query("SELECT * FROM  `Album` WHERE owner_id=".$_SESSION['user_id'], $link);	
?>	
    <div id ="images" style="display: inline-block">
	<ul>
<?php
    while ($row = mysql_fetch_assoc($result)) {
?>
        <li>
<?php
        $link_photo = spoj_s_db();
	    $result_photo = mysql_query("SELECT * FROM  `Photo` WHERE album_id = ".mysql_escape_string($row['id'])." LIMIT 1", $link);
        
        $file = mysql_fetch_assoc($result_photo)['id'].'.jpg';
?>          
            
            <a href="" rel='lightbox' > 
                <img src="scripts/timthumb.php?src=<?php echo $TIMTHUMB_PATH . $file; ?>&h=194&w=224&zc=1&q=100" /> 
            </a>
            <span style="position: absolute; left: 30px; top: 220px;">
            <?php echo get_album_name($row['id']); ?>
            </span>
             <input type="checkbox" name="<?php echo $row['id']; ?>" value= "<?php echo $row['id']; ?>" >
        </li>
<?php
    }
?>
    </div>
<br/>
<br/>
<br/>
<input type="submit" value="Delete selected albums">
</form>

</body>
</html>