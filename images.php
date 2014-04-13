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

<div id="imagePageTop" style="margin-left: 4em;">
<?php echo_logout(); ?>

<h1><?php echo get_album_name($_GET['album_id']); ?></h1>


<?php
/* toto vypisujeme, len ak album patri prihlasenemu pouzivatelovi */
if (get_owner_id($_GET['album_id'])==$_SESSION['user_id']){
?>


<ul class="nav nav-pills">
    <li><a href ='albums.php'>back to albums</a></li>
    <li><a href ='share_album.php?album_id=<?php echo $_GET['album_id'];?>'>share album</a></li>
    <li><a href ='delete_image_list.php?album_id=<?php echo $_GET['album_id']?> '>delete images</a></li>
</ul>

<br/>
<br/>

<div id="mulitplefileuploader">Upload</div>

<div id="status"></div>
</div>
<?php 
}
?>

<?php 
	function random_orientation(){		
		$orientation = array('&h=194&w=224', '&h=224&w=194' );
		shuffle($orientation);
		foreach($orientation as $o){
			return $o;
		}		
	};
	
?>

<br/>
<br/>


<?php 
$path =  'http://' . $_SERVER['SERVER_NAME'] . '/fotoAlbum/images/'; 
//$files = scandir('images/'); 
?>
<div id ="images" style="display: inline-block">
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
    /* ak ide o prihlaseneho pouzivatela, dame mu moznost komentovat */
    
    
    $result = get_comments($_GET['album_id']);    
    while($row = mysql_fetch_assoc($result)){
?>
</ul>
</div>
    <p>
<?php
        echo $row['text']."<br/>".get_username($row['user_id'])."<br/>".date($row['time']);
?>
    </p>
<?php    
    }    
        
    
    if (isset($_SESSION['user_id'])){
?>
    <div id ="comment">
        <form action="add_comment.php?album_id=<?php echo $_GET['album_id']?>" method="post">
				<textarea id="comment_text" name="comment_text" placeholder="Comment here..." style="width: 30em; height:5em;" ></textarea> <br/>
				<input type="submit" value="Comment">        
        </form>
    </div>    
<?php  
    }
?>
<?php
bootstrap_scripts();
?>

</body>

</html>

