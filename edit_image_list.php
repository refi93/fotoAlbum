<?php 

/* zoznam fotiek na editovanie - uzivatel si moze vybrat, ktore fotky bude editovat */

include 'functions.php';
include 'config.php';
$path =  'http://' . $_SERVER['SERVER_NAME'] . '/~korbas4/fotoAlbum/get_image.php?image_id='; 

image_page_header("Edit image");
?>
</head>
<body>
<?php echo_logout(); ?>
<h1> Edit image</h1>

<br/>
<ul class="nav nav-pills">
    <li><?php echo "<a href = ".$base_path."images.php?album_id=".$_GET['album_id'].">back to album</a>"; ?></li>
</ul>
<br/>
<br/>

<div id ="images" style="display: inline-block">
<ul>
<?php $link = spoj_s_db();
	$result = mysql_query("SELECT * FROM  `Photo` WHERE album_id = ".mysql_escape_string($_GET['album_id']), $link);
    while ($row = mysql_fetch_assoc($result)) {
?>
<li>
<?php
        $file = $row['id'].'.jpg';
?>
    <a href="image_editor.php?image_id=<?php echo $row['id'];?>"> 
        <img src="./timthumb.php?id=<?php echo $row['id']; ?>" /> 
    </a>
</li>
<?php
    }
?>
</div>
<br/>
</body>
</html>