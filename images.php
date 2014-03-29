<?php 
if (!isset($_GET['album_id'])) {
    header('HTTP/1.0 403 Forbidden');
    die('You are not allowed to access this file.');     
}


include 'config.php'; 
include 'functions.php';

if (!check_login_album($_GET['album_id'])){
    echo "ACCESS DENIED";
    return;
}


image_page_header();
?>



<script>

$(document).ready(function()
{

var settings = {
	url: "upload.php?album_id=<?php echo $_GET['album_id'] ?>",
	method: "POST",
	allowedTypes:"jpg", /* inak "jpg,png,gif" */
	fileName: "myfile",
	multiple: true,
	onSuccess:function(files,data,xhr)
	{
		$("#status").html("<font color='green'>Upload successful</font>");
		location.reload();
	},
	onError: function(files,status,errMsg)
	{		
		$("#status").html("<font color='red'>Upload Failed</font>");
	}
}
$("#mulitplefileuploader").uploadFile(settings);
});
</script>


<body>

<h1>fotoAlbum</h1>

<div id="mulitplefileuploader">Upload</div>

<div id="status"></div>

<a href ='delete_image_list.php?album_id=<?php echo $_GET['album_id']?> '>delete images</a>
<br/>
<a href ='albums.php'>back to albums</a>
<br/>
<a href ='share_album.php'>share album</a>
<?php 
	function random_orientation(){		
		$orientation = array('&h=194&w=224', '&h=224&w=194' );
		shuffle($orientation);
		foreach($orientation as $o){
			return $o;
		}		
	};
	
?>


<?php 
$path =  'http://' . $_SERVER['SERVER_NAME'] . '/fotoAlbum/images/'; 
//$files = scandir('images/'); 
?>

<ul>


<?php 
    $link = spoj_s_db();
	$result = mysql_query("SELECT * FROM  `Photo` WHERE album_id =".$_GET['album_id'], $link);
    while ($row = mysql_fetch_assoc($result)) {
        $file = $row['id'].'.jpg';
?>
    <li ><a href="<?php echo $path . $file; ?>" rel='lightbox' ><img src="scripts/timthumb.php?src=<?php echo $path . $file; ?>&h=194&w=224&zc=1&q=100" /></a></li>
<?php
    }
?>
</ul>
</body>

</html>

