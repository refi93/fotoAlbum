<?php 

/* zoznam fotiek pre zmazanie */

include 'functions.php';
include 'config.php';
$path =  'http://' . $_SERVER['SERVER_NAME'] . '/~korbas4/fotoAlbum/get_image.php?image_id='; 
$files = scandir('images/'); 

image_page_header("Delete images");
echo_form_submit_script("#form1", "delete_image.php");
?>
</head>
<body>
<?php echo_logout(); ?>
<h1> Delete images</h1>

<br/>
<ul class="nav nav-pills">
    <li><?php echo "<a href = ".$base_path."images.php?album_id=".$_GET['album_id'].">back to album</a>"; ?></li>
</ul>
<br/>
<br/>

<div id ="images" style="display: inline-block">
<form action="delete_image.php" method="POST" id="form1">
<ul>
<?php $link = spoj_s_db();
	$result = mysql_query("SELECT * FROM  `Photo` WHERE album_id = ".mysql_escape_string($_GET['album_id']), $link);
    while ($row = mysql_fetch_assoc($result)) {
?>
<li>
<?php
        $file = $row['id'].'.jpg';
?>
    <a href="" onclick="return false;" > 
        <img src="./timthumb.php?id=<?php echo $row['id']; ?>" /> 
    </a>
    <input type="checkbox" name="<?php echo $row['id']; ?>" value= "<?php echo $row['id']; ?>" >
</li>
<?php
    }
?>
</div>
<br/>
<input type="submit" value="Delete selected images">
</form>
</body>
</html>